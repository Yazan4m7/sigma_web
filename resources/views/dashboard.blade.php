@extends('layouts.app', ['pageSlug' => 'Home'])

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/css/magnific-popup.css"/>
@endpush

@section('content')
    <style>
        /**:not(.modal-backdrop):not(.modal) {*/
        /*    z-index: 1 !important;*/
        /*}*/


        .pageTitleContainer {

            background: rgba(62, 62, 62, 0);
        }
        #datatable_wrapper {
            

        }
        /*.modal{*/
        /*    position: absolute;*/
        /*    right:0;*/
        /*    left:0;*/
        /*    top: 0;*/
        /*    bottom: 0;*/
        /*    z-index: 99999999;*/
        /*    margin:0;*/
        /*}*/
        /*.modal{*/
        /*    z-index: 99999999;*/

        /*}*/

        /*.modal {*/
        /*    z-index: 99999999 !important;*/
        /*}*/

        /*.modal-backdrop {*/
        /*    z-index: 99999998 !important;*/
        /*}*/
        
.modal.sigma-modal--abutments-delivery-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--abutments-delivery-receive {
            z-index: 99999999;
        }
.modal.sigma-modal--abutments-index-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--accountant-delivery-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--accountant-delivery-actions-alt {
            z-index: 99999999;
        }
.modal.sigma-modal--admin-intel-shortcut {
            z-index: 99999999;
        }
.modal.sigma-modal--admin-intel-settings {
            z-index: 99999999;
        }
.modal.sigma-modal--admin-intel2-shortcut {
            z-index: 99999999;
        }
.modal.sigma-modal--admin-intel2-settings {
            z-index: 99999999;
        }
.modal.sigma-modal--admin-mobile-access {
            z-index: 99999999;
        }
.modal.sigma-modal--cases-dashboard-case-completion {
            z-index: 99999999;
        }
.modal.sigma-modal--cases-dashboard-case-completion-alt {
            z-index: 99999999;
        }
.modal.sigma-modal--cases-dashboard-loading {
            z-index: 99999999;
        }
.modal.sigma-modal--case-create-modern-teeth {
            z-index: 99999999;
        }
.modal.sigma-modal--case-create-teeth {
            z-index: 99999999;
        }
.modal.sigma-modal--case-create-files {
            z-index: 99999999;
        }
.modal.sigma-modal--dashboard-active-milling {
            z-index: 99999999;
        }
.modal.sigma-modal--dashboard-active-case-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--dashboard-waiting-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--case-edit-teeth {
            z-index: 99999999;
        }
.modal.sigma-modal--case-edit-teeth-secondary {
            z-index: 99999999;
        }
.modal.sigma-modal--case-edit-files {
            z-index: 99999999;
        }
.modal.sigma-modal--cases-index-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--cases-rejected-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--clients-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--clients-add {
            z-index: 99999999;
        }
.modal.sigma-modal--clients-delete {
            z-index: 99999999;
        }
.modal.sigma-modal--active-cases-preview {
            z-index: 99999999;
        }
.modal.sigma-modal--adminhth-intel-shortcut {
            z-index: 99999999;
        }
.modal.sigma-modal--adminhth-intel-settings {
            z-index: 99999999;
        }
.modal.sigma-modal--adminhth-intel2-shortcut {
            z-index: 99999999;
        }
.modal.sigma-modal--adminhth-intel2-settings {
            z-index: 99999999;
        }
.modal.sigma-modal--adminhth-mobile-access {
            z-index: 99999999;
        }
.modal.sigma-modal--waiting-3d-printing {
            z-index: 99999999;
        }
.modal.sigma-modal--waiting-delivery {
            z-index: 99999999;
        }
.modal.sigma-modal--waiting-generic {
            z-index: 99999999;
        }
.modal.sigma-modal--delivery-schedule-edit {
            z-index: 99999999;
        }
.modal.sigma-modal--delivery-schedule-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--delivery-receive-payment {
            z-index: 99999999;
        }
.modal.sigma-modal--devices-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--failures-causes-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--failures-modify-teeth {
            z-index: 99999999;
        }
.modal.sigma-modal--failures-modify-teeth-secondary {
            z-index: 99999999;
        }
.modal.sigma-modal--failures-modify-files {
            z-index: 99999999;
        }
.modal.sigma-modal--failures-redo-teeth {
            z-index: 99999999;
        }
.modal.sigma-modal--failures-redo-teeth-secondary {
            z-index: 99999999;
        }
.modal.sigma-modal--failures-redo-files {
            z-index: 99999999;
        }
.modal.sigma-modal--failures-reject-teeth {
            z-index: 99999999;
        }
.modal.sigma-modal--failures-reject-files {
            z-index: 99999999;
        }
.modal.sigma-modal--failures-repeat-teeth {
            z-index: 99999999;
        }
.modal.sigma-modal--failures-repeat-teeth-secondary {
            z-index: 99999999;
        }
.modal.sigma-modal--failures-repeat-files {
            z-index: 99999999;
        }
.modal.sigma-modal--generic-emp-cases-primary {
            z-index: 99999999;
        }
.modal.sigma-modal--generic-emp-cases-secondary {
            z-index: 99999999;
        }
.modal.sigma-modal--generic-emp-cases-tertiary {
            z-index: 99999999;
        }
.modal.sigma-modal--generic-emp-cases-alt-primary {
            z-index: 99999999;
        }
.modal.sigma-modal--generic-emp-cases-alt-secondary {
            z-index: 99999999;
        }
.modal.sigma-modal--generic-emp-cases-alt-tertiary {
            z-index: 99999999;
        }
.modal.sigma-modal--generic-payments-alt {
            z-index: 99999999;
        }
.modal.sigma-modal--generic-payments {
            z-index: 99999999;
        }
.modal.sigma-modal--implants-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--job-types-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--labs-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--material-create-add-type {
            z-index: 99999999;
        }
.modal.sigma-modal--material-create-add-implant {
            z-index: 99999999;
        }
.modal.sigma-modal--material-create-edit-implant {
            z-index: 99999999;
        }
.modal.sigma-modal--material-edit-types {
            z-index: 99999999;
        }
.modal.sigma-modal--material-edit-add-type {
            z-index: 99999999;
        }
.modal.sigma-modal--material-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--media-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--rtl-search {
            z-index: 99999999;
        }
.modal.sigma-modal--report-employees-filter {
            z-index: 99999999;
        }
.modal.sigma-modal--report-devices-filter {
            z-index: 99999999;
        }
.modal.sigma-modal--tags-actions {
            z-index: 99999999;
        }
.modal.sigma-modal--invoice-check {
            z-index: 99999999;
        }
.modal.sigma-modal--invoice-check-new {
            z-index: 99999999;
        }
.modal.sigma-modal--users-actions {
            z-index: 99999999;
        }

        /* Generic dialog popup styling (used by payments, deliveries, etc.) */
        
