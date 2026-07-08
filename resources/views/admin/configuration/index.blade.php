@extends('layouts.app', ['pageSlug' => 'Configuration'])

@section('content')
<style>
    .config-card {
        margin-bottom: 24px;
        transition: box-shadow 0.2s ease;
    }
    
    .config-card:hover {
        transform: none;
    }
    
    .config-header {
        background: transparent;
        color: #243746;
        padding: 0 0 16px;
        border-radius: 0;
        margin-bottom: 0;
        border-bottom: 1px solid rgba(188, 206, 216, 0.4);
    }
    
    .config-header h4 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .config-header .icon {
        width: 24px;
        height: 24px;
        opacity: 0.9;
    }
    
    .config-body {
        padding: 20px 0 0;
    }
    
    .config-section {
        margin-bottom: 30px;
    }
    
    .config-section:last-child {
        margin-bottom: 0;
    }
    
    .setting-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid #f1f3f4;
        transition: background-color 0.2s ease;
    }
    
    .setting-item:last-child {
        border-bottom: none;
    }
    
    .setting-item:hover {
        background-color: #f8f9fa;
        margin: 0 -15px;
        padding: 16px 15px;
        border-radius: 8px;
    }
    
    .setting-info {
        flex: 1;
    }
    
    .setting-title {
        font-weight: 600;
        color: #2d3748;
        margin: 0 0 4px 0;
        font-size: 16px;
    }
    
    .setting-description {
        color: #718096;
        margin: 0;
        font-size: 14px;
        line-height: 1.4;
    }
    
    .setting-control {
        margin-left: 20px;
    }
    
    /* Custom Toggle Switch */
    .toggle-switch {
        position: relative;
        width: 54px;
        height: 28px;
        background-color: #cbd5e0;
        border-radius: 14px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        border: none;
        outline: none;
        -webkit-appearance: none;
        appearance: none;
    }
    
    .toggle-switch:checked {
        background-color: #48bb78;
    }
    
    .toggle-switch::before {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 24px;
        height: 24px;
        background-color: white;
        border-radius: 50%;
        transition: transform 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .toggle-switch:checked::before {
        transform: translateX(26px);
    }
    
    .toggle-switch:focus {
        box-shadow: 0 0 0 3px rgba(72, 187, 120, 0.3);
    }
    
    /* Hide default checkbox styling */
    input[type="checkbox"].toggle-switch::-webkit-outer-spin-button,
    input[type="checkbox"].toggle-switch::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    input[type="checkbox"].toggle-switch {
        -moz-appearance: none;
    }
    
    /* Form Controls */
    .form-control-custom {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 14px;
        transition: border-color 0.3s ease;
        width: 80px;
    }
    
    .form-control-custom:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }
    
    /* Action Buttons */
    .btn-primary-custom {
        background: linear-gradient(135deg, #408385 0%, #67aeb0 100%);
        border: 1px solid #408385;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        color: white;
        box-shadow: 0 6px 16px rgba(64, 131, 133, 0.24);
        transition: all 0.3s ease;
    }
    
    .btn-primary-custom:hover,
    .btn-primary-custom:focus {
        background: linear-gradient(135deg, #336f71 0%, #5ca0a2 100%);
        border-color: #336f71;
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(51, 111, 113, 0.24);
        color: white;
    }

    .btn-primary-custom:active {
        background: linear-gradient(135deg, #285f61 0%, #4c8587 100%);
        border-color: #285f61;
        transform: translateY(0);
        box-shadow: 0 4px 12px rgba(40, 95, 97, 0.2);
        color: white;
    }
    
    .btn-secondary-custom {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(188, 206, 216, 0.55);
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        color: #2d5f6d;
        transition: all 0.3s ease;
    }
    
    .btn-secondary-custom:hover {
        background: #2d5f6d;
        border-color: #2d5f6d;
        color: #ffffff;
        transform: translateY(-1px);
    }
    
    /* Page Header */
    .page-header {
        padding: 30px;
        margin-bottom: 30px;
    }
    
    .page-title {
        margin: 0;
        color: #2d3748;
        font-weight: 600;
        font-size: 28px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .page-subtitle {
        margin: 8px 0 0 0;
        color: #718096;
        font-size: 16px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .config-body {
            padding: 20px;
        }
        
        .setting-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        
        .setting-control {
            margin-left: 0;
        }
    }
</style>

<div class="content sigma-config-page sigma-admin-configuration-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header sigma-config-card">
            <h1 class="page-title">
                <i class="fas fa-cogs" style="color: #408385;"></i>
                System Configuration
            </h1>
            <p class="page-subtitle">Manage system settings, features, and preferences</p>
        </div>

        <form action="{{ route('configuration.update') }}" method="POST">
            @csrf
            
            <div class="row">
                <!-- Workflow Settings -->
                <div class="col-lg-6">
                    <div class="config-card sigma-config-card">
                        <div class="config-header">
                            <h4>
                                <i class="fas fa-cogs icon"></i>
                                Workflow Settings
                            </h4>
                        </div>
                        <div class="config-body">
                            <div class="setting-item">
                                <div class="setting-info">
                                    <h5 class="setting-title">Enable Repeating</h5>
                                    <p class="setting-description">Allow cases to be marked for repetition if quality issues arise</p>
                                </div>
                                <div class="setting-control">
                                    <input type="checkbox" name="enable_repeating" class="toggle-switch" {{ $config['enable_repeating'] ? 'checked' : '' }}>
                                </div>
                            </div>
                            
                            <div class="setting-item">
                                <div class="setting-info">
                                    <h5 class="setting-title">Enable Redo</h5>
                                    <p class="setting-description">Allow cases to be redone from specific stages</p>
                                </div>
                                <div class="setting-control">
                                    <input type="checkbox" name="enable_redo" class="toggle-switch" {{ $config['enable_redo'] ? 'checked' : '' }}>
                                </div>
                            </div>
                            
                            <div class="setting-item">
                                <div class="setting-info">
                                    <h5 class="setting-title">Enable Remake</h5>
                                    <p class="setting-description">Allow cases to be completely remade from beginning</p>
                                </div>
                                <div class="setting-control">
                                    <input type="checkbox" name="enable_remake" class="toggle-switch" {{ $config['enable_remake'] ? 'checked' : '' }}>
                                </div>
                            </div>
                            
                            <div class="setting-item">
                                <div class="setting-info">
                                    <h5 class="setting-title">Count Implants as Unit</h5>
                                    <p class="setting-description">Include implants in unit count calculations for pricing and reporting</p>
                                </div>
                                <div class="setting-control">
                                    <input type="checkbox" name="count_implants_as_unit" class="toggle-switch" {{ $config['count_implants_as_unit'] ? 'checked' : '' }}>
                                </div>
                            </div>
                            
                            <div class="setting-item">
                                <div class="setting-info">
                                    <h5 class="setting-title">Count Abutments as Unit</h5>
                                    <p class="setting-description">Include abutments in unit count calculations for pricing and reporting</p>
                                </div>
                                <div class="setting-control">
                                    <input type="checkbox" name="count_abutments_as_unit" class="toggle-switch" {{ $config['count_abutments_as_unit'] ? 'checked' : '' }}>
                                </div>
                            </div>

                            <div class="setting-item">
                                <div class="setting-info">
                                    <h5 class="setting-title">Case Auto Numbering</h5>
                                    <p class="setting-description">Automatically generate sequential case numbers</p>
                                </div>
                                <div class="setting-control">
                                    <input type="checkbox" name="case_auto_numbering" class="toggle-switch" {{ $config['case_auto_numbering'] ? 'checked' : '' }}>
                                </div>
                            </div>
                            
                            <div class="setting-item">
                                <div class="setting-info">
                                    <h5 class="setting-title">Case ID Prefix</h5>
                                    <p class="setting-description">Prefix for auto-generated case numbers</p>
                                </div>
                                <div class="setting-control">
                                    <input type="text" name="case_prefix" class="form-control-custom" value="{{ $config['case_prefix'] }}" maxlength="10" style="width: 100px;">
                                </div>
                            </div>
                            
                            <div class="setting-item">
                                <div class="setting-info">
                                    <h5 class="setting-title">Delivery Notifications</h5>
                                    <p class="setting-description">Send notifications when cases are ready for delivery</p>
                                </div>
                                <div class="setting-control">
                                    <input type="checkbox" name="delivery_notifications" class="toggle-switch" {{ $config['delivery_notifications'] ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quality Control -->
                <div class="col-lg-6">
                    <div class="config-card sigma-config-card">
                        <div class="config-header">
                            <h4>
                                <i class="fas fa-check-double icon"></i>
                                Quality Control
                            </h4>
                        </div>
                        <div class="config-body">
                            <div class="setting-item">
                                <div class="setting-info">
                                    <h5 class="setting-title">Mandatory QC Photos</h5>
                                    <p class="setting-description">Require photos to be uploaded during QC inspection</p>
                                </div>
                                <div class="setting-control">
                                    <input type="checkbox" name="mandatory_qc_photos" class="toggle-switch" {{ $config['mandatory_qc_photos'] ? 'checked' : '' }}>
                                </div>
                            </div>
                            
                            <div class="setting-item">
                                <div class="setting-info">
                                    <h5 class="setting-title">QC Approval Required</h5>
                                    <p class="setting-description">Require QC approval before cases can proceed to delivery</p>
                                </div>
                                <div class="setting-control">
                                    <input type="checkbox" name="qc_approval_required" class="toggle-switch" {{ $config['qc_approval_required'] ? 'checked' : '' }}>
                                </div>
                            </div>
                            
                            <div class="setting-item">
                                <div class="setting-info">
                                    <h5 class="setting-title">Failure Cause Tracking</h5>
                                    <p class="setting-description">Track and categorize reasons for case failures or repeats</p>
                                </div>
                                <div class="setting-control">
                                    <input type="checkbox" name="failure_cause_tracking" class="toggle-switch" {{ $config['failure_cause_tracking'] ? 'checked' : '' }}>
                                </div>
                            </div>
                            
                            <div class="setting-item">
                                <div class="setting-info">
                                    <h5 class="setting-title">Quality Metrics Dashboard</h5>
                                    <p class="setting-description">Display quality metrics and performance indicators</p>
                                </div>
                                <div class="setting-control">
                                    <input type="checkbox" name="quality_metrics" class="toggle-switch" {{ $config['quality_metrics'] ? 'checked' : '' }}>
                                </div>
                            </div>

                            <div class="setting-item">
                                <div class="setting-info">
                                    <h5 class="setting-title">Material Cost Tracking</h5>
                                    <p class="setting-description">Track material costs per case for profitability analysis</p>
                                </div>
                                <div class="setting-control">
                                    <input type="checkbox" name="material_cost_tracking" class="toggle-switch" {{ $config['material_cost_tracking'] ? 'checked' : '' }}>
                                </div>
                            </div>
                            
                            <div class="setting-item">
                                <div class="setting-info">
                                    <h5 class="setting-title">Device Capacity Tracking</h5>
                                    <p class="setting-description">Monitor device utilization and capacity limits</p>
                                </div>
                                <div class="setting-control">
                                    <input type="checkbox" name="device_capacity_tracking" class="toggle-switch" {{ $config['device_capacity_tracking'] ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4 mb-5">
                <button type="submit" class="btn btn-primary-custom mr-3">
                    <i class="fas fa-save mr-2"></i>
                    Save Configuration
                </button>
                <a href="{{ route('configuration.reset') }}" class="btn btn-secondary-custom" 
                   onclick="return confirm('Are you sure you want to reset all settings to defaults?')">
                    <i class="fas fa-undo mr-2"></i>
                    Reset to Defaults
                </a>
            </div>
        </form>

        <div class="config-card sigma-config-card">
            <div class="config-header">
                <h4>
                    <i class="fas fa-code-branch icon"></i>
                    Deployment Controls
                </h4>
            </div>
            <div class="config-body">
                <div class="setting-item">
                    <div class="setting-info">
                        <h5 class="setting-title">Deploy Latest Version</h5>
                        <p class="setting-description">Pull the latest code from `main`, then refresh config and compiled views.</p>
                    </div>
                    <div class="setting-control">
                        <form action="{{ route('admin.deploy') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="fas fa-cloud-download-alt mr-2"></i>
                                Deploy
                            </button>
                        </form>
                    </div>
                </div>

                <div class="setting-item">
                    <div class="setting-info">
                        <h5 class="setting-title">Rollback Previous Version</h5>
                        <p class="setting-description">Restore the previous Git revision in `/var/www/sigma`, then refresh config and compiled views.</p>
                    </div>
                    <div class="setting-control">
                        <form action="{{ route('admin.rollback') }}" method="POST" style="display:inline;" onsubmit="return confirm('Rollback to the previous deployed version?');">
                            @csrf
                            <button type="submit" class="btn btn-secondary-custom">
                                <i class="fas fa-history mr-2"></i>
                                Rollback
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
