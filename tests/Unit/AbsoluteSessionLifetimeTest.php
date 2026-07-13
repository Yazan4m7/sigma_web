<?php

namespace Tests\Unit;

use App\Http\Middleware\EnforceAbsoluteSessionLifetime;
use Illuminate\Http\Request;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class AbsoluteSessionLifetimeTest extends TestCase
{
    public function test_authenticated_session_remains_valid_before_24_hours(): void
    {
        config(['session.absolute_lifetime' => 1440]);
        $request = $this->requestWithSession(['authenticated_at' => time() - (23 * 60 * 60)]);
        Auth::shouldReceive('check')->once()->andReturnTrue();

        $response = (new EnforceAbsoluteSessionLifetime())->handle($request, function () {
            return response('allowed', 200);
        });

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('allowed', $response->getContent());
    }

    public function test_authenticated_session_is_logged_out_after_24_hours(): void
    {
        config(['session.absolute_lifetime' => 1440]);
        $request = $this->requestWithSession([
            'authenticated_at' => time() - (24 * 60 * 60),
            'retained_value' => 'must be cleared',
        ]);
        $guard = Mockery::mock();

        Auth::shouldReceive('check')->once()->andReturnTrue();
        Auth::shouldReceive('guard')->once()->with('web')->andReturn($guard);
        $guard->shouldReceive('logout')->once();

        $response = (new EnforceAbsoluteSessionLifetime())->handle($request, function () {
            return response('should not run', 200);
        });

        $this->assertTrue($response->isRedirect(route('login')));
        $this->assertFalse($request->session()->has('authenticated_at'));
        $this->assertFalse($request->session()->has('retained_value'));
    }

    public function test_legacy_session_without_login_timestamp_is_expired(): void
    {
        $request = $this->requestWithSession();
        $guard = Mockery::mock();

        Auth::shouldReceive('check')->once()->andReturnTrue();
        Auth::shouldReceive('guard')->once()->with('web')->andReturn($guard);
        $guard->shouldReceive('logout')->once();

        $response = (new EnforceAbsoluteSessionLifetime())->handle($request, function () {
            return response('should not run', 200);
        });

        $this->assertTrue($response->isRedirect(route('login')));
    }

    public function test_login_timestamp_and_web_middleware_are_wired(): void
    {
        $loginController = file_get_contents(__DIR__ . '/../../app/Http/Controllers/Auth/LoginController.php');
        $kernel = file_get_contents(__DIR__ . '/../../app/Http/Kernel.php');

        $this->assertStringContainsString("'authenticated_at' => now()->timestamp", $loginController);
        $this->assertStringContainsString(EnforceAbsoluteSessionLifetime::class, $kernel);
        $this->assertLessThan(
            strpos($kernel, EnforceAbsoluteSessionLifetime::class),
            strpos($kernel, '\\Illuminate\\Session\\Middleware\\StartSession::class')
        );
    }

    private function requestWithSession(array $values = []): Request
    {
        $request = Request::create('/operations-dashboard', 'GET');
        $session = new Store('absolute-session-test', new ArraySessionHandler(1440 * 60));
        $session->start();
        $session->put($values);
        $request->setLaravelSession($session);

        return $request;
    }
}