.sigma-modal--abutments-delivery-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--abutments-delivery-receive .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--abutments-index-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--accountant-delivery-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--accountant-delivery-actions-alt .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--admin-intel-shortcut .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--admin-intel-settings .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--admin-intel2-shortcut .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--admin-intel2-settings .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--admin-mobile-access .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--cases-dashboard-case-completion .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--cases-dashboard-case-completion-alt .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--cases-dashboard-loading .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--case-create-modern-teeth .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--case-create-teeth .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--case-create-files .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--dashboard-active-milling .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--dashboard-active-case-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--dashboard-waiting-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--case-edit-teeth .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--case-edit-teeth-secondary .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--case-edit-files .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--cases-index-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--cases-rejected-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--clients-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--clients-add .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--clients-delete .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--active-cases-preview .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--adminhth-intel-shortcut .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--adminhth-intel-settings .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--adminhth-intel2-shortcut .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--adminhth-intel2-settings .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--adminhth-mobile-access .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--waiting-3d-printing .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--waiting-delivery .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--waiting-generic .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--delivery-schedule-edit .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--delivery-schedule-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--delivery-receive-payment .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--devices-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--failures-causes-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--failures-modify-teeth .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--failures-modify-teeth-secondary .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--failures-modify-files .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--failures-redo-teeth .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--failures-redo-teeth-secondary .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--failures-redo-files .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--failures-reject-teeth .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--failures-reject-files .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--failures-repeat-teeth .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--failures-repeat-teeth-secondary .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--failures-repeat-files .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--generic-emp-cases-primary .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--generic-emp-cases-secondary .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--generic-emp-cases-tertiary .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--generic-emp-cases-alt-primary .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--generic-emp-cases-alt-secondary .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--generic-emp-cases-alt-tertiary .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--generic-payments-alt .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--generic-payments .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--implants-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--job-types-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--labs-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--material-create-add-type .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--material-create-add-implant .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--material-create-edit-implant .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--material-edit-types .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--material-edit-add-type .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--material-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--media-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--rtl-search .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--report-employees-filter .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--report-devices-filter .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--tags-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--invoice-check .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--invoice-check-new .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
.sigma-modal--users-actions .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
        
.sigma-modal--abutments-delivery-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--abutments-delivery-receive .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--abutments-index-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--accountant-delivery-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--accountant-delivery-actions-alt .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--admin-intel-shortcut .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--admin-intel-settings .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--admin-intel2-shortcut .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--admin-intel2-settings .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--admin-mobile-access .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--cases-dashboard-case-completion .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--cases-dashboard-case-completion-alt .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--cases-dashboard-loading .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--case-create-modern-teeth .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--case-create-teeth .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--case-create-files .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--dashboard-active-milling .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--dashboard-active-case-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--dashboard-waiting-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--case-edit-teeth .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--case-edit-teeth-secondary .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--case-edit-files .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--cases-index-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--cases-rejected-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--clients-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--clients-add .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--clients-delete .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--active-cases-preview .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--adminhth-intel-shortcut .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--adminhth-intel-settings .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--adminhth-intel2-shortcut .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--adminhth-intel2-settings .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--adminhth-mobile-access .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--waiting-3d-printing .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--waiting-delivery .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--waiting-generic .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--delivery-schedule-edit .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--delivery-schedule-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--delivery-receive-payment .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--devices-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--failures-causes-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--failures-modify-teeth .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--failures-modify-teeth-secondary .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--failures-modify-files .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--failures-redo-teeth .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--failures-redo-teeth-secondary .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--failures-redo-files .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--failures-reject-teeth .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--failures-reject-files .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--failures-repeat-teeth .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--failures-repeat-teeth-secondary .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--failures-repeat-files .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--generic-emp-cases-primary .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--generic-emp-cases-secondary .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--generic-emp-cases-tertiary .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--generic-emp-cases-alt-primary .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--generic-emp-cases-alt-secondary .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--generic-emp-cases-alt-tertiary .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--generic-payments-alt .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--generic-payments .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--implants-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--job-types-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--labs-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--material-create-add-type .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--material-create-add-implant .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--material-create-edit-implant .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--material-edit-types .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--material-edit-add-type .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--material-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--media-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--rtl-search .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--report-employees-filter .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--report-devices-filter .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--tags-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--invoice-check .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--invoice-check-new .dialog-mfp .mfp-content {
            text-align: center;
        }
.sigma-modal--users-actions .dialog-mfp .mfp-content {
            text-align: center;
        }
        
.sigma-modal--abutments-delivery-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--abutments-delivery-receive .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--abutments-index-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--accountant-delivery-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--accountant-delivery-actions-alt .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--admin-intel-shortcut .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--admin-intel-settings .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--admin-intel2-shortcut .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--admin-intel2-settings .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--admin-mobile-access .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--cases-dashboard-case-completion .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--cases-dashboard-case-completion-alt .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--cases-dashboard-loading .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--case-create-modern-teeth .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--case-create-teeth .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--case-create-files .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--dashboard-active-milling .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--dashboard-active-case-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--dashboard-waiting-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--case-edit-teeth .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--case-edit-teeth-secondary .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--case-edit-files .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--cases-index-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--cases-rejected-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--clients-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--clients-add .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--clients-delete .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--active-cases-preview .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--adminhth-intel-shortcut .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--adminhth-intel-settings .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--adminhth-intel2-shortcut .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--adminhth-intel2-settings .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--adminhth-mobile-access .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--waiting-3d-printing .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--waiting-delivery .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--waiting-generic .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--delivery-schedule-edit .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--delivery-schedule-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--delivery-receive-payment .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--devices-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--failures-causes-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--failures-modify-teeth .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--failures-modify-teeth-secondary .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--failures-modify-files .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--failures-redo-teeth .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--failures-redo-teeth-secondary .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--failures-redo-files .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--failures-reject-teeth .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--failures-reject-files .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--failures-repeat-teeth .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--failures-repeat-teeth-secondary .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--failures-repeat-files .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--generic-emp-cases-primary .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--generic-emp-cases-secondary .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--generic-emp-cases-tertiary .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--generic-emp-cases-alt-primary .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--generic-emp-cases-alt-secondary .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--generic-emp-cases-alt-tertiary .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--generic-payments-alt .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--generic-payments .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--implants-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--job-types-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--labs-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--material-create-add-type .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--material-create-add-implant .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--material-create-edit-implant .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--material-edit-types .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--material-edit-add-type .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--material-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--media-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--rtl-search .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--report-employees-filter .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--report-devices-filter .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--tags-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--invoice-check .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--invoice-check-new .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
.sigma-modal--users-actions .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
        
.sigma-modal--abutments-delivery-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--abutments-delivery-receive .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--abutments-index-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--accountant-delivery-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--accountant-delivery-actions-alt .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--admin-intel-shortcut .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--admin-intel-settings .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--admin-intel2-shortcut .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--admin-intel2-settings .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--admin-mobile-access .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--cases-dashboard-case-completion .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--cases-dashboard-case-completion-alt .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--cases-dashboard-loading .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--case-create-modern-teeth .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--case-create-teeth .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--case-create-files .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--dashboard-active-milling .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--dashboard-active-case-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--dashboard-waiting-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--case-edit-teeth .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--case-edit-teeth-secondary .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--case-edit-files .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--cases-index-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--cases-rejected-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--clients-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--clients-add .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--clients-delete .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--active-cases-preview .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--adminhth-intel-shortcut .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--adminhth-intel-settings .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--adminhth-intel2-shortcut .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--adminhth-intel2-settings .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--adminhth-mobile-access .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--waiting-3d-printing .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--waiting-delivery .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--waiting-generic .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--delivery-schedule-edit .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--delivery-schedule-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--delivery-receive-payment .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--devices-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--failures-causes-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--failures-modify-teeth .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--failures-modify-teeth-secondary .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--failures-modify-files .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--failures-redo-teeth .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--failures-redo-teeth-secondary .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--failures-redo-files .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--failures-reject-teeth .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--failures-reject-files .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--failures-repeat-teeth .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--failures-repeat-teeth-secondary .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--failures-repeat-files .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--generic-emp-cases-primary .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--generic-emp-cases-secondary .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--generic-emp-cases-tertiary .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--generic-emp-cases-alt-primary .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--generic-emp-cases-alt-secondary .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--generic-emp-cases-alt-tertiary .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--generic-payments-alt .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--generic-payments .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--implants-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--job-types-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--labs-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--material-create-add-type .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--material-create-add-implant .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--material-create-edit-implant .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--material-edit-types .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--material-edit-add-type .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--material-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--media-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--rtl-search .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--report-employees-filter .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--report-devices-filter .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--tags-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--invoice-check .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--invoice-check-new .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
.sigma-modal--users-actions .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
        
