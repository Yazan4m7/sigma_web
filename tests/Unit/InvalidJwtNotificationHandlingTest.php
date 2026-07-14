<?php

namespace Tests\Unit;

use App\Http\Traits\helperTrait;
use Tests\TestCase as LaravelTestCase;

class InvalidJwtNotificationHandlingTest extends LaravelTestCase
{
    /** @test */
    public function invalid_google_jwt_responses_are_recognized_without_hiding_other_oauth_errors(): void
    {
        $helper = new InvalidJwtNotificationHelper();

        $this->assertTrue($helper->isInvalidJwt('invalid_grant', 'Invalid JWT Signature.'));
        $this->assertTrue($helper->isInvalidJwt('invalid_grant', 'JWT must be a short-lived token.'));
        $this->assertFalse($helper->isInvalidJwt('invalid_client', 'The OAuth client was not found.'));
    }

    /** @test */
    public function notification_senders_drop_an_invalid_jwt_without_starting_an_fcm_request(): void
    {
        $helper = new InvalidJwtNotificationHelper();

        $this->assertFalse($helper->sendCaseNotification('device-token', 'Title', 'Body'));
        $this->assertFalse($helper->sendPaymentNotification('device-token', 'Title', 'Body'));
    }

    /** @test */
    public function jwt_uses_the_new_service_account_and_google_token_audience(): void
    {
        $jwt = (new InvalidJwtNotificationHelper())->generateJWT();
        $segments = explode('.', $jwt);
        $payloadSegment = strtr($segments[1], '-_', '+/');
        $payloadSegment .= str_repeat('=', (4 - strlen($payloadSegment) % 4) % 4);
        $payload = json_decode(base64_decode($payloadSegment), true);

        $this->assertCount(3, $segments);
        $this->assertNotEmpty($payload['iss'] ?? null);
        $this->assertSame('https://oauth2.googleapis.com/token', $payload['aud'] ?? null);
    }
}

class InvalidJwtNotificationHelper
{
    use helperTrait;

    public function generateAccessToken(): ?string
    {
        return null;
    }

    public function isInvalidJwt(string $error, string $description): bool
    {
        return $this->isInvalidJwtOAuthError($error, $description);
    }
}
