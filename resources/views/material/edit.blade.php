@extends('layouts.app', ['pageSlug' => 'Edit Material'])
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.bootstrap4.min.css">

<!-- Choices.js CSS - Only for this page -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

<style>
    /* Premium Material Information Form - Teal Theme */
    * {
        box-sizing: border-box;
    }

    /* Form Container with Premium Styling */
    .modern-form-container {
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
        border-radius: 16px;
        box-shadow:
            0 4px 6px -1px rgba(15, 118, 110, 0.1),
            0 2px 4px -1px rgba(15, 118, 110, 0.06),
            0 1px 2px 0 rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(15, 118, 110, 0.08);
        padding: 40px;
        margin: 24px auto;

        position: relative;
        overflow: hidden;
    }

    .modern-form-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #0F766E 0%, #14B8A6 50%, #22D3EE 100%);
        border-radius: 16px 16px 0 0;
    }

    /* Section Headers with Teal Accent */
    .section-header {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0fdfa;

        padding: 16px 20px 12px 20px;
        margin: -8px -8px 24px -8px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
    }

    .section-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 20px;
        width: 60px;
        height: 3px;

        border-radius: 2px;
    }

    .section-header i {
        color: #0F766E;
        font-size: 18px;
        padding: 8px;

        border-radius: 8px;
        transition: all 0.3s ease;
    }

    /* Premium Form Layout */
    .form-section {
        margin-bottom: 40px;
        position: relative;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 32px;
        margin-bottom: 28px;
    }

    .workflow-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
        align-items: start;
    }

    /* Premium Labels */
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        position: relative;
    }

    .form-label::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 20px;
        height: 2px;
        background: linear-gradient(90deg, #14B8A6, #22D3EE);
        border-radius: 1px;
        opacity: 0.6;
    }

    /* Premium Input Fields */
    .form-input {
        width: 100%;
        height: 48px;
        padding: 0 20px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 500;
        color: #1e293b;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
    }

    .form-input:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 6px -1px rgba(15, 118, 110, 0.1);
    }

    .form-input:focus {
        outline: none;
        border-color: #0F766E;
        background: #ffffff;
        box-shadow:
            0 0 0 4px rgba(15, 118, 110, 0.12),
            0 4px 6px -1px rgba(15, 118, 110, 0.1);
        transform: translateY(-1px);
    }

    .form-input::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    /* Premium Multi-Select Dropdown */
    .form-select {
        width: 100%;
        min-height: 48px;
        padding: 12px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 500;
        color: #1e293b;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
    }

    /* Multi-select specific styling */
    .form-select[multiple] {
        min-height: 120px;
        height: auto;
    }

    .form-select[multiple] option {
        padding: 8px 12px;
        border-radius: 4px;
        margin: 2px 0;
    }

    .form-select[multiple] option:checked {
        background: linear-gradient(135deg, #0F766E 0%, #14B8A6 100%);
        color: white;
        font-weight: 600;
    }

    .form-select:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 6px -1px rgba(15, 118, 110, 0.1);
    }

    .form-select:focus {
        outline: none;
        border-color: #0F766E;
        background: #ffffff;
        box-shadow:
            0 0 0 4px rgba(15, 118, 110, 0.12),
            0 4px 6px -1px rgba(15, 118, 110, 0.1);
        transform: translateY(-1px);
    }

    /* Multi-select styling for Bootstrap Select */
    .selectpicker {
        border: 2px solid #e2e8f0 !important;
        border-radius: 10px !important;
        min-height: 48px !important;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .bootstrap-select .dropdown-toggle {
        border: 2px solid #e2e8f0 !important;
        border-radius: 10px !important;
        min-height: 48px !important;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
        padding: 12px 20px !important;
        font-size: 15px !important;
        font-weight: 500 !important;
        color: #1e293b !important;
    }

    .bootstrap-select .dropdown-toggle:focus,
    .bootstrap-select .dropdown-toggle:hover {
        border-color: #0F766E !important;
        box-shadow:
            0 0 0 4px rgba(15, 118, 110, 0.12),
            0 4px 6px -1px rgba(15, 118, 110, 0.1) !important;
        transform: translateY(-1px);
    }

    /* Premium Helper Text */
    .help-text {
        font-size: 12px;
        color: #64748b;
        margin-top: 6px;
        opacity: 1;
        transition: opacity 0.3s ease;
        font-weight: 400;
    }

    .help-text.hidden {
        opacity: 0;
        height: 0;
        margin: 0;
        overflow: hidden;
    }

    /* Premium Toggle Switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 54px;
        height: 30px;
        margin-top: 4px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 30px;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .toggle-slider:hover {
        box-shadow:
            inset 0 2px 4px rgba(0, 0, 0, 0.1),
            0 0 0 4px rgba(15, 118, 110, 0.1);
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 24px;
        width: 24px;
        left: 3px;
        top: 3px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    input:checked+.toggle-slider {
        background: linear-gradient(135deg, #0F766E 0%, #14B8A6 100%);
    }

    input:checked+.toggle-slider:before {
        transform: translateX(24px);
        box-shadow: 0 2px 8px rgba(15, 118, 110, 0.3);
    }

    /* Premium Radio and Checkbox Groups */
    .radio-group,
    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 8px;
    }

    .radio-group.vertical,
    .checkbox-group.vertical {
        flex-direction: column;
        gap: 12px;
    }

    .radio-option,
    .checkbox-option {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
        min-width: fit-content;
    }

    .radio-option:hover,
    .checkbox-option:hover {
        background: rgba(15, 118, 110, 0.05);
        transform: translateY(-1px);
    }

    .custom-radio,
    .custom-checkbox {
        position: relative;
        width: 20px;
        height: 20px;
        border: 2px solid #cbd5e1;
        border-radius: 50%;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }

    .custom-checkbox {
        border-radius: 6px;
    }

    .custom-radio input,
    .custom-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .custom-radio:hover,
    .custom-checkbox:hover {
        border-color: #0F766E;
        box-shadow:
            0 1px 3px rgba(0, 0, 0, 0.1),
            0 0 0 3px rgba(15, 118, 110, 0.1);
        transform: scale(1.05);
    }

    .custom-radio input:checked+.radio-indicator,
    .custom-checkbox input:checked+.checkbox-indicator {
        border-color: #0F766E;
        background: #0F766E;
        box-shadow: 0 2px 8px rgba(15, 118, 110, 0.3);
        transform: scale(1.1);
    }

    .radio-indicator,
    .checkbox-indicator {
        position: absolute;
        top: -2px;
        left: -2px;
        width: 20px;
        height: 20px;
        border: 2px solid #cbd5e1;
        border-radius: 50%;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .checkbox-indicator {
        border-radius: 6px;
    }

    .radio-indicator:after,
    .checkbox-indicator:after {
        content: '';
        position: absolute;
        display: none;
        transition: all 0.2s ease;
    }

    .custom-radio input:checked+.radio-indicator:after {
        display: block;
        top: 4px;
        left: 4px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: white;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .custom-checkbox input:checked+.checkbox-indicator:after {
        display: block;
        left: 6px;
        top: 3px;
        width: 4px;
        height: 8px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    /* Premium Material Types Section */
    .types-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: #f0fdfa;
        border: 2px solid #14B8A6;
        border-radius: 10px;
        color: #0F766E;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(15, 118, 110, 0.1);
    }

    .types-button:hover {
        background: #14B8A6;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.25);
    }

    .types-button i {
        transition: transform 0.3s ease;
    }

    .types-button:hover i {
        transform: rotate(15deg) scale(1.1);
    }

    /* Inline Add Type Input Styling */
    .add-type-container {
        margin-bottom: 16px;
    }

    .add-type-input-group {
        display: flex;
        gap: 8px;
        align-items: stretch;
    }

    .add-type-input {
        flex: 1;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        background: #ffffff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
    }

    .add-type-input:focus {
        border-color: #14B8A6;
        box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
        background: #f0fdfa;
    }

    .add-type-input::placeholder {
        color: #94a3b8;
        font-style: italic;
    }

    .add-type-button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        background: #14B8A6;
        border: 2px solid #14B8A6;
        border-radius: 10px;
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(20, 184, 166, 0.2);
    }

    .add-type-button:hover {
        background: #0F766E;
        border-color: #0F766E;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
    }

    .add-type-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(20, 184, 166, 0.2);
    }

    .add-type-button i {
        transition: transform 0.2s ease;
    }

    .add-type-button:hover i {
        transform: scale(1.1);
    }

    .selected-types-container {
        min-height: 120px;
        max-height: 240px;
        overflow-y: auto;
        padding: 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        margin-top: 12px;
        transition: all 0.3s ease;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .selected-types-container:hover {
        border-color: #cbd5e1;
        box-shadow:
            inset 0 1px 3px rgba(0, 0, 0, 0.05),
            0 0 0 3px rgba(15, 118, 110, 0.1);
    }

    .selected-type-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: #0F766E;
        color: white;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        margin: 3px 6px 3px 0;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(15, 118, 110, 0.2);
        cursor: pointer;
        position: relative;
    }

    .selected-type-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(15, 118, 110, 0.3);
        background: #115e59;
    }

    .selected-type-item.is-default {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
    }

    .selected-type-item.is-default:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        box-shadow: 0 4px 8px rgba(245, 158, 11, 0.4);
    }

    .selected-type-item.is-default::after {
        content: '★';
        position: absolute;
        top: -2px;
        right: -2px;
        background: #fbbf24;
        color: #92400e;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: bold;
    }

    .remove-selected {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        cursor: pointer;
        padding: 2px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .remove-selected:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    /* Premium Buttons */
    .btn-primary,
    button.btn-primary,
    .button-group .btn-primary {
        display: inline-flex !important;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: #0F766E !important;
        background-color: #0F766E !important;
        color: white !important;
        border: none !important;
        border-color: #0F766E !important;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow:
            0 2px 4px -1px rgba(15, 118, 110, 0.25),
            0 1px 2px -1px rgba(15, 118, 110, 0.06);
    }

    .btn-primary:hover,
    button.btn-primary:hover,
    .button-group .btn-primary:hover {
        background: #0d5b52 !important;
        background-color: #0d5b52 !important;
        border-color: #0d5b52 !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow:
            0 4px 8px -2px rgba(15, 118, 110, 0.35),
            0 2px 4px -1px rgba(15, 118, 110, 0.1);
    }

    .btn-primary:focus,
    button.btn-primary:focus,
    .button-group .btn-primary:focus {
        background: #0F766E !important;
        background-color: #0F766E !important;
        border-color: #0F766E !important;
        color: white !important;
        box-shadow:
            0 0 0 3px rgba(15, 118, 110, 0.2),
            0 4px 6px -1px rgba(15, 118, 110, 0.25);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: #f8fafc;
        color: #1e293b;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        color: #334155;
        border-color: #cbd5e1;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .button-group {
        display: flex;
        gap: 16px;
        margin-top: 40px;
        padding-top: 32px;
        border-top: 2px solid #f0fdfa;
        position: relative;
    }

    .button-group::before {
        content: '';
        position: absolute;
        top: -1px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 2px;
        background: linear-gradient(90deg, #0F766E, #14B8A6);
        border-radius: 1px;
    }

    /* Premium Modal Styles */
    .types-modal .modal-dialog {
        max-width: 700px;
    }

    .types-modal .modal-content {
        border: 1px solid rgba(15, 118, 110, 0.08);
        border-radius: 16px;
        box-shadow:
            0 4px 6px -1px rgba(15, 118, 110, 0.1),
            0 2px 4px -1px rgba(15, 118, 110, 0.06),
            0 25px 50px -12px rgba(0, 0, 0, 0.15);
        position: relative;
        overflow: hidden;
    }

    .types-modal .modal-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #0F766E 0%, #14B8A6 50%, #22D3EE 100%);
        border-radius: 16px 16px 0 0;
    }

    .types-modal .modal-header {
        display: none;
    }

    .types-modal .modal-body {
        padding: 0;
        background: white;
        margin-top: 4px;
    }

    .types-modal .modal-footer {
        padding: 20px 24px;
        background: #f8fafc;
        border-top: 2px solid #f0fdfa;
        border-radius: 0 0 16px 16px;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        position: relative;
    }

    .types-modal .modal-footer::before {
        content: '';
        position: absolute;
        top: -1px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 2px;
        background: linear-gradient(90deg, #0F766E, #14B8A6);
        border-radius: 1px;
    }

    .types-modal .btn-close {
        position: absolute;
        top: 16px;
        right: 16px;
        background: rgba(248, 250, 252, 0.9);
        border: 2px solid #e2e8f0;
        color: #475569;
        font-size: 16px;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .types-modal .btn-close:hover {
        background: #f1f5f9;
        border-color: #0F766E;
        color: #0F766E;
        transform: scale(1.05);
        box-shadow: 0 2px 4px rgba(15, 118, 110, 0.1);
    }

    /* Modal Button Styles */
    .types-modal .btn-secondary {
        padding: 12px 20px;
        font-size: 14px;
    }

    .types-modal .btn-success {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: #0F766E;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(15, 118, 110, 0.2);
    }

    .types-modal .btn-success:hover {
        background: #0d5b52;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(15, 118, 110, 0.3);
    }

    /* DataTable Styles */
    #typesTable {
        font-size: 14px;
        border-collapse: separate;
        border-spacing: 0;
    }

    #typesTable thead th {
        background: #F9FAFB;
        color: #374151;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: none;
        border-bottom: 1px solid #E5E7EB;
        padding: 12px 16px;
        text-align: left;
    }

    #typesTable tbody td {
        padding: 12px 16px;
        border: none;
        border-bottom: 1px solid #F3F4F6;
        font-size: 14px;
    }

    #typesTable tbody tr:hover {
        background: #F9FAFB;
    }

    #typesTable_wrapper .dt-buttons {
        padding: 16px;
        border-bottom: 1px solid #E5E7EB;
    }

    #typesTable_wrapper .dt-buttons .btn {
        background: #0F766E;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 1px 2px rgba(15, 118, 110, 0.2);
    }

    #typesTable_wrapper .dt-buttons .btn:hover {
        background: #0d5b52;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(15, 118, 110, 0.3);
    }

    /* DataTable Action Buttons - Smaller Edit/Delete buttons */
    #typesTable .btn-sm {
        padding: 4px 8px !important;
        font-size: 11px !important;
        border-radius: 4px !important;
        min-width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    #typesTable .btn-primary.btn-sm {
        background: #0F766E !important;
        background-color: #0F766E !important;
        border-color: #0F766E !important;
        color: white !important;
    }

    #typesTable .btn-primary.btn-sm:hover {
        background: #115e59 !important;
        background-color: #115e59 !important;
        border-color: #115e59 !important;
        transform: translateY(-1px);
    }

    #typesTable .btn-danger.btn-sm {
        background: #dc2626 !important;
        background-color: #dc2626 !important;
        border-color: #dc2626 !important;
        color: white !important;
    }

    #typesTable .btn-danger.btn-sm:hover {
        background: #b91c1c !important;
        background-color: #b91c1c !important;
        border-color: #b91c1c !important;
        transform: translateY(-1px);
    }

    /* Modal Dialog Button Styling */
    .modal .btn-secondary {
        background: #6b7280 !important;
        background-color: #6b7280 !important;
        border-color: #6b7280 !important;
        color: white !important;
        padding: 10px 20px !important;
        font-size: 13px !important;
        border-radius: 6px !important;
        font-weight: 500 !important;
    }

    .modal .btn-secondary:hover {
        background: #4b5563 !important;
        background-color: #4b5563 !important;
        border-color: #4b5563 !important;
        transform: translateY(-1px);
    }

    /* Modal Save/Action Button Styling */
    .modal .btn:not(.btn-secondary):not(.btn-close) {
        background: #0F766E !important;
        background-color: #0F766E !important;
        border-color: #0F766E !important;
        color: white !important;
        padding: 10px 20px !important;
        font-size: 13px !important;
        border-radius: 6px !important;
        font-weight: 600 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
    }

    .modal .btn:not(.btn-secondary):not(.btn-close):hover {
        background: #115e59 !important;
        background-color: #115e59 !important;
        border-color: #115e59 !important;
        transform: translateY(-1px);
    }

    /* Modal Close Button (X) Styling */
    .modal .btn-close {
        background: rgba(248, 250, 252, 0.9) !important;
        border: 2px solid #e2e8f0 !important;
        color: #475569 !important;
        font-size: 16px !important;
        width: 32px !important;
        height: 32px !important;
        border-radius: 6px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .modal .btn-close:hover {
        background: rgba(239, 68, 68, 0.1) !important;
        border-color: #fecaca !important;
        color: #dc2626 !important;
        transform: translateY(-1px);
    }

    #typesTable .edit-type {
        background: #0F766E !important;
        border-color: #0F766E !important;
        color: white !important;
    }

    #typesTable .edit-type:hover {
        background: #0d5b52 !important;
        border-color: #0d5b52 !important;
    }

    #typesTable .delete-type {
        background: #dc2626 !important;
        border-color: #dc2626 !important;
        color: white !important;
    }

    #typesTable .delete-type:hover {
        background: #b91c1c !important;
        border-color: #b91c1c !important;
    }

    /* Premium Responsive Design */
    @media (max-width: 1024px) {
        .workflow-columns {
            grid-template-columns: 1fr;
            gap: 40px;
        }

        .radio-group,
        .checkbox-group {
            flex-direction: column;
        }
    }

    @media (max-width: 768px) {
        .modern-form-container {
            margin: 12px;
            padding: 28px 20px;
        }

        .section-header {
            font-size: 18px;
            padding: 12px 16px 8px 16px;
            margin: -4px -4px 20px -4px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .button-group {
            flex-direction: column;
            gap: 12px;
        }

        .btn-primary,
        .btn-secondary {
            padding: 14px 24px;
            font-size: 14px;
        }

        .types-button {
            width: 100%;
            justify-content: center;
        }

        .selected-types-container {
            min-height: 100px;
        }
    }

    @media (max-width: 480px) {
        .modern-form-container {
            margin: 8px;
            padding: 20px 16px;
        }

        .form-input,
        .bootstrap-select .dropdown-toggle {
            height: 44px;
            padding: 0 16px;
            font-size: 14px;
        }

        .toggle-switch {
            width: 48px;
            height: 28px;
        }

        .toggle-slider:before {
            height: 22px;
            width: 22px;
        }

        input:checked+.toggle-slider:before {
            transform: translateX(20px);
        }
    }

    /* Configuration-based Helper Text Control */
    body.hide-helper-text .help-text {
        display: none;
    }

    /* Animation Keyframes for Premium Feel */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-section {
        animation: fadeInUp 0.6s ease-out;
    }

    .form-section:nth-child(2) {
        animation-delay: 0.1s;
    }

    .form-section:nth-child(3) {
        animation-delay: 0.2s;
    }
</style>
<style>
    .option-div {
        display: flex;
        align-items: center;
    }

    .option-div .btn {
        margin-left: auto;
    }

    .option-div .btn-group {
        display: flex;
    }

    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #ffffff;
        background-clip: border-box;
        border: 0.0625rem solid rgba(34, 42, 66, 0.05);
        border-radius: 0.2857rem;
        height: -webkit-fill-available;
    }

    .card .card-header {
        padding: 13px 16px !important;
    }

    .content {
        background-clip: border-box;
        border: 0 !important;
        border-radius: none;
        background: transparent;
        height: fit-content !important;
    }

    /* Choices.js Custom Styling - Only affects material-edit-choices class */
    .material-edit-choices .choices__inner {
        background-color: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 12px;
        min-height: 48px;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .material-edit-choices .choices__inner:hover {
        border-color: #14B8A6;
    }

    .material-edit-choices.is-focused .choices__inner,
    .material-edit-choices.is-open .choices__inner {
        border-color: #0F766E;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
    }

    .material-edit-choices .choices__list--multiple .choices__item {
        background-color: #0F766E;
        border: 1px solid #0F766E;
        border-radius: 6px;
        padding: 6px 10px;
        margin: 3px;
        font-size: 14px;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s ease;
    }

    .material-edit-choices .choices__list--multiple .choices__item:hover {
        background-color: #0d5f58;
        border-color: #0d5f58;
    }

    .material-edit-choices .choices__button {
        border-left: 1px solid rgba(255, 255, 255, 0.3);
        margin-left: 8px;
        padding-left: 8px;
        opacity: 0.8;
        transition: opacity 0.2s ease;
    }

    .material-edit-choices .choices__button:hover {
        opacity: 1;
    }

    .material-edit-choices .choices__list--dropdown {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-top: 4px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }

    .material-edit-choices .choices__list--dropdown .choices__item {
        padding: 12px 16px;
        font-size: 14px;
        transition: background-color 0.2s ease;
    }

    .material-edit-choices .choices__list--dropdown .choices__item--selectable {
        padding-right: 16px;
    }

    .material-edit-choices .choices__list--dropdown .choices__item--selectable:hover {
        background-color: #f0fdfa;
    }

    .material-edit-choices .choices__list--dropdown .choices__item--selectable.is-highlighted {
        background-color: #ccfbf1;
        color: #0F766E;
    }

    .material-edit-choices .choices__input {
        background-color: transparent;
        margin-bottom: 0;
        font-size: 15px;
        padding: 4px 0;
    }

    .material-edit-choices .choices__input::placeholder {
        color: #94a3b8;
    }

    .material-edit-choices .choices__placeholder {
        color: #94a3b8;
        opacity: 1;
    }

</style>

@section('content')
    <div class="modern-form-container">
        <form method="POST" action="{{ route('edit-material') }}">
            @csrf
            <!-- Material Info Section -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-cube"></i>
                    Material Information
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Material Name</label>
                        <input value="{{ $material->id }}" type="hidden" name="mat_id" />
                        <input class="form-input" type="text" name="mat_name" required
                            value="{{ $material->name }}" placeholder="Enter material name" />
                        <div class="help-text">E.g. Zircon, E.max, etc.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Price (JOD)</label>
                        <input class="form-input" type="number" name="price" required placeholder="0.00"
                            step="0.01" value="{{ $material->price }}" />
                        <div class="help-text">Price per unit in Jordanian Dinar</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Job Types</label>
                        <select id="jobTypes" name="jobTypes[]" multiple required>
                            @foreach ($jobTypes as $type)
                                <option value="{{ $type->id }}" {{ in_array($type->id, $matJobTypes) ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <div class="help-text">Select compatible job types</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Count as Unit</label>
                        <label class="toggle-switch">
                            <input type="checkbox" name="count_as_unit" {{ $material->count_as_unit == 1 ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <div class="help-text">Enable unit-based counting</div>
                    </div>
                </div>
            </div>

            <!-- Workflow Configuration Section -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-project-diagram"></i>
                    Workflow Configuration
                </div>

                <div class="workflow-columns">
                    <!-- Production Stages Column -->
                    <div class="form-group">
                        <div class="form-label">Production Stages</div>

                        <div class="checkbox-group">
                            <label class="checkbox-option">
                                <div class="custom-checkbox">
                                    <input type="checkbox" id="design" name="design" value="1" {{ $material->design ? 'checked' : '' }}>
                                    <div class="checkbox-indicator"></div>
                                </div>
                                <span>Design</span>
                            </label>

                            <label class="checkbox-option">
                                <div class="custom-checkbox">
                                    <input type="checkbox" id="finishing" name="finishing" value="6" {{ $material->finish ? 'checked' : '' }}>
                                    <div class="checkbox-indicator"></div>
                                </div>
                                <span>Finishing</span>
                            </label>

                            <label class="checkbox-option">
                                <div class="custom-checkbox">
                                    <input type="checkbox" id="qc" name="qc" value="7" {{ $material->qc ? 'checked' : '' }}>
                                    <div class="checkbox-indicator"></div>
                                </div>
                                <span>Quality Control</span>
                            </label>

                            <label class="checkbox-option">
                                <div class="custom-checkbox">
                                    <input type="checkbox" id="delivery" name="delivery" value="8" {{ $material->delivery ? 'checked' : '' }}>
                                    <div class="checkbox-indicator"></div>
                                </div>
                                <span>Delivery</span>
                            </label>
                        </div>

                        <div style="margin-top: 28px;">
                            <div class="form-label">Manufacturing Method</div>
                            <div class="radio-group">
                                <label class="radio-option">
                                    <div class="custom-radio">
                                        <input type="radio" id="noMilling" name="manufacturing" value="0" {{ !$material->mill && !$material->print_3d ? 'checked' : '' }}>
                                        <div class="radio-indicator"></div>
                                    </div>
                                    <span>None</span>
                                </label>

                                <label class="radio-option">
                                    <div class="custom-radio">
                                        <input type="radio" id="milling" name="manufacturing" value="2" {{ $material->mill ? 'checked' : '' }}>
                                        <div class="radio-indicator"></div>
                                    </div>
                                    <span>Milling</span>
                                </label>

                                <label class="radio-option">
                                    <div class="custom-radio">
                                        <input type="radio" id="3dPrinting" name="manufacturing" value="3" {{ $material->print_3d ? 'checked' : '' }}>
                                        <div class="radio-indicator"></div>
                                    </div>
                                    <span>3D Printing</span>
                                </label>
                            </div>
                        </div>

                        <div style="margin-top: 28px;">
                            <div class="form-label">Furnace Type</div>
                            <div class="radio-group">
                                <label class="radio-option">
                                    <div class="custom-radio">
                                        <input type="radio" id="furnace0" name="furnace" value="0" {{ !$material->sinter_furnace && !$material->press_furnace ? 'checked' : '' }}>
                                        <div class="radio-indicator"></div>
                                    </div>
                                    <span>None</span>
                                </label>

                                <label class="radio-option">
                                    <div class="custom-radio">
                                        <input type="radio" id="furnace1" name="furnace" value="4" {{ $material->sinter_furnace ? 'checked' : '' }}>
                                        <div class="radio-indicator"></div>
                                    </div>
                                    <span>Sintering Furnace</span>
                                </label>

                                <label class="radio-option">
                                    <div class="custom-radio">
                                        <input type="radio" id="furnace2" name="furnace" value="5" {{ $material->press_furnace ? 'checked' : '' }}>
                                        <div class="radio-indicator"></div>
                                    </div>
                                    <span>Press Furnace</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Material Types Column -->
                    <div class="form-group">
                        <div class="form-label">Material Types</div>

                        <!-- Inline Add Type Input -->
                        <div class="add-type-container">
                            <div class="add-type-input-group">
                                <input type="text"
                                       id="newTypeInput"
                                       class="add-type-input"
                                       placeholder="Enter new material type name"
                                       onkeypress="window.handleTypeInputKeypress(event)">
                                <button type="button"
                                        class="add-type-button"
                                        onclick="window.addNewMaterialType()"
                                        title="Add new material type">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Selected Types Display -->
                        <div id="selectedTypesDisplay">
                            <div class="selected-types-container">
                                <div style="color: #94a3b8; text-align: center; font-size: 14px; font-style: italic;">Loading types...</div>
                            </div>
                        </div>
                        <div class="help-text">Type a new material type name and click the + button to add it. Click on a type chip to set it as default.</div>

                        <!-- Default Type Selection -->
                        <div style="margin-top: 24px;">
                            <div class="form-label">Default Material Type</div>
                            <select class="form-select" id="default_type_id" name="default_type_id">
                                <option value="">Select Default Type</option>
                                @foreach ($material->types as $type)
                                    <option value="{{ $type->id }}" {{ $material->default_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <div class="help-text">This type will be pre-selected in material dropdowns throughout the system</div>
                        </div>

                        <div id="selectedTypesInputs">
                            @foreach($selectedTypes as $typeId)
                                <input type="hidden" name="materialTypes[]" value="{{ $typeId }}">
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- Form Actions -->
            <div class="button-group">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    Update Material
                </button>
                <button type="reset" class="btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </button>
            </div>
        </form>
    </div>
@endsection

<!-- Material Types Modal - Simplified -->
<div class="modal fade types-modal" id="typesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="table-container">
                    <table id="typesTable" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via DataTable -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="saveSelectedTypes">
                    <i class="fas fa-save"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>


@push('js')
<!-- Choices.js library - Only for this page -->
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>

<script>
// Global variables
let typesTable;
let allTypes = @json($types);
let selectedTypes = [];
let defaultTypeId = {{ $material->default_type_id ?? 'null' }};

// Define global function immediately
window.openTypesModal = function() {
    if (!typesTable) {
        // Initialize DataTable if not already done
        typesTable = $('#typesTable').DataTable({
            data: allTypes,
            columns: [
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    width: '60px',
                    render: function(data, type, row) {
                        const isSelected = selectedTypes.some(t => t.id === row.id);
                        return `<input type="checkbox" class="type-checkbox" data-id="${row.id}" ${isSelected ? 'checked' : ''}>`;
                    }
                },
                {
                    data: 'name',
                    width: '200px'
                },
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    width: '100px',
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-sm btn-primary edit-type" data-id="${row.id}" data-name="${row.name}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-type" data-id="${row.id}" data-name="${row.name}">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
                    }
                }
            ],
            dom: 'Brtip',
            buttons: [{
                text: '<i class="fas fa-plus"></i> Add New Type',
                className: 'btn btn-success btn-sm',
                action: function() {
                    if (window.showAddTypeModal) window.showAddTypeModal();
                }
            }],
            pageLength: 8,
            lengthChange: false,
            searching: false,
            info: false,
            responsive: true,
            autoWidth: false
        });

        // Set up event handlers after table initialization
        $('#typesTable tbody').on('change', '.type-checkbox', function() {
            const id = parseInt($(this).data('id'));
            const type = allTypes.find(t => t.id === id);

            if (this.checked) {
                if (!selectedTypes.some(t => t.id === id)) {
                    selectedTypes.push(type);
                }
            } else {
                selectedTypes = selectedTypes.filter(t => t.id !== id);
                // If this was the default type, clear the default
                if (defaultTypeId === id) {
                    defaultTypeId = null;
                }
            }

            if (window.updateSelectedTypesList) window.updateSelectedTypesList();
        });
    }
    $('#typesModal').modal('show');
};

// Initialize Choices.js for jobTypes multi-select
let jobTypesChoices = null;

(function initializeChoicesJS() {
    // Wait for Choices to be available
    const checkChoices = setInterval(function() {
        if (typeof Choices !== 'undefined') {
            clearInterval(checkChoices);

            try {
                console.log('Initializing jobTypes with Choices.js');

                const jobTypesElement = document.getElementById('jobTypes');

                if (jobTypesElement && !jobTypesChoices) {
                    jobTypesChoices = new Choices(jobTypesElement, {
                        removeItemButton: true,
                        searchEnabled: true,
                        searchChoices: true,
                        searchFloor: 1,
                        searchResultLimit: 10,
                        shouldSort: false,
                        placeholder: true,
                        placeholderValue: 'Select Job Types',
                        searchPlaceholderValue: 'Search job types...',
                        noResultsText: 'No job types found',
                        noChoicesText: 'No job types available',
                        itemSelectText: 'Click to select',
                        maxItemCount: -1
                    });

                    // Add custom class after initialization
                    const choicesContainer = jobTypesElement.parentElement;
                    choicesContainer.classList.add('material-edit-choices');

                    // Custom logic to show "X items selected" after 3 selections
                    const updateSelectedDisplay = () => {
                        const selectedValues = jobTypesChoices.getValue(true);
                        const itemsList = choicesContainer.querySelector('.choices__list--multiple');

                        if (selectedValues.length > 3 && itemsList) {
                            // Hide individual items
                            const items = itemsList.querySelectorAll('.choices__item');
                            items.forEach(item => item.style.display = 'none');

                            // Show count message
                            let countMsg = itemsList.querySelector('.choices-count-message');
                            if (!countMsg) {
                                countMsg = document.createElement('div');
                                countMsg.className = 'choices__item choices-count-message';
                                countMsg.style.cssText = 'background-color: #0F766E; color: white; padding: 6px 12px; border-radius: 6px; margin: 3px;';
                                itemsList.appendChild(countMsg);
                            }
                            countMsg.textContent = `${selectedValues.length} job types selected`;
                            countMsg.style.display = 'inline-flex';
                        } else if (itemsList) {
                            // Show individual items
                            const items = itemsList.querySelectorAll('.choices__item:not(.choices-count-message)');
                            items.forEach(item => item.style.display = 'inline-flex');

                            // Hide count message
                            const countMsg = itemsList.querySelector('.choices-count-message');
                            if (countMsg) countMsg.style.display = 'none';
                        }
                    };

                    // Listen for changes
                    jobTypesElement.addEventListener('change', updateSelectedDisplay);
                    jobTypesElement.addEventListener('addItem', updateSelectedDisplay);
                    jobTypesElement.addEventListener('removeItem', updateSelectedDisplay);

                    // Initial update
                    setTimeout(updateSelectedDisplay, 100);

                    console.log('jobTypes Choices.js initialized successfully');
                }
            } catch (error) {
                console.error('Error initializing Choices.js:', error);
            }
        }
    }, 100);

    // Timeout after 5 seconds
    setTimeout(function() {
        clearInterval(checkChoices);
    }, 5000);
})();

$(document).ready(function() {
    // Load pre-selected types from the material
    let preSelectedTypeIds = @json($selectedTypes);
    preSelectedTypeIds.forEach(typeId => {
        const type = allTypes.find(t => t.id === typeId);
        if (type) {
            selectedTypes.push(type);
        }
    });

    function initializeTypesTable() {
        typesTable = $('#typesTable').DataTable({
            data: allTypes,
            columns: [
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    width: '60px',
                    render: function(data, type, row) {
                        const isSelected = selectedTypes.some(t => t.id === row.id);
                        return `<input type="checkbox" class="type-checkbox" data-id="${row.id}" ${isSelected ? 'checked' : ''}>`;
                    }
                },
                {
                    data: 'name',
                    width: '200px',
                    render: function(data, type, row) {
                        return `<span class="editable-name" data-id="${row.id}">${data}</span>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    width: '100px',
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-sm btn-primary edit-type" data-id="${row.id}" data-name="${row.name}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-type" data-id="${row.id}" data-name="${row.name}">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
                    }
                }
            ],
            dom: 'Brtip',
            buttons: [
                {
                    text: '<i class="fas fa-plus"></i> Add New Type',
                    className: 'btn btn-success btn-sm',
                    action: function() {
                        showAddTypeModal();
                    }
                }
            ],
            pageLength: 8,
            lengthChange: false,
            searching: false,
            info: false,
            responsive: true,
            autoWidth: false,
            language: {
                emptyTable: "No types available"
            }
        });

        // Handle checkbox changes
        $('#typesTable tbody').on('change', '.type-checkbox', function() {
            const id = parseInt($(this).data('id'));
            const type = allTypes.find(t => t.id === id);

            if (this.checked) {
                if (!selectedTypes.some(t => t.id === id)) {
                    selectedTypes.push(type);
                }
            } else {
                selectedTypes = selectedTypes.filter(t => t.id !== id);
                // If this was the default type, clear the default
                if (defaultTypeId === id) {
                    defaultTypeId = null;
                }
            }

            updateSelectedTypesList();
        });

        // Handle edit buttons
        $('#typesTable tbody').on('click', '.edit-type', function() {
            const id = parseInt($(this).data('id'));
            const name = $(this).data('name');
            editType(id, name);
        });

        // Handle delete buttons
        $('#typesTable tbody').on('click', '.delete-type', function() {
            const id = parseInt($(this).data('id'));
            const name = $(this).data('name');
            deleteType(id, name);
        });
    }

    function updateSelectedTypesList() {
        window.updateSelectedTypesList = updateSelectedTypesList;
        const container = $('#selectedTypesDisplay .selected-types-container');

        if (selectedTypes.length === 0) {
            container.html('<p class="text-muted text-center">No types selected</p>');
            return;
        }

        let html = '';
        selectedTypes.forEach(type => {
            const isDefault = defaultTypeId === type.id;
            html += `
                <div class="selected-type-item ${isDefault ? 'is-default' : ''}" data-type-id="${type.id}">
                    <span>${type.name}</span>
                    <button class="remove-selected" data-id="${type.id}">×</button>
                </div>
            `;
        });

        container.html(html);

        // Handle remove buttons
        container.find('.remove-selected').click(function(e) {
            e.stopPropagation(); // Prevent triggering the click-to-set-default
            const id = parseInt($(this).data('id'));
            selectedTypes = selectedTypes.filter(t => t.id !== id);
            // If this was the default type, clear the default
            if (defaultTypeId === id) {
                defaultTypeId = null;
            }
            updateSelectedTypesList();
            updateSelectedTypesDisplay();
            // Update checkbox in table if table is initialized
            if (typesTable) {
                $(`.type-checkbox[data-id="${id}"]`).prop('checked', false);
            }
        });

        // Handle click-to-set-default functionality
        container.find('.selected-type-item').click(function(e) {
            // Don't trigger if clicking the remove button
            if ($(e.target).hasClass('remove-selected')) {
                return;
            }
            
            const typeId = parseInt($(this).data('type-id'));
            const type = selectedTypes.find(t => t.id === typeId);
            
            if (type) {
                // Show SweetAlert confirmation
                Swal.fire({
                    title: 'Set Default Material Type',
                    text: `Do you want to set "${type.name}" as the default choice?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0F766E',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, set as default',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        defaultTypeId = typeId;
                        updateSelectedTypesList();
                        updateSelectedTypesDisplay();
                        
                        Swal.fire({
                            title: 'Default Set!',
                            text: `"${type.name}" is now the default material type.`,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    }

    function editType(id, currentName) {
        const newName = prompt('Enter new type name:', currentName);
        if (newName && newName.trim() && newName.trim() !== currentName) {
            updateTypeName(id, newName.trim());
        }
    }

    function updateTypeName(id, newName) {
        $.ajax({
            url: `/admin/types/${id}`,
            type: 'PUT',
            data: {
                name: newName,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const typeIndex = allTypes.findIndex(t => t.id === id);
                    if (typeIndex > -1) {
                        allTypes[typeIndex].name = newName;
                    }

                    const selectedIndex = selectedTypes.findIndex(t => t.id === id);
                    if (selectedIndex > -1) {
                        selectedTypes[selectedIndex].name = newName;
                    }

                    typesTable.clear().rows.add(allTypes).draw();
                    updateSelectedTypesList();
                    showAlert('success', 'Type updated successfully!');
                } else {
                    showAlert('error', response.message || 'Failed to update type');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Failed to update type';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert('error', errorMessage);
            }
        });
    }

    function deleteType(id, name) {
        if (confirm(`Are you sure you want to delete "${name}"?`)) {
            $.ajax({
                url: `/admin/types/${id}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        allTypes = allTypes.filter(t => t.id !== id);
                        selectedTypes = selectedTypes.filter(t => t.id !== id);
                        // If this was the default type, clear the default
                        if (defaultTypeId === id) {
                            defaultTypeId = null;
                        }
                        typesTable.clear().rows.add(allTypes).draw();
                        updateSelectedTypesList();
                        showAlert('success', 'Type deleted successfully!');
                    } else {
                        showAlert('error', response.message || 'Failed to delete type');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to delete type';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showAlert('error', errorMessage);
                }
            });
        }
    }

    function showAddTypeModal() {
        window.showAddTypeModal = showAddTypeModal;
        const modalHtml = `
            <div class="modal fade" id="addTypeModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">Add New Material Type</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" style="background:none; border:none; color:white; font-size:1.5rem;">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form id="addTypeForm">
                                <div class="form-group mb-3">
                                    <label for="newTypeName">Type Name *</label>
                                    <input type="text" class="form-control" id="newTypeName" required placeholder="e.g. High Translucency">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-success" id="saveNewTypeBtn">
                                <i class="fas fa-save"></i> Save Type
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#addTypeModal').remove();
        $('body').append(modalHtml);

        const modal = new bootstrap.Modal(document.getElementById('addTypeModal'));
        modal.show();

        $('#saveNewTypeBtn').click(function() {
            saveNewType();
        });
    }

    function saveNewType() {
        const name = $('#newTypeName').val().trim();

        if (!name) {
            showAlert('error', 'Please enter a type name.');
            return;
        }

        $('#saveNewTypeBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: '{{ route('types.store') }}',
            type: 'POST',
            data: {
                name: name,
                is_enabled: 1,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    const newType = {
                        id: response.type.id,
                        name: response.type.name
                    };

                    allTypes.push(newType);
                    selectedTypes.push(newType);

                    typesTable.clear().rows.add(allTypes).draw();
                    updateSelectedTypesList();

                    $('#addTypeModal').modal('hide');
                    showAlert('success', 'Type added successfully!');
                } else {
                    showAlert('error', response.message || 'Failed to create type');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Failed to create type';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert('error', errorMessage);
            },
            complete: function() {
                $('#saveNewTypeBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Save Type');
            }
        });
    }

    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'check-circle' : 'exclamation-triangle';

        const alert = $(`
            <div class="alert ${alertClass} alert-dismissible fade show" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 350px;">
                <i class="fas fa-${icon}"></i> ${message}
                <button type="button" class="btn-close" data-dismiss="alert"></button>
            </div>
        `);

        $('body').append(alert);
        setTimeout(() => alert.fadeOut(() => alert.remove()), 4000);
    }

    // Global functions
    window.openTypesModal = function() {
        if (!typesTable) {
            initializeTypesTable();
        }
        $('#typesModal').modal('show');
    };

    // Save selected types
    $('#saveSelectedTypes').click(function() {
        updateSelectedTypesDisplay();
        updateSelectedTypesList();
        $('#typesModal').modal('hide');
        showAlert('success', `Selected ${selectedTypes.length} type(s) for this material`);
    });

    // Handle Enter key press in the input field
    window.handleTypeInputKeypress = function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            addNewMaterialType();
        }
    }

    // Add new material type function
    window.addNewMaterialType = function() {
        const input = document.getElementById('newTypeInput');
        const typeName = input.value.trim();

        if (!typeName) {
            showAlert('Please enter a material type name', 'warning');
            return;
        }

        // Check if type already exists
        const existingType = allTypes.find(type =>
            type.name.toLowerCase() === typeName.toLowerCase()
        );

        if (existingType) {
            // If type exists but not selected, add it to selected types
            const isAlreadySelected = selectedTypes.find(type => type.id === existingType.id);
            if (isAlreadySelected) {
                showAlert('This material type is already selected', 'info');
            } else {
                selectedTypes.push(existingType);
                updateSelectedTypesList();
                updateSelectedTypesDisplay();
                showAlert('Material type added to selection', 'success');
            }
        } else {
            // Create new type via AJAX
            $.ajax({
                url: '/materials/types/create',
                method: 'POST',
                data: {
                    name: typeName,
                    material_id: {{ $material->id }},
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        const newType = response.type;

                        // Add to allTypes array
                        allTypes.push(newType);

                        // Add to selected types
                        selectedTypes.push(newType);

                        // Update displays
                        updateSelectedTypesList();
                        updateSelectedTypesDisplay();

                        showAlert('New material type created and added', 'success');
                    } else {
                        showAlert('Error creating material type: ' + (response.message || 'Unknown error'), 'error');
                    }
                },
                error: function(xhr) {
                    let message = 'Error creating material type';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message += ': ' + xhr.responseJSON.message;
                    }
                    showAlert(message, 'error');
                }
            });
        }

        // Clear input
        input.value = '';
        input.focus();
    }

    function updateSelectedTypesDisplay() {
        const inputsContainer = $('#selectedTypesInputs');

        // Clear existing inputs
        inputsContainer.empty();

        // Add hidden inputs for form submission
        selectedTypes.forEach(type => {
            inputsContainer.append(`<input type="hidden" name="materialTypes[]" value="${type.id}">`);
        });

        // Add hidden input for default type
        if (defaultTypeId) {
            inputsContainer.append(`<input type="hidden" name="default_type_id" value="${defaultTypeId}">`);
        }

        // Update default type dropdown
        updateDefaultTypeDropdown();
    }

    function updateDefaultTypeDropdown() {
        const defaultTypeSelect = $('#default_type_id');
        
        if (selectedTypes.length === 0) {
            defaultTypeSelect.html('<option value="">No material types assigned</option>');
            defaultTypeSelect.prop('disabled', true);
        } else {
            let options = '<option value="">Select Default Type</option>';
            let hasCurrentSelection = false;
            
            selectedTypes.forEach(type => {
                const isSelected = defaultTypeId == type.id;
                if (isSelected) hasCurrentSelection = true;
                options += `<option value="${type.id}"${isSelected ? ' selected' : ''}>${type.name}</option>`;
            });
            
            defaultTypeSelect.html(options);
            defaultTypeSelect.prop('disabled', false);
            
            // Handle dropdown change
            defaultTypeSelect.off('change').on('change', function() {
                defaultTypeId = this.value ? parseInt(this.value) : null;
                updateSelectedTypesList();
                updateSelectedTypesDisplay();
            });
            
            // If the current selection is no longer valid, clear it
            if (defaultTypeId && !hasCurrentSelection) {
                defaultTypeId = null;
            }
        }
    }

    // Initialize display
    updateSelectedTypesDisplay();
    updateSelectedTypesList();
    
    // Initialize default type dropdown on page load
    updateDefaultTypeDropdown();
});

</script>
@endpush