.sigma-modal--abutments-delivery-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--abutments-delivery-receive .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--abutments-index-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--accountant-delivery-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--accountant-delivery-actions-alt .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--admin-intel-shortcut .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--admin-intel-settings .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--admin-intel2-shortcut .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--admin-intel2-settings .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--admin-mobile-access .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--cases-dashboard-case-completion .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--cases-dashboard-case-completion-alt .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--cases-dashboard-loading .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--case-create-modern-teeth .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--case-create-teeth .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--case-create-files .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--dashboard-active-milling .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--dashboard-active-case-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--dashboard-waiting-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--case-edit-teeth .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--case-edit-teeth-secondary .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--case-edit-files .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--cases-index-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--cases-rejected-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--clients-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--clients-add .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--clients-delete .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--active-cases-preview .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--adminhth-intel-shortcut .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--adminhth-intel-settings .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--adminhth-intel2-shortcut .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--adminhth-intel2-settings .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--adminhth-mobile-access .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--waiting-3d-printing .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--waiting-delivery .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--waiting-generic .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--delivery-schedule-edit .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--delivery-schedule-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--delivery-receive-payment .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--devices-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--failures-causes-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--failures-modify-teeth .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--failures-modify-teeth-secondary .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--failures-modify-files .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--failures-redo-teeth .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--failures-redo-teeth-secondary .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--failures-redo-files .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--failures-reject-teeth .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--failures-reject-files .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--failures-repeat-teeth .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--failures-repeat-teeth-secondary .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--failures-repeat-files .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--generic-emp-cases-primary .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--generic-emp-cases-secondary .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--generic-emp-cases-tertiary .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--generic-emp-cases-alt-primary .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--generic-emp-cases-alt-secondary .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--generic-emp-cases-alt-tertiary .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--generic-payments-alt .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--generic-payments .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--implants-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--job-types-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--labs-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--material-create-add-type .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--material-create-add-implant .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--material-create-edit-implant .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--material-edit-types .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--material-edit-add-type .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--material-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--media-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--rtl-search .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--report-employees-filter .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--report-devices-filter .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--tags-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--invoice-check .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--invoice-check-new .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
.sigma-modal--users-actions .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
        
.sigma-modal--abutments-delivery-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--abutments-delivery-receive .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--abutments-index-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--accountant-delivery-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--accountant-delivery-actions-alt .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--admin-intel-shortcut .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--admin-intel-settings .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--admin-intel2-shortcut .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--admin-intel2-settings .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--admin-mobile-access .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--cases-dashboard-case-completion .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--cases-dashboard-case-completion-alt .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--cases-dashboard-loading .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--case-create-modern-teeth .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--case-create-teeth .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--case-create-files .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--dashboard-active-milling .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--dashboard-active-case-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--dashboard-waiting-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--case-edit-teeth .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--case-edit-teeth-secondary .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--case-edit-files .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--cases-index-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--cases-rejected-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--clients-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--clients-add .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--clients-delete .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--active-cases-preview .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--adminhth-intel-shortcut .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--adminhth-intel-settings .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--adminhth-intel2-shortcut .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--adminhth-intel2-settings .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--adminhth-mobile-access .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--waiting-3d-printing .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--waiting-delivery .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--waiting-generic .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--delivery-schedule-edit .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--delivery-schedule-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--delivery-receive-payment .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--devices-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--failures-causes-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--failures-modify-teeth .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--failures-modify-teeth-secondary .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--failures-modify-files .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--failures-redo-teeth .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--failures-redo-teeth-secondary .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--failures-redo-files .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--failures-reject-teeth .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--failures-reject-files .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--failures-repeat-teeth .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--failures-repeat-teeth-secondary .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--failures-repeat-files .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--generic-emp-cases-primary .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--generic-emp-cases-secondary .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--generic-emp-cases-tertiary .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--generic-emp-cases-alt-primary .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--generic-emp-cases-alt-secondary .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--generic-emp-cases-alt-tertiary .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--generic-payments-alt .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--generic-payments .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--implants-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--job-types-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--labs-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--material-create-add-type .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--material-create-add-implant .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--material-create-edit-implant .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--material-edit-types .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--material-edit-add-type .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--material-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--media-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--rtl-search .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--report-employees-filter .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--report-devices-filter .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--tags-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--invoice-check .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--invoice-check-new .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
.sigma-modal--users-actions .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
        
.sigma-modal--abutments-delivery-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--abutments-delivery-receive .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--abutments-index-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--accountant-delivery-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--accountant-delivery-actions-alt .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--admin-intel-shortcut .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--admin-intel-settings .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--admin-intel2-shortcut .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--admin-intel2-settings .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--admin-mobile-access .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--cases-dashboard-case-completion .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--cases-dashboard-case-completion-alt .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--cases-dashboard-loading .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--case-create-modern-teeth .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--case-create-teeth .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--case-create-files .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--dashboard-active-milling .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--dashboard-active-case-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--dashboard-waiting-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--case-edit-teeth .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--case-edit-teeth-secondary .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--case-edit-files .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--cases-index-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--cases-rejected-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--clients-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--clients-add .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--clients-delete .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--active-cases-preview .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--adminhth-intel-shortcut .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--adminhth-intel-settings .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--adminhth-intel2-shortcut .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--adminhth-intel2-settings .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--adminhth-mobile-access .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--waiting-3d-printing .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--waiting-delivery .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--waiting-generic .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--delivery-schedule-edit .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--delivery-schedule-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--delivery-receive-payment .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--devices-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--failures-causes-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--failures-modify-teeth .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--failures-modify-teeth-secondary .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--failures-modify-files .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--failures-redo-teeth .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--failures-redo-teeth-secondary .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--failures-redo-files .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--failures-reject-teeth .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--failures-reject-files .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--failures-repeat-teeth .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--failures-repeat-teeth-secondary .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--failures-repeat-files .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--generic-emp-cases-primary .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--generic-emp-cases-secondary .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--generic-emp-cases-tertiary .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--generic-emp-cases-alt-primary .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--generic-emp-cases-alt-secondary .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--generic-emp-cases-alt-tertiary .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--generic-payments-alt .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--generic-payments .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--implants-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--job-types-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--labs-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--material-create-add-type .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--material-create-add-implant .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--material-create-edit-implant .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--material-edit-types .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--material-edit-add-type .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--material-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--media-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--rtl-search .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--report-employees-filter .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--report-devices-filter .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--tags-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--invoice-check .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--invoice-check-new .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
.sigma-modal--users-actions .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
        
