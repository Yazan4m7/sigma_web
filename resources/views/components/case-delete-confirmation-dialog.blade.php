<div class="modal fade sigma-modal--case-delete-confirmation sigma-dialog-overlay" tabindex="-1"
     role="alertdialog" id="caseDeleteConfirmationDialog" data-backdrop="false" data-keyboard="true"
     aria-labelledby="caseDeleteConfirmationTitle" aria-describedby="caseDeleteConfirmationMessage">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header case-preview-header">
                <x-sigma-close-button />
            </div>
            <div class="modal-body sigma-delete-confirmation__body">
                <div class="sigma-delete-confirmation__icon" aria-hidden="true">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <h5 class="sigma-delete-confirmation__title" id="caseDeleteConfirmationTitle">Delete Case?</h5>
                <p class="sigma-delete-confirmation__case">
                    <span id="caseDeleteConfirmationClient">-</span>
                    <span aria-hidden="true"> — </span>
                    <span id="caseDeleteConfirmationPatient">-</span>
                </p>
                <p class="sigma-delete-confirmation__message" id="caseDeleteConfirmationMessage">
                    This will also delete related information, including invoices and photos. This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer sigma-delete-confirmation__footer">
                <button type="button" class="btn btn-secondary sigma-action-btn" id="cancelCaseDeleteButton" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger sigma-action-btn" id="confirmCaseDeleteButton">Delete Case</button>
            </div>
        </div>
    </div>
</div>

@once
    @push('js')
        <script>
            $( document ).ready( function () {
                var $caseDeleteConfirmationDialog = $( '#caseDeleteConfirmationDialog' );

                if (!$caseDeleteConfirmationDialog.length) {
                    return;
                }

                if ($caseDeleteConfirmationDialog.parent()[0] !== document.body) {
                    $caseDeleteConfirmationDialog.appendTo( document.body );
                }

                function cleanupCaseDeleteConfirmationArtifacts() {
                    if (document.querySelector( '.modal.show' )) {
                        return;
                    }

                    $( '.modal-backdrop' ).remove();
                    $( 'body' )
                        .removeClass( 'modal-open' )
                        .css( {
                            'padding-right': ''
                        } );
                    document.body.classList.remove( 'sigma-dialog-scroll-unlocked' );
                }

                function showCaseDeleteConfirmationDialog(url, clientName, patientName, showLoadingScreen) {
                    if (!url) {
                        return;
                    }

                    $caseDeleteConfirmationDialog
                        .off( 'hidden.bs.modal.sigmaCaseDeleteNavigate' )
                        .data( {
                            deleteUrl: url,
                            showLoadingScreen: showLoadingScreen
                        } );
                    $( '#caseDeleteConfirmationClient' ).text( clientName || '-' );
                    $( '#caseDeleteConfirmationPatient' ).text( patientName || '-' );
                    $caseDeleteConfirmationDialog.modal( 'show' );
                }

                $( document )
                    .off( 'show.bs.modal.sigmaCaseDeleteConfirmation', '#caseDeleteConfirmationDialog' )
                    .on( 'show.bs.modal.sigmaCaseDeleteConfirmation', '#caseDeleteConfirmationDialog', function () {
                        document.body.classList.add( 'sigma-dialog-scroll-unlocked' );
                    } )
                    .off( 'shown.bs.modal.sigmaCaseDeleteConfirmation', '#caseDeleteConfirmationDialog' )
                    .on( 'shown.bs.modal.sigmaCaseDeleteConfirmation', '#caseDeleteConfirmationDialog', function () {
                        $( '#cancelCaseDeleteButton' ).trigger( 'focus' );
                    } )
                    .off( 'click.sigmaCaseDeleteConfirmationBackdrop', '#caseDeleteConfirmationDialog' )
                    .on( 'click.sigmaCaseDeleteConfirmationBackdrop', '#caseDeleteConfirmationDialog', function (event) {
                        if (event.target === this) {
                            $( this ).modal( 'hide' );
                        }
                    } )
                    .off( 'hidden.bs.modal.sigmaCaseDeleteConfirmation', '#caseDeleteConfirmationDialog' )
                    .on( 'hidden.bs.modal.sigmaCaseDeleteConfirmation', '#caseDeleteConfirmationDialog', function () {
                        $( this )
                            .removeData( 'deleteUrl' )
                            .removeData( 'showLoadingScreen' );
                        $( '#confirmCaseDeleteButton' )
                            .prop( 'disabled', false )
                            .removeAttr( 'aria-busy' );
                        setTimeout( cleanupCaseDeleteConfirmationArtifacts, 0 );
                    } )
                    .off( 'click.sigmaCaseDeleteConfirmation', '#confirmCaseDeleteButton' )
                    .on( 'click.sigmaCaseDeleteConfirmation', '#confirmCaseDeleteButton', function () {
                        var url = $caseDeleteConfirmationDialog.data( 'deleteUrl' );
                        var showLoadingScreen = $caseDeleteConfirmationDialog.data( 'showLoadingScreen' );

                        if (!url) {
                            return;
                        }

                        $( this )
                            .prop( 'disabled', true )
                            .attr( 'aria-busy', 'true' );
                        $caseDeleteConfirmationDialog.one( 'hidden.bs.modal.sigmaCaseDeleteNavigate', function () {
                            if (showLoadingScreen && typeof window.showLoadingScreen === 'function') {
                                window.showLoadingScreen();
                            }
                            window.location.assign( url );
                        } );
                        $caseDeleteConfirmationDialog.modal( 'hide' );
                    } )
                    .off( 'click.sigmaCaseDeleteRequest', '.js-case-delete' )
                    .on( 'click.sigmaCaseDeleteRequest', '.js-case-delete', function (event) {
                        event.preventDefault();
                        event.stopPropagation();

                        var trigger = this;
                        var url = trigger.getAttribute( 'href' );
                        var clientName = trigger.getAttribute( 'data-clientName' );
                        var patientName = trigger.getAttribute( 'data-patientName' );
                        var showLoadingScreen = trigger.getAttribute( 'data-delete-show-loading' ) === 'true';
                        var $openModal = $( trigger ).closest( '.modal.show' );

                        function openDeleteConfirmation() {
                            showCaseDeleteConfirmationDialog( url, clientName, patientName, showLoadingScreen );
                        }

                        if ($openModal.length && !$openModal.is( $caseDeleteConfirmationDialog )) {
                            $openModal.one( 'hidden.bs.modal.sigmaCaseDeletePrompt', function () {
                                setTimeout( openDeleteConfirmation, 0 );
                            } );
                            $openModal.modal( 'hide' );
                            return;
                        }

                        openDeleteConfirmation();
                    } );
            } );
        </script>
    @endpush
@endonce
