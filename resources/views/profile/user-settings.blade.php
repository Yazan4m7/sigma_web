@extends('layouts.app', ['page' => __('User Settings'), 'pageSlug' => 'user-settings'])

@section('content')
    <style>
        .sigma-settings-shell {
            max-width: 960px;
            margin: 0 auto;
        }

        .sigma-settings-card {
            border: 1px solid rgba(34, 42, 66, 0.08);
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(34, 42, 66, 0.07);
            overflow: hidden;
        }

        .sigma-settings-card .card-header {
            border-bottom: 1px solid rgba(34, 42, 66, 0.08);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96) 0%, rgba(248, 250, 252, 0.96) 100%);
            padding: 16px 18px;
        }

        .sigma-settings-group {
            background: #fff;
            border: 1px solid #e6edf5;
            border-radius: 12px;
            padding: 10px 12px;
            margin-bottom: 14px;
        }

        .sigma-settings-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 6px;
            border-bottom: 1px dashed #e8edf4;
        }

        .sigma-settings-row:last-child {
            border-bottom: 0;
        }

        .sigma-settings-title {
            font-size: 14px;
            font-weight: 600;
            color: #27364a;
            margin: 0;
        }

        .sigma-settings-subtitle {
            font-size: 12px;
            color: #6b7b8f;
            margin: 2px 0 0;
        }

        .sigma-settings-toggle {
            width: 44px;
            height: 22px;
            accent-color: #2b7b7d;
            flex: 0 0 auto;
        }

        .sigma-settings-actions {
            border: 1px solid #ffe7b2;
            background: #fff9ec;
            border-radius: 12px;
            padding: 12px;
        }

        .sigma-settings-actions .btn {
            min-width: 220px;
        }
    </style>

    <div class="row">
        <div class="col-12 sigma-settings-shell">
            <div class="card sigma-settings-card">
                <div class="card-header">
                    <h5 class="title mb-0">User Settings</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">These preferences are saved on this device and browser.</p>

                    <div class="sigma-settings-group">
                        <div class="sigma-settings-row">
                            <div>
                                <p class="sigma-settings-title">Enable Font Size Locks</p>
                                <p class="sigma-settings-subtitle">Master switch for all font-lock options below.</p>
                            </div>
                            <input id="pref-locks-master" type="checkbox" class="sigma-pref-toggle sigma-settings-toggle" data-pref-key="locks_enabled">
                        </div>

                        <div class="sigma-settings-row">
                            <div>
                                <p class="sigma-settings-title">Lock Dialog Font Sizes</p>
                                <p class="sigma-settings-subtitle">Keeps dialog text-size scaling stable.</p>
                            </div>
                            <input id="pref-lock-dialogs" type="checkbox" class="sigma-pref-toggle sigma-pref-child-lock sigma-settings-toggle" data-pref-key="lock_dialog_fonts">
                        </div>

                        <div class="sigma-settings-row">
                            <div>
                                <p class="sigma-settings-title">Lock Table Font Sizes</p>
                                <p class="sigma-settings-subtitle">Helps dense table layouts remain compact and consistent.</p>
                            </div>
                            <input id="pref-lock-tables" type="checkbox" class="sigma-pref-toggle sigma-pref-child-lock sigma-settings-toggle" data-pref-key="lock_table_fonts">
                        </div>

                        <div class="sigma-settings-row">
                            <div>
                                <p class="sigma-settings-title">Lock Other UI Font Sizes</p>
                                <p class="sigma-settings-subtitle">Applies text-size lock to non-dialog, non-table areas.</p>
                            </div>
                            <input id="pref-lock-other" type="checkbox" class="sigma-pref-toggle sigma-pref-child-lock sigma-settings-toggle" data-pref-key="lock_other_fonts">
                        </div>
                    </div>

                    <div class="sigma-settings-group">
                        <div class="sigma-settings-row">
                            <div>
                                <p class="sigma-settings-title">Disable Sidebar Expansion (icons only)</p>
                                <p class="sigma-settings-subtitle">Prevents sidebar hover/click expansion and keeps compact icon mode.</p>
                            </div>
                            <input id="pref-disable-sidebar-expand" type="checkbox" class="sigma-pref-toggle sigma-settings-toggle" data-pref-key="disable_sidebar_expand">
                        </div>
                    </div>

                    <div class="sigma-settings-actions mb-0">
                        <button id="resetAllTableWidthsBtn" type="button" class="btn btn-warning btn-sm">
                            Reset All Saved Table Column Widths
                        </button>
                        <small class="d-block text-muted mt-2">Equivalent to resetting table widths to defaults across pages.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