.sigma-modal--abutments-delivery-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--abutments-delivery-receive .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--abutments-index-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--accountant-delivery-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--accountant-delivery-actions-alt .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--admin-intel-shortcut .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--admin-intel-settings .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--admin-intel2-shortcut .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--admin-intel2-settings .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--admin-mobile-access .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--cases-dashboard-case-completion .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--cases-dashboard-case-completion-alt .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--cases-dashboard-loading .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--case-create-modern-teeth .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--case-create-teeth .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--case-create-files .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--dashboard-active-milling .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--dashboard-active-case-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--dashboard-waiting-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--case-edit-teeth .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--case-edit-teeth-secondary .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--case-edit-files .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--cases-index-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--cases-rejected-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--clients-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--clients-add .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--clients-delete .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--active-cases-preview .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--adminhth-intel-shortcut .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--adminhth-intel-settings .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--adminhth-intel2-shortcut .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--adminhth-intel2-settings .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--adminhth-mobile-access .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--waiting-3d-printing .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--waiting-delivery .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--waiting-generic .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--delivery-schedule-edit .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--delivery-schedule-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--delivery-receive-payment .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--devices-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-causes-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-modify-teeth .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-modify-teeth-secondary .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-modify-files .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-redo-teeth .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-redo-teeth-secondary .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-redo-files .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-reject-teeth .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-reject-files .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-repeat-teeth .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-repeat-teeth-secondary .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-repeat-files .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--generic-emp-cases-primary .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--generic-emp-cases-secondary .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--generic-emp-cases-tertiary .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--generic-emp-cases-alt-primary .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--generic-emp-cases-alt-secondary .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--generic-emp-cases-alt-tertiary .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--generic-payments-alt .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--generic-payments .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--implants-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--job-types-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--labs-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--material-create-add-type .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--material-create-add-implant .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--material-create-edit-implant .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--material-edit-types .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--material-edit-add-type .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--material-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--media-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--rtl-search .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--report-employees-filter .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--report-devices-filter .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--tags-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--invoice-check .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--invoice-check-new .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--users-actions .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
        
.sigma-modal--abutments-delivery-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--abutments-delivery-receive .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--abutments-index-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--accountant-delivery-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--accountant-delivery-actions-alt .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--admin-intel-shortcut .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--admin-intel-settings .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--admin-intel2-shortcut .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--admin-intel2-settings .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--admin-mobile-access .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--cases-dashboard-case-completion .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--cases-dashboard-case-completion-alt .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--cases-dashboard-loading .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--case-create-modern-teeth .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--case-create-teeth .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--case-create-files .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--dashboard-active-milling .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--dashboard-active-case-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--dashboard-waiting-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--case-edit-teeth .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--case-edit-teeth-secondary .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--case-edit-files .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--cases-index-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--cases-rejected-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--clients-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--clients-add .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--clients-delete .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--active-cases-preview .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--adminhth-intel-shortcut .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--adminhth-intel-settings .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--adminhth-intel2-shortcut .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--adminhth-intel2-settings .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--adminhth-mobile-access .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--waiting-3d-printing .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--waiting-delivery .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--waiting-generic .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--delivery-schedule-edit .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--delivery-schedule-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--delivery-receive-payment .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--devices-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--failures-causes-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--failures-modify-teeth .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--failures-modify-teeth-secondary .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--failures-modify-files .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--failures-redo-teeth .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--failures-redo-teeth-secondary .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--failures-redo-files .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--failures-reject-teeth .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--failures-reject-files .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--failures-repeat-teeth .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--failures-repeat-teeth-secondary .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--failures-repeat-files .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--generic-emp-cases-primary .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--generic-emp-cases-secondary .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--generic-emp-cases-tertiary .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--generic-emp-cases-alt-primary .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--generic-emp-cases-alt-secondary .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--generic-emp-cases-alt-tertiary .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--generic-payments-alt .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--generic-payments .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--implants-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--job-types-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--labs-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--material-create-add-type .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--material-create-add-implant .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--material-create-edit-implant .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--material-edit-types .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--material-edit-add-type .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--material-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--media-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--rtl-search .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--report-employees-filter .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--report-devices-filter .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--tags-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--invoice-check .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--invoice-check-new .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
.sigma-modal--users-actions .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
.sigma-modal--abutments-delivery-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--abutments-delivery-receive .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--abutments-index-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--accountant-delivery-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--accountant-delivery-actions-alt .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--admin-intel-shortcut .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--admin-intel-settings .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--admin-intel2-shortcut .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--admin-intel2-settings .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--admin-mobile-access .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--cases-dashboard-case-completion .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--cases-dashboard-case-completion-alt .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--cases-dashboard-loading .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--case-create-modern-teeth .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--case-create-teeth .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--case-create-files .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--dashboard-active-milling .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--dashboard-active-case-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--dashboard-waiting-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--case-edit-teeth .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--case-edit-teeth-secondary .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--case-edit-files .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--cases-index-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--cases-rejected-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--clients-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--clients-add .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--clients-delete .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--active-cases-preview .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--adminhth-intel-shortcut .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--adminhth-intel-settings .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--adminhth-intel2-shortcut .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--adminhth-intel2-settings .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--adminhth-mobile-access .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--waiting-3d-printing .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--waiting-delivery .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--waiting-generic .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--delivery-schedule-edit .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--delivery-schedule-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--delivery-receive-payment .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--devices-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--failures-causes-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--failures-modify-teeth .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--failures-modify-teeth-secondary .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--failures-modify-files .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--failures-redo-teeth .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--failures-redo-teeth-secondary .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--failures-redo-files .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--failures-reject-teeth .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--failures-reject-files .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--failures-repeat-teeth .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--failures-repeat-teeth-secondary .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--failures-repeat-files .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--generic-emp-cases-primary .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--generic-emp-cases-secondary .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--generic-emp-cases-tertiary .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--generic-emp-cases-alt-primary .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--generic-emp-cases-alt-secondary .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--generic-emp-cases-alt-tertiary .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--generic-payments-alt .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--generic-payments .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--implants-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--job-types-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--labs-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--material-create-add-type .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--material-create-add-implant .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--material-create-edit-implant .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--material-edit-types .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--material-edit-add-type .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--material-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--media-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--rtl-search .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--report-employees-filter .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--report-devices-filter .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--tags-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--invoice-check .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--invoice-check-new .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
.sigma-modal--users-actions .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
        
