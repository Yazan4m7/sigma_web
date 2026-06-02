@extends('layouts.app', ['pageSlug' => 'Configuration'])

@section('content')
<style>
    .config-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: 1px solid #e3e6f0;
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }
    
    .config-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .config-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 30px;
        border-radius: 12px 12px 0 0;
        margin-bottom: 0;
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
        padding: 30px;
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        color: white;
    }
    
    .btn-secondary-custom {
        background: #e2e8f0;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        color: #4a5568;
        transition: all 0.3s ease;
    }
    
    .btn-secondary-custom:hover {
        background: #cbd5e0;
        transform: translateY(-2px);
    }
    
    /* Page Header */
    .page-header {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 30px;
        border: 1px solid #e3e6f0;
    }
    
    .page-title {
        margin: 0;
        color: #2d3748;
        font-weight: 700;
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
    
    /* Success Alert */
    .alert-success-custom {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 20px;
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

<div class="content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-cogs" style="color: #667eea;"></i>
                System Configuration
            </h1>
            <p class="page-subtitle">Manage system settings, features, and preferences</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success-custom">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('configuration.update') }}" method="POST">
            @csrf
            
            <div class="row">
                <!-- Workflow Settings -->
                <div class="col-lg-6">
                    <div class="config-card">
                        <div class="config-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
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
                    <div class="config-card">
                        <div class="config-header" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
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
    </div>
</div>
@endsection