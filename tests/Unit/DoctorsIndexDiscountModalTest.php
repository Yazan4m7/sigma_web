<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class DoctorsIndexDiscountModalTest extends TestCase
{
    /** @test */
    public function discount_trigger_passes_the_selected_doctor_to_the_modal(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/clients/index.blade.php');
        $css = file_get_contents(__DIR__ . '/../../public/assets/css/pages/doctors-index.css');

        $this->assertStringContainsString('id="doctor-discount-link"', $view);
        $this->assertStringNotContainsString('id="doctor-discount-link doctor-edit-link"', $view);
        $this->assertStringContainsString('modal fade doctor-centered-fade-modal sigma-modal--clients-delete', $view);
        $this->assertStringContainsString('modal fade doctor-centered-fade-modal sigma-modal--clients-add', $view);
        $this->assertStringContainsString('id="doctor-discount-date" type="datetime-local" name="discount_date" class="form-control" required', $view);
        $this->assertStringContainsString("['doctor-payment-link', 'doctor-discount-link']", $view);
        $this->assertStringContainsString(".one('hidden.bs.modal.doctorDiscountHandoff', showDiscountModal)", $view);
        $this->assertStringContainsString("document.getElementById('doctor-discount-client-id').value = trigger?.dataset?.clientId || '';", $view);
        $this->assertStringContainsString("document.getElementById('doctor-discount-client-name').textContent = trigger?.dataset?.clientName || '-';", $view);
        $this->assertStringContainsString('.doctor-centered-fade-modal.fade .modal-content', $css);
        $this->assertStringContainsString('@media (prefers-reduced-motion: reduce)', $css);
        $this->assertStringContainsString('min-height: calc(100% - 1.5rem) !important', $css);
        $this->assertStringContainsString('height: calc(100vh - 1.5rem)', $css);
    }

    /** @test */
    public function account_discounts_use_the_same_safe_rule_for_display_and_deletion(): void
    {
        $invoice = new \App\invoice();
        $invoice->case_id = 0;
        $invoice->amount = -25;
        $this->assertTrue($invoice->isAccountDiscount());

        $invoice->case_id = -1;
        $this->assertTrue($invoice->isAccountDiscount());

        $invoice->case_id = 123;
        $this->assertFalse($invoice->isAccountDiscount());

        $invoice->case_id = 0;
        $invoice->amount = 25;
        $this->assertFalse($invoice->isAccountDiscount());

        $view = file_get_contents(__DIR__ . '/../../resources/views/generic/invoices-list.blade.php');
        $controller = file_get_contents(__DIR__ . '/../../app/Http/Controllers/ClientsController.php');

        $this->assertStringContainsString('@if($invoice->isAccountDiscount())', $view);
        $this->assertStringContainsString('Linked case unavailable', $view);
        $this->assertStringContainsString('$invoice->isAccountDiscount()', $controller);
        $this->assertStringContainsString('DB::transaction', $controller);
        $this->assertStringContainsString('$invoice->case_id = 0;', $controller);
    }
}