.sigma-modal--abutments-delivery-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--abutments-delivery-receive .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--abutments-index-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--accountant-delivery-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--accountant-delivery-actions-alt .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--admin-intel-shortcut .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--admin-intel-settings .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--admin-intel2-shortcut .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--admin-intel2-settings .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--admin-mobile-access .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--cases-dashboard-case-completion .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--cases-dashboard-case-completion-alt .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--cases-dashboard-loading .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--case-create-modern-teeth .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--case-create-teeth .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--case-create-files .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--dashboard-active-milling .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--dashboard-active-case-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--dashboard-waiting-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--case-edit-teeth .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--case-edit-teeth-secondary .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--case-edit-files .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--cases-index-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--cases-rejected-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--clients-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--clients-add .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--clients-delete .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--active-cases-preview .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--adminhth-intel-shortcut .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--adminhth-intel-settings .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--adminhth-intel2-shortcut .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--adminhth-intel2-settings .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--adminhth-mobile-access .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--waiting-3d-printing .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--waiting-delivery .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--waiting-generic .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--delivery-schedule-edit .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--delivery-schedule-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--delivery-receive-payment .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--devices-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--failures-causes-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--failures-modify-teeth .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--failures-modify-teeth-secondary .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--failures-modify-files .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--failures-redo-teeth .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--failures-redo-teeth-secondary .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--failures-redo-files .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--failures-reject-teeth .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--failures-reject-files .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--failures-repeat-teeth .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--failures-repeat-teeth-secondary .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--failures-repeat-files .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--generic-emp-cases-primary .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--generic-emp-cases-secondary .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--generic-emp-cases-tertiary .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--generic-emp-cases-alt-primary .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--generic-emp-cases-alt-secondary .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--generic-emp-cases-alt-tertiary .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--generic-payments-alt .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--generic-payments .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--implants-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--job-types-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--labs-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--material-create-add-type .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--material-create-add-implant .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--material-create-edit-implant .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--material-edit-types .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--material-edit-add-type .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--material-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--media-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--rtl-search .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--report-employees-filter .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--report-devices-filter .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--tags-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--invoice-check .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--invoice-check-new .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
.sigma-modal--users-actions .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
        /* Better info layout */
        .payment-info-grid {
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        .payment-info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            padding: 5px 0;
            border-bottom: 1px solid #f1f3f5;
        }
        .payment-info-row:last-child {
            border-bottom: 0;
        }
        .payment-info-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #6c757d;
            font-weight: 700;
            text-align: left;
        }
        .payment-info-value {
            color: #1f2937;
            font-weight: 600;
            text-align: right;
            word-break: break-word;
        }
        .payment-info-value-input {
            display: flex;
            justify-content: flex-end;
            width: 100%;
        }
        .payment-info-value-input .form-control {
            width: auto;
            max-width: 100%;
            /*min-width: 220px;*/
            text-align: right;
            font-weight: 600;
            color: #1f2937;
            background-color: #f8f9fa;
            border-color: #e5e7eb;
            border-radius: 12px;
            box-shadow: none;

        }

        /* iOS scroll containment */
        .mfp-wrap, .mfp-bg {
            overscroll-behavior: contain;
            touch-action: none;
        }
        body.mfp-open {
            overflow: hidden;

            width: 100%;
        }
        @supports (-webkit-touch-callout: none){
            .mfp-wrap, .mfp-bg {
                position: fixed !important;
                width: 100%;
                height: 100%;
            }
        }


        .card {
            padding: 5px;
        }

        .row {
            padding: 5px;
        }

        .navbar .navbar-brand {
            /*font-family: 'Black Ops One', cursive !important;*/
            /*font-size: 2rem !important;*/
            margin-top: 0;
        }

        .pageTitleContainer {
            /*text-align: center;*/
            /*background:none;*/

        }

        .card-title {
            font-weight: bold !important;
        }

        /* Cases & Units Btns colors : */
        .btn-primary.bar.active {}

        @media screen and (max-width: 768px){

            .main-panel, .content {
                padding-left: 0px !important;
                padding-right: 0px !important;
            }

            .main-panel>.content {
                margin: 0px;

            }

        }

        @media screen and (max-width: 991px){
            .main-panel>.content {
                margin-top: 60px;
                height: fit-content;
            }
        }

        .barsBtns, .performanceBtns {
            background-color: #2b7b7d !important;
            border-color: #2b7b7d !important;
        }

        .barsBtns.active, .performanceBtns.active {
            background-color: #1e5253 !important;
            border-color: #1e5253 !important;
        }

        .barsBtns:hover, .performanceBtns:hover {
            background-color: #4daeb0 !important;
            border-color: #4daeb0 !important;
        }

        .barsBtns:focus, .performanceBtns:focus {
            /*box-shadow: 0 0 0 .2, shadow: rgba(89 141 142);*/
        }

        /* Device image container styles */
        .device-container {
            height: calc(100vh - 400px);
            /* Adjust height to match left menu */
            overflow-y: auto;
            padding: 15px;
        }

        .device-image {
            max-width: 250px;
            /* Limit image width */
            max-height: 200px;
            /* Limit image height */
            object-fit: contain;
            margin: 10px auto;
            display: block;
        }

        .device-card {
            margin-bottom: 15px;
            text-align: center;
        }
        /* Prevent horizontal scroll on small screens for summary tables */
        .sunriseTable {
            font-family: 'Cairo', sans-serif;
            table-layout: fixed;
            width: 100%;
        }
        .sunriseTable th, .sunriseTable td {
            white-space: normal !important;
            word-break: break-word;
        }
        .summary-table-responsive {
            overflow-x: hidden;
        }
        .summary-table-responsive table {
            margin-bottom: 0;
        }
        
.sigma-modal--abutments-delivery-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--abutments-delivery-receive .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--abutments-index-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--accountant-delivery-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--accountant-delivery-actions-alt .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--admin-intel-shortcut .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--admin-intel-settings .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--admin-intel2-shortcut .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--admin-intel2-settings .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--admin-mobile-access .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--cases-dashboard-case-completion .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--cases-dashboard-case-completion-alt .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--cases-dashboard-loading .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--case-create-modern-teeth .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--case-create-teeth .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--case-create-files .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--dashboard-active-milling .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--dashboard-active-case-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--dashboard-waiting-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--case-edit-teeth .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--case-edit-teeth-secondary .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--case-edit-files .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--cases-index-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--cases-rejected-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--clients-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--clients-add .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--clients-delete .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--active-cases-preview .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--adminhth-intel-shortcut .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--adminhth-intel-settings .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--adminhth-intel2-shortcut .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--adminhth-intel2-settings .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--adminhth-mobile-access .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--waiting-3d-printing .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--waiting-delivery .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--waiting-generic .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--delivery-schedule-edit .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--delivery-schedule-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--delivery-receive-payment .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--devices-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--failures-causes-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--failures-modify-teeth .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--failures-modify-teeth-secondary .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--failures-modify-files .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--failures-redo-teeth .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--failures-redo-teeth-secondary .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--failures-redo-files .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--failures-reject-teeth .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--failures-reject-files .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--failures-repeat-teeth .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--failures-repeat-teeth-secondary .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--failures-repeat-files .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--generic-emp-cases-primary .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--generic-emp-cases-secondary .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--generic-emp-cases-tertiary .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--generic-emp-cases-alt-primary .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--generic-emp-cases-alt-secondary .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--generic-emp-cases-alt-tertiary .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--generic-payments-alt .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--generic-payments .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--implants-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--job-types-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--labs-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--material-create-add-type .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--material-create-add-implant .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--material-create-edit-implant .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--material-edit-types .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--material-edit-add-type .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--material-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--media-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--rtl-search .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--report-employees-filter .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--report-devices-filter .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--tags-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--invoice-check .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--invoice-check-new .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
.sigma-modal--users-actions .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
        
