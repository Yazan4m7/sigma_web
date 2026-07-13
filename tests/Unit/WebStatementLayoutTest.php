<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class WebStatementLayoutTest extends TestCase
{
    /** @test */
    public function web_and_pdf_statements_reuse_the_same_document_layout(): void
    {
        $webView = file_get_contents(__DIR__ . '/../../resources/views/clients/statement.blade.php');
        $pdfView = file_get_contents(__DIR__ . '/../../resources/views/clients/statement-pdf.blade.php');
        $document = file_get_contents(__DIR__ . '/../../resources/views/clients/partials/statement-document.blade.php');
        $sharedCss = file_get_contents(__DIR__ . '/../../public/assets/css/custom-styling.css');

        $sharedDocumentInclude = 'clients.partials.statement-document';

        $this->assertStringContainsString($sharedDocumentInclude, $webView);
        $this->assertStringContainsString($sharedDocumentInclude, $pdfView);
        $this->assertStringContainsString('class="statement-screen-header"', $webView);
        $this->assertStringContainsString('class="summary-table"', $document);
        $this->assertStringContainsString('class="transactions-table"', $document);
        $this->assertStringContainsString('title="Download PDF"', $webView);
        $this->assertStringContainsString("'statementTableId' => 'statement-transactions-table'", $webView);
        $this->assertStringContainsString('pageLength: 25', $webView);
        $this->assertStringContainsString("ordering: false", $webView);
        $this->assertStringContainsString('balanceDue.detach().insertBefore(pagination)', $webView);
        $this->assertStringContainsString('.summary-layout > tbody > tr {', $webView);
        $this->assertStringContainsString('flex: 0 0 50%;', $webView);
        $this->assertStringContainsString('max-width: 50%;', $webView);
        $this->assertStringContainsString('#statement-transactions-table_wrapper.dataTables_wrapper .dataTables_paginate .paginate_button', $sharedCss);
        $this->assertStringContainsString('#statement-transactions-table_wrapper.dataTables_wrapper .dataTables_paginate .paginate_button.current', $sharedCss);
        $this->assertStringContainsString('<td colspan="4" class="statement-total-spacer">', $document);
        $this->assertStringNotContainsString('Account Summery', $webView);
    }
}
