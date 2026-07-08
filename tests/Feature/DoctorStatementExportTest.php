<?php

namespace Tests\Feature;

use App\client;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DoctorStatementExportTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function statement_pdf_requires_an_authenticated_doctors_user(): void
    {
        $doctor = $this->createDoctor(true, 'Protected Doctor');

        $this->get(route('doctor-statement-pdf', [
            'doctor' => $doctor->id,
            'from' => '2026-07-01',
            'to' => '2026-07-31',
        ]))->assertRedirect(route('login'));
    }

    /** @test */
    public function enabled_doctor_statement_returns_a_non_cached_pdf(): void
    {
        $doctor = $this->createDoctor(true, 'طبيب تجريبي');

        $response = $this->actingAs($this->createAdmin())->get(route('doctor-statement-pdf', [
            'doctor' => $doctor->id,
            'from' => '2026-07-01',
            'to' => '2026-07-31',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('no-store', (string) $response->headers->get('cache-control'));
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    /** @test */
    public function statement_pdf_rejects_reversed_dates_and_disabled_doctors(): void
    {
        $admin = $this->createAdmin();
        $enabledDoctor = $this->createDoctor(true, 'Enabled Doctor');
        $disabledDoctor = $this->createDoctor(false, 'Disabled Doctor');

        $this->actingAs($admin)->getJson(route('doctor-statement-pdf', [
            'doctor' => $enabledDoctor->id,
            'from' => '2026-07-31',
            'to' => '2026-07-01',
        ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('to');

        $this->actingAs($admin)->getJson(route('doctor-statement-pdf', [
            'doctor' => $disabledDoctor->id,
            'from' => '2026-07-01',
            'to' => '2026-07-31',
        ]))->assertNotFound();
    }

    /** @test */
    public function batch_statement_picker_lists_only_enabled_doctors(): void
    {
        $enabledDoctor = $this->createDoctor(true, 'Enabled Batch Doctor');
        $disabledDoctor = $this->createDoctor(false, 'Disabled Batch Doctor');

        $response = $this->actingAs($this->createAdmin())->get(route('clients-index'));
        $response->assertOk();

        preg_match(
            '/<select[^>]+id="doctor-statements-doctors".*?<\/select>/s',
            $response->getContent(),
            $matches
        );

        $this->assertNotEmpty($matches);
        $this->assertStringContainsString('value="all" selected', $matches[0]);
        $this->assertStringContainsString('value="' . $enabledDoctor->id . '"', $matches[0]);
        $this->assertStringNotContainsString('value="' . $disabledDoctor->id . '"', $matches[0]);
        $response->assertSee("iosDtp_doctor_statements_from('date', false, false, false, 'wheel')", false);
        $response->assertSee("iosDtp_doctor_statements_to('date', false, false, false, 'wheel')", false);
        $response->assertSee("id: doctorStatementsPickerId()", false);
        $response->assertSee('shareZip: true', false);
        $response->assertSee('browserDownload: true', false);
        $response->assertSee('URL.createObjectURL(blob)', false);
        $response->assertSee('doctor-statements-footer-spacer', false);
        $response->assertSee('resetDoctorStatementsProgress();', false);
        $response->assertSee('clearDoctorStatementsFilters', false);
        $response->assertSee('doctor-statements-modal-open', false);
        $response->assertSee('doctor-statements-generate-btn', false);
        $response->assertSee('failedDoctorMessages', false);
        $response->assertDontSee('window.confirm(', false);
        $response->assertDontSee('Folder selection is unavailable', false);
        $response->assertDontSee('data-actions-box="true"', false);
        $response->assertDontSee('id="doctor-statements-reset"', false);
        $response->assertDontSee("$('#doctor-statements-doctors').selectpicker('refresh')", false);
        $this->assertLessThan(
            strpos($response->getContent(), 'aria-label="Mobile"'),
            strpos($response->getContent(), 'aria-label="Generate Statements"')
        );
    }

    private function createAdmin(): User
    {
        $suffix = uniqid('', true);
        $user = new User();
        $user->first_name = 'Statement';
        $user->last_name = 'Admin';
        $user->username = 'statement-admin-' . $suffix;
        $user->email = 'statement-' . $suffix . '@example.test';
        $user->phone = '0790000000';
        $user->password = Hash::make('password');
        $user->is_admin = 1;
        $user->status = 1;
        $user->save();

        return $user;
    }

    private function createDoctor(bool $active, string $name): client
    {
        $doctor = new client();
        $doctor->name = $name;
        $doctor->phone = '0790000000';
        $doctor->address = 'Test address';
        $doctor->active = $active ? 1 : 0;
        $doctor->save();

        return $doctor;
    }
}