.sigma-modal--abutments-delivery-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--abutments-delivery-receive .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--abutments-index-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--accountant-delivery-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--accountant-delivery-actions-alt .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--admin-intel-shortcut .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--admin-intel-settings .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--admin-intel2-shortcut .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--admin-intel2-settings .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--admin-mobile-access .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--cases-dashboard-case-completion .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--cases-dashboard-case-completion-alt .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--cases-dashboard-loading .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--case-create-modern-teeth .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--case-create-teeth .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--case-create-files .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--dashboard-active-milling .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--dashboard-active-case-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--dashboard-waiting-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--case-edit-teeth .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--case-edit-teeth-secondary .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--case-edit-files .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--cases-index-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--cases-rejected-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--clients-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--clients-add .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--clients-delete .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--active-cases-preview .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--adminhth-intel-shortcut .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--adminhth-intel-settings .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--adminhth-intel2-shortcut .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--adminhth-intel2-settings .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--adminhth-mobile-access .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--waiting-3d-printing .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--waiting-delivery .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--waiting-generic .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--delivery-schedule-edit .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--delivery-schedule-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--delivery-receive-payment .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--devices-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-causes-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-modify-teeth .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-modify-teeth-secondary .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-modify-files .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-redo-teeth .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-redo-teeth-secondary .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-redo-files .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-reject-teeth .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-reject-files .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-repeat-teeth .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-repeat-teeth-secondary .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--failures-repeat-files .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--generic-emp-cases-primary .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--generic-emp-cases-secondary .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--generic-emp-cases-tertiary .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--generic-emp-cases-alt-primary .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--generic-emp-cases-alt-secondary .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--generic-emp-cases-alt-tertiary .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--generic-payments-alt .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--generic-payments .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--implants-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--job-types-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--labs-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--material-create-add-type .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--material-create-add-implant .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--material-create-edit-implant .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--material-edit-types .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--material-edit-add-type .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--material-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--media-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--rtl-search .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--report-employees-filter .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--report-devices-filter .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--tags-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--invoice-check .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--invoice-check-new .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
.sigma-modal--users-actions .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
        .delivery-popup hr {
            margin: 12px 0;
            border-color: #e5e7eb;
        }
        /* Keep datetime picker above modal/backdrop */
        .bootstrap-datetimepicker-widget, .xdsoft_datetimepicker {
            z-index: 100000000 !important;
        }

        /* Bootstrap modal styling for payments/deliveries */
        [id^="payment-modal-"],
        [id^="delivery-modal-"] {
            z-index: 99999 !important;
        }

        [id^="payment-modal-"] .modal-dialog,
        [id^="delivery-modal-"] .modal-dialog {
            max-width: 500px;
            z-index: 100000 !important;
        }

        [id^="payment-modal-"] .modal-content,
        [id^="delivery-modal-"] .modal-content {
            z-index: 100001 !important;
            position: relative;
        }

        /* Dark blurred backdrop for modals */
        [id^="payment-modal-"].modal,
        [id^="delivery-modal-"].modal {
            background: rgba(0, 0, 0, 0.6);
            -webkit-backdrop-filter: blur(5px);
            backdrop-filter: blur(5px);
        }

        [id^="payment-modal-"] + .modal-backdrop,
        [id^="delivery-modal-"] + .modal-backdrop,
        .modal-backdrop.show {
            background-color: rgba(0, 0, 0, 0.7);
            opacity: 1;
            z-index: 99998 !important;
        }

        /* iOS Chrome fix: reset transform stacking context on interfering elements */
        body.modal-open .card,
        body.modal-open .btn-group,
        body.modal-open .performanceBtns.active,
        body.modal-open [class*="col-"] {
            -webkit-transform: none !important;
            transform: none !important;
        }

        @media (max-width: 576px) {
            [id^="payment-modal-"] .modal-dialog,
            [id^="delivery-modal-"] .modal-dialog {
                margin: 10px;
                max-width: calc(100% - 20px);
            }

            [id^="payment-modal-"] .modal-content,
            [id^="delivery-modal-"] .modal-content {
                max-height: 85vh;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
    {{-- <div class="row"  style="background-color: transparent"> --}}
    {{-- <h2 class="subheader-title"> --}}
    {{-- <i class="fa-solid fa-chart-area"></i><b> Main </b><span >Dashboard</span> --}}
    {{-- <small> --}}
    {{-- </small> --}}
    {{-- </h2> --}}
    {{-- </div> --}}
    <div class="row" style="background-color: transparent">
        <div class="col-lg-6 noLeftPadding">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row" style="background-color: transparent">
                        <div class="col-sm-7 text-left">
                            <h4 class="card-title" style="">Completed in 7 Days</h4>


                        </div>
                        <div class="col-sm-5">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple bar active barsBtns"
                                    id="completedChartCases">
                                    <input type="radio" name="options" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Units</span>
                                    <span class="d-block d-sm-none">
                                        <i class="fa-solid fa-boxes-stacked"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple bar barsBtns" id="completedChartUnits">
                                    <input type="radio" class="d-none d-sm-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Cases</span>
                                    <span class="d-block d-sm-none">
                                        <i class="fa-solid fa-box"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="completedChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 ">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row" style="background-color: transparent;padding:0">
                        <div class="col-sm-12 text-left">
                            <h4 class="card-title" style="">Cases/Units Currently in-work</h4>

                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <div id="chartContainer" style="height: 100%; width: 100%;"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row noLeftPadding" style="background-color: transparent">
        <div class="col-12 noLeftPadding">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row" style="background-color: transparent">
                        <div class="col-sm-6 text-left">

                            <h4 class="card-title">Monthly Performance</h4>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active performanceBtns" id="0">
                                    <input type="radio" name="options" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Units</span>
                                    <span class="d-block d-sm-none">
                                        <i class="fa-solid fa-boxes-stacked"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple performanceBtns" id="1">
                                    <input type="radio" class="d-none d-sm-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Cases</span>
                                    <span class="d-block d-sm-none">
                                        <i class="fa-solid fa-box"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple performanceBtns" id="3">
                                    <input type="radio" class="d-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Sales</span>
                                    <span class="d-block d-sm-none">
                                        <i class="fa-solid fa-money-bill-trend-up"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple performanceBtns" id="2">
                                    <input type="radio" class="d-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Payments</span>
                                    <span class="d-block d-sm-none">
                                        <i class="fa-regular fa-money-bill-1"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartBig1"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" >
        <div class="col-lg-6 col-md-12 noLeftPadding">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Payments Collected Today</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive summary-table-responsive">
                        <table id="datatable" class="datatable hover compact stripe sunriseTable" style="width:100%">
                            <colgroup>
                                <col style="width:30%">
                                <col style="width:15%">
                                <col style="width:15%">
                                <col style="width:20%">
                                <col style="width:20%">
                            </colgroup>
                            <thead>
                                <tr>

                                    <th>
                                        Doctor
                                    </th>
                                    <th>
                                        Payment
                                    </th>
                                    <th class="text-center">
                                        Collector
                                    </th>
                                    <th class="text-center">
                                        Time Collected
                                    </th>
                                    <th>
                                        Received by
                                    </th>
                                </tr>
                            </thead>
            <tbody>
                                @foreach ($paymentsReceivedToday as $payment)
                                    <tr class="clickable"
                                        data-toggle="modal"
                                        data-target="#payment-modal-{{ $payment->id }}">

                                        <td>
                                            {{ $payment->client->name }}
                                        </td>
                                        <td>
                                            {{ $payment->amount }} JOD
                                        </td>
                                        <td class="text-center">
                                            {{ $payment->collectorUserRecord->name_initials }}
                                        </td>
                                        <td class="text-center">
                                            {{ date('g:i a', strtotime(substr(str_replace('T', ' ', $payment->recieved_on), 0, -3))) }}

                                        </td>
                                        <td>

                                            @if ($payment->receivedBy)
                                                <span style="color:green">{{ $payment->receivedBy->name_initials }}</span>
                                            @else
                                                <span style="color:red">NONE</span>
                                            @endif

                                        </td>
                                    </tr>

                                    {{-- Bootstrap Modal for Payment --}}
                                    <div class="modal fade" id="payment-modal-{{ $payment->id }}" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel{{ $payment->id }}" aria-hidden="true" style="z-index: 1009999 !important">
                                        <div class="modal-dialog modal-dialog-centered" role="document" style="z-index: 1009999 !important">
                                            <div class="modal-content" style="z-index: 1009999 !important">
                                                <div class="modal-header" style="z-index: 1009999 !important">
                                                    <h5 class="modal-title" id="paymentModalLabel{{ $payment->id }}">Receive Payment </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body" style="z-index: 1009999 !important">
                                                    <div class="payment-info-grid">
                                                        <div class="payment-info-row">
                                                            <div class="payment-info-label">Doctor</div>
                                                            <div class="payment-info-value">{{ $payment->client->name }}</div>
                                                        </div>
                                                        <div class="payment-info-row">
                                                            <div class="payment-info-label">Collected from doctor by</div>
                                                            <div class="payment-info-value">{{ $payment->collectorFullName() }}</div>
                                                        </div>
                                                        <div class="payment-info-row">
                                                            <div class="payment-info-label">Payment Amount</div>
                                                            <div class="payment-info-value">{{ $payment->amount }} JOD</div>
                                                        </div>
                                                        <div class="payment-info-row">
                                                            <div class="payment-info-label">Collected On</div>
                                                            <div class="payment-info-value">{{ $payment->created_at }}</div>
                                                        </div>
                                                        @if ($payment->isCollected())
                                                            <div class="payment-info-row">
                                                                <div class="payment-info-label">Received On</div>
                                                                <div class="payment-info-value">{{ $payment->recieved_on }}</div>
                                                            </div>
                                                            <div class="payment-info-row">
                                                                <div class="payment-info-label">Received by</div>
                                                                <div class="payment-info-value">{{ $payment->receiverFullName() }}</div>
                                                            </div>
                                                        @endif
                                                        <div class="payment-info-row">
                                                            <div class="payment-info-label">Payment Method</div>
                                                            <div class="payment-info-value">{{ $payment->notes }}</div>
                                                        </div>
                                                        @if ($payment->additional_notes)
                                                            <div class="payment-info-row">
                                                                <div class="payment-info-label">Notes</div>
                                                                <div class="payment-info-value">{{ $payment->additional_notes }}</div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted">PAYMENT ID : {{ $payment->id }}</small>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    @if (!$payment->isCollected())
                                                        <a href="{{ route('receive-payment', $payment->id) }}" class="btn btn-danger">Receive</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Deliveries Today</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive summary-table-responsive">
                        <table class="datatable compact hover stripe sunriseTable" id="datatable2">
                            <colgroup>
                                <col style="width:28%">
                                <col style="width:32%">
                                <col style="width:20%">
                                <col style="width:20%">
                            </colgroup>
                            <thead>
                                <tr>

                                    <th>
                                        Doctor
                                    </th>
                                    <th>
                                        Patient name
                                    </th>
                                    <th class="text-center">
                                        Delivery time
                                    </th>
                                    <th class="text-center">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($DeliveriesToday as $case)
                                    <tr class="clickable"
                                        data-toggle="modal"
                                        data-target="#delivery-modal-{{ $case->id }}">

                                        <td>
                                            {{ $case->client->name }}
                                        </td>
                                        <td>
                                            {{ $case->patient_name }}
                                        </td>
                                        <td class="text-center">
                                            {{ date('g:i a', strtotime(str_replace('T', ' ', $case->initial_delivery_date))) }}

                                        </td>
                                        <td>
                                            @php
                                                $status = $case->status();
                                                $active = true;
                                                if (str_contains($status, 'Waiting')) {
                                                    $active = false;
                                                }
                                                $stageLabel = trim(str_replace(['Waiting in', 'Waiting', 'Active in', 'Active'], '', $status));
                                                $deliveryJob = $case->jobs->where('stage', 8)->first();
                                                $assigned = $deliveryJob && $deliveryJob->assignedTo ? $deliveryJob->assignedTo->name_initials : null;
                                            @endphp

                                            @if ($active)
                                                <span style="width:auto; margin: auto; text-align: center"
                                                    class="badge badge-primary sigma-status-width">
                                                    {{ $assigned ? $assigned . ' / ' : '' }}{{ $stageLabel ?: $status }}
                                                </span>
                                            @else
                                                <span style="width:auto; margin: auto; text-align: center"
                                                    class="badge badge-danger sigma-status-width">
                                                    {{ $stageLabel ?: $case->status() }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Bootstrap Modals for Deliveries --}}
    @foreach ($DeliveriesToday as $case)
        @php
            $time = date('Y-m-d g:i a', strtotime($case->initial_delivery_date));
            $formId = 'delivery-form-' . $case->id;
        @endphp
        <div class="modal fade" id="delivery-modal-{{ $case->id }}" tabindex="-1" role="dialog" aria-labelledby="deliveryModalLabel{{ $case->id }}" aria-hidden="true" style="z-index: 1009999">
            <div class="modal-dialog modal-dialog-centered" role="document"  style="z-index: 1009999">
                <div class="modal-content"  style="z-index: 1009999">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deliveryModalLabel{{ $case->id }}">Update Delivery Date</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"  style="z-index: 1009999">
                        <form id="{{ $formId }}" action="{{ route('edit-delivery-date') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $case->id }}">
                            <div class="payment-info-grid">
                                <div class="payment-info-row">
                                    <div class="payment-info-label">Doctor</div>
                                    <div class="payment-info-value">{{ $case->client->name }}</div>
                                </div>
                                <div class="payment-info-row">
                                    <div class="payment-info-label">Patient Name</div>
                                    <div class="payment-info-value">{{ $case->patient_name }}</div>
                                </div>
                                <div class="payment-info-row">
                                    <div class="payment-info-label">Current Delivery Time</div>
                                    <div class="payment-info-value payment-info-value-input">
                                        <x-ios-dtp name="delivery_date" id="dashboard_delivery_date_{{ $case->id }}" :value="old('delivery_date', \Carbon\Carbon::parse($case->initial_delivery_date)->format('Y-m-d\TH:i:s') ?? '')" :required="true" />

{{--                                        <input class="form-control SDTP"--}}
{{--                                               id="dashboard_delivery_date_{{ $case->id }}"--}}
{{--                                               name="delivery_date"--}}
{{--                                               type="text"--}}
{{--                                               value="{{ \Carbon\Carbon::parse($case->initial_delivery_date)->format('Y-m-d\TH:i:s') }}"--}}
{{--                                               required=""--}}
{{--                                               readonly=""--}}
{{--                                        >--}}
                                    </div>
                                </div>
                            </div>
                        </form>
                        <small class="text-muted">CASE ID : {{ $case->id }}</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger" form="{{ $formId }}">UPDATE</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('js')
    <script src="{{ asset('assets') }}/js/jquery.magnific-popup.min.js"></script>
    <script src="{{ asset('assets') }}/js/canvasjs.min.js"></script>
    <script src="{{ asset('white') }}/js/plugins/chartjs.min.js"></script>

    <script>
        $(document).ready(function() {
            const hasChartJs = typeof Chart !== 'undefined';
            const hasCanvasJs = typeof CanvasJS !== 'undefined';

            if (hasCanvasJs && document.getElementById('chartContainer')) {
                initDoughnutChart();
            }

            if (hasChartJs && document.getElementById('completedChart')) {
                initComp7DaysChart();
            }

            if (hasChartJs && document.getElementById('chartBig1')) {
                initPerformanceChart();
            }

            $('.datatable').DataTable({
                "pageLength": 50,
                "searching": false,
                "lengthChange": false,
                "ordering": false,
                "paging": false,
                "autoWidth": false,
                "columnDefs": [
                    { "targets": -1, "className": "text-center" }
                ]
            });

            // iOS fix: Move modals to body to escape stacking context
            $('[id^="payment-modal-"], [id^="delivery-modal-"]').appendTo('body');
        });

        function initComp7DaysChart() {
            const completedChartElement = document.getElementById("completedChart");
            if (!completedChartElement || !completedChartElement.getContext) {
                return;
            }
            var completedChartData = {
                "Cases": ['{!! implode("','", $compCasesCount7Days) !!}'],
                "Units": ['{!! implode("','", $compUnitsCount7Days) !!}']
            };

            var barChartConfiguration = {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: '#f5f5f5',
                    titleFontColor: '#333',
                    bodyFontColor: '#666',
                    bodySpacing: 4,
                    xPadding: 12,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest"
                },
                responsive: true,
                scales: {
                    yAxes: [{
                        gridLines: {
                            drawBorder: false,
                            color: 'rgba(29,140,248,0.1)',
                            zeroLineColor: "transparent",
                        },
                        ticks: {
                            suggestedMin: 20,
                            suggestedMax: 0,
                            padding: 20,
                            fontColor: "#9e9e9e"
                        }
                    }],

                    xAxes: [{
                        gridLines: {
                            drawBorder: false,
                            color: 'rgba(29,140,248,0.1)',
                            zeroLineColor: "transparent"
                        },
                        ticks: {
                            padding: 20,
                            fontColor: "#9e9e9e"
                        }
                    }]
                }
            };

            var ctx = completedChartElement.getContext("2d");

            var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);

            gradientStroke.addColorStop(1, 'rgba(29,140,248,0.2)');
            gradientStroke.addColorStop(0.4, 'rgba(29,140,248,0.0)');
            gradientStroke.addColorStop(0, 'rgba(29,140,248,0)'); //blue colors

            var options1 = {
                type: 'bar',
                responsive: true,
                legend: {
                    display: false
                },
                data: {
                    labels: ['{!! implode("','", $last7DaysLabels) !!}'],
                    datasets: [{
                        label: "Completed Units",
                        fill: true,
                        backgroundColor: gradientStroke,
                        hoverBackgroundColor: gradientStroke,
                        borderColor: '#1f8ef1',
                        borderWidth: 2,
                        borderDash: [],
                        borderDashOffset: 0.0,
                        data: completedChartData['Units']
                    }]
                },
                options: barChartConfiguration
            };
            var options2 = {
                type: 'bar',
                responsive: true,
                legend: {
                    display: false
                },
                data: {
                    labels: ['{!! implode("','", $last7DaysLabels) !!}'],
                    datasets: [{
                        label: "Completed Cases",
                        fill: true,
                        backgroundColor: gradientStroke,
                        hoverBackgroundColor: gradientStroke,
                        borderColor: '#1f8ef1',
                        borderWidth: 2,
                        borderDash: [],
                        borderDashOffset: 0.0,
                        data: completedChartData['Cases']
                    }]
                },
                options: barChartConfiguration
            };
            var completedChart = new Chart(ctx, options1);

            $("#completedChartCases").click(function() {

                completedChart.destroy();
                completedChart = new Chart(ctx, options1);
            });
            $("#completedChartUnits").click(function() {

                completedChart.destroy();
                completedChart = new Chart(ctx, options2);
            });
        }

        function initDoughnutChart() {
            const chartContainer = document.getElementById("chartContainer");
            if (!chartContainer || typeof CanvasJS === 'undefined') {
                return;
            }
            var doughnetChartData = {
                "Units": [{
                        y: {!! $CompletedJobsToday !!},
                        name: "Completed"
                    },
                    {
                        y: {!! $ActiveJobsToday !!},
                        name: "Active"
                    },
                    {
                        y: {!! $waitingJobsToday !!},
                        name: "Waiting"
                    }

                ]
            };
            CanvasJS.addColorSet("greenShades",
                [ //colorSet Array

                    "#37b44a",
                    "#007bff",
                    "#dc3545"
                ]);
            var options = {

                exportFileName: "Active/Waiting/Completed Chart",
                exportEnabled: false,
                animationEnabled: true,
                animationDuration: 800,
                colorSet: "greenShades",
                //                title:{
                //                    text: "Monthly Expense"
                //                },
                legend: {
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "doughnut",
                    innerRadius: 50,
                    indexLabelTextAlign: "center",
                    //indexLabelWrap: true,

                    indexLabelPlacement: "outside",
                    indexLabelFontColor: "black",
                    showInLegend: false,
                    toolTipContent: "<b>{name}</b>: {y} (#percent%)",
                    indexLabel: "{name}",
                    dataPoints: doughnetChartData["Units"]
                }]

            };

            var compWaitingChart = new CanvasJS.Chart("chartContainer",
                options);

            compWaitingChart.render();




            function explodePie(e) {
                if (typeof(e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e
                        .dataPointIndex].exploded) {
                    e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
                } else {
                    e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
                }
                e.chart.render();
            }

        }

        function initPerformanceChart() {
            const chartBig = document.getElementById("chartBig1");
            if (!chartBig || !chartBig.getContext || typeof Chart === 'undefined') {
                return;
            }

            gradientChartOptionsConfigurationWithTooltipPurple = {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },

                tooltips: {
                    backgroundColor: '#f5f5f5',
                    titleFontColor: '#333',
                    bodyFontColor: '#666',
                    bodySpacing: 4,
                    xPadding: 12,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                    callbacks: {
                        label: function(tooltipItems, data) {
                            return tooltipItems.yLabel + ' ' + data.datasets[tooltipItems.datasetIndex].label;
                        }
                    }
                },
                responsive: true,
                scales: {
                    yAxes: [{
                        barPercentage: 1.6,
                        gridLines: {
                            drawBorder: false,
                            color: 'rgba(29,140,248,0.0)',
                            zeroLineColor: "transparent"
                        },
                        ticks: {
                            suggestedMin: 20,
                            suggestedMax: 0,
                            padding: 20,
                            fontColor: "#9a9a9a",

                        }
                    }],

                    xAxes: [{
                        barPercentage: 1.6,
                        gridLines: {
                            drawBorder: false,
                            color: 'rgba(225,78,202,0.1)',
                            zeroLineColor: "transparent"
                        },
                        ticks: {
                            padding: 20,
                            fontColor: "#9a9a9a",
                            fontStyle: 'bold'
                        }
                    }]
                }
            };
            var chart_labels = ['{!! implode("', '", $last30DaysLabels) !!}'];

            var performanceChartData = {
                "Cases": ['{!! implode("','", $compCasesCount30Days) !!}'],
                "Units": ['{!! implode("','", $compUnitsCount30Days) !!}'],
                "Income": ['{!! implode("','", $collectionsInLast30Days) !!}'],
                "Sales": ['{!! implode("','", $sales30Days) !!}']
            };


            var ctx = chartBig.getContext('2d');

            var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);

            gradientStroke.addColorStop(1, 'rgba(72,72,176,0.1)');
            gradientStroke.addColorStop(0.4, 'rgba(72,72,176,0.0)');
            gradientStroke.addColorStop(0, 'rgba(55, 180, 74,0)'); //purple colors
            var config = {
                type: 'line',
                data: {
                    labels: chart_labels,
                    datasets: [{
                        label: "Units",

                        fill: true,
                        backgroundColor: gradientStroke,
                        borderColor: '#31b72f',
                        borderWidth: 2,
                        borderDash: [],
                        borderDashOffset: 15.0,
                        pointBackgroundColor: '#226746',
                        pointBorderColor: 'rgba(255,255,255,0)',
                        //                       pointHoverBackgroundColor: '#d346b1',
                        pointBorderWidth: 20,
                        //                       pointHoverRadius: 4,
                        //                        pointHoverBorderWidth: 15,
                        pointRadius: 5,
                        data: performanceChartData["Units"]
                    }]
                },
                options: gradientChartOptionsConfigurationWithTooltipPurple
            };
            var myChartData = new Chart(ctx, config);
            $("#0").click(function() {
                var data = myChartData.config.data;
                data.datasets[0].data = performanceChartData["Units"];
                data.datasets[0].label = "Units";

                myChartData.update();
            });
            $("#1").click(function() {
                var data = myChartData.config.data;
                data.datasets[0].data = performanceChartData["Cases"];
                data.datasets[0].label = "Cases";

                myChartData.update();
            });

            $("#2").click(function() {
                var data = myChartData.config.data;
                data.datasets[0].data = performanceChartData["Income"];
                data.datasets[0].label = "JOD Collected Payments";

                myChartData.update();
            });
            $("#3").click(function() {
                var data = myChartData.config.data;
                data.datasets[0].data = performanceChartData["Sales"];
                data.datasets[0].label = "JOD";
                myChartData.update();
            });

        }
    </script>


    <script>
        const animateCSS = (element, animation, prefix = 'animate__') =>
            // We create a Promise and return it
            new Promise((resolve, reject) => {
                const animationName = `${prefix}${animation}`;
                const node = document.querySelector(element);

                node.classList.add(`${prefix}animated`, animationName);

                // When the animation ends, we clean the classes and resolve the Promise
                function handleAnimationEnd(event) {
                    event.stopPropagation();
                    node.classList.remove(`${prefix}animated`, animationName);
                    resolve('Animation ended');
                }

                node.addEventListener('animationend', handleAnimationEnd, {once: true});
            });
    </script>
@endpush
