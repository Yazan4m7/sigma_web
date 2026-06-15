<?php

return [
    'audits' => [
        'create-case' => [
            'path' => '/new-case',
            'viewports' => [
                'desktop' => ['width' => 1366, 'height' => 768],
                'mobile' => ['width' => 390, 'height' => 844],
            ],
            'required_controls' => [
                ['name' => 'Create case form', 'selector' => 'form.case-form'],
                ['name' => 'Doctor', 'selector' => '[name="doctor"]'],
                ['name' => 'Patient name', 'selector' => '[name="patient_name"]'],
                ['name' => 'Case ID month', 'selector' => '[name="caseId2"]'],
                ['name' => 'Case ID day', 'selector' => '[name="caseId3"]'],
                ['name' => 'Case ID number', 'selector' => '[name="caseId4"]'],
                ['name' => 'Impression type', 'selector' => '[name="impression_type"]'],
                ['name' => 'Delivery date', 'selector' => '[name="delivery_date"]'],
                ['name' => 'Select units button', 'selector' => '.slctUnitsBtn'],
                ['name' => 'Job type', 'selector' => '[name$="[jobType]"]'],
                ['name' => 'Material', 'selector' => '[name$="[material_id]"]'],
                ['name' => 'Color', 'selector' => '[name$="[color]"]'],
                ['name' => 'Style', 'selector' => '[name$="[style]"]'],
                ['name' => 'Submit button', 'selector' => 'form.case-form button[type="submit"], form.case-form .button.create-case-page'],
            ],
        ],
    ],
];
