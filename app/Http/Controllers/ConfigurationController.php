<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ConfigurationController extends Controller
{
    /**
     * Show the configuration page
     */
    public function index()
    {
        $config = $this->getConfiguration();
        return view('admin.configuration.index', compact('config'));
    }

    /**
     * Update configuration settings
     */
    public function update(Request $request)
    {
        $config = [
            // System Settings
            'maintenance_mode' => $request->has('maintenance_mode'),
            'debug_mode' => $request->has('debug_mode'),
            'cache_enabled' => $request->has('cache_enabled'),
            
            // Workflow Settings
            'enable_repeating' => $request->has('enable_repeating'),
            'enable_redo' => $request->has('enable_redo'),
            'enable_remake' => $request->has('enable_remake'),
            'count_implants_as_unit' => $request->has('count_implants_as_unit'),
            'count_abutments_as_unit' => $request->has('count_abutments_as_unit'),
            
            // Stage Configuration
            'require_design_stage' => $request->has('require_design_stage'),
            'require_milling_stage' => $request->has('require_milling_stage'),
            'require_3d_printing_stage' => $request->has('require_3d_printing_stage'),
            'require_sintering_stage' => $request->has('require_sintering_stage'),
            'require_pressing_stage' => $request->has('require_pressing_stage'),
            'require_finishing_stage' => $request->has('require_finishing_stage'),
            'require_qc_stage' => $request->has('require_qc_stage'),
            
            // Case Management
            'case_auto_numbering' => $request->has('case_auto_numbering'),
            'case_prefix' => $request->input('case_prefix', 'SIGMA'),
            'delivery_notifications' => $request->has('delivery_notifications'),
            'late_case_warnings' => $request->has('late_case_warnings'),
            'auto_archive_delivered' => $request->has('auto_archive_delivered'),
            'auto_archive_days' => $request->input('auto_archive_days', 30),
            
            // Materials & Job Types
            'material_cost_tracking' => $request->has('material_cost_tracking'),
            'job_time_tracking' => $request->has('job_time_tracking'),
            'material_usage_reports' => $request->has('material_usage_reports'),
            'batch_processing' => $request->has('batch_processing'),
            'device_capacity_tracking' => $request->has('device_capacity_tracking'),
            
            // Client & Billing
            'client_portal' => $request->has('client_portal'),
            'auto_invoice_generation' => $request->has('auto_invoice_generation'),
            'payment_reminders' => $request->has('payment_reminders'),
            'client_case_photos' => $request->has('client_case_photos'),
            'delivery_confirmations' => $request->has('delivery_confirmations'),
            
            // Quality Control
            'mandatory_qc_photos' => $request->has('mandatory_qc_photos'),
            'qc_approval_required' => $request->has('qc_approval_required'),
            'failure_cause_tracking' => $request->has('failure_cause_tracking'),
            'quality_metrics' => $request->has('quality_metrics'),
            
            // Security & Access
            'two_factor_auth' => $request->has('two_factor_auth'),
            'session_timeout' => $request->input('session_timeout', 120),
            'max_login_attempts' => $request->input('max_login_attempts', 5),
            'audit_logging' => $request->has('audit_logging'),
        ];

        $this->saveConfiguration($config);

        return redirect()->back()->with('success', 'Configuration updated successfully!');
    }

    /**
     * Get configuration from storage or return defaults
     */
    private function getConfiguration()
    {
        $defaultConfig = [
            // System Settings
            'maintenance_mode' => false,
            'debug_mode' => config('app.debug', false),
            'cache_enabled' => true,
            
            // Workflow Settings
            'enable_repeating' => true,
            'enable_redo' => true,
            'enable_remake' => true,
            'count_implants_as_unit' => true,
            'count_abutments_as_unit' => false,
            
            // Stage Configuration
            'require_design_stage' => true,
            'require_milling_stage' => true,
            'require_3d_printing_stage' => true,
            'require_sintering_stage' => true,
            'require_pressing_stage' => true,
            'require_finishing_stage' => true,
            'require_qc_stage' => true,
            
            // Case Management
            'case_auto_numbering' => true,
            'case_prefix' => 'SIGMA',
            'delivery_notifications' => true,
            'late_case_warnings' => true,
            'auto_archive_delivered' => false,
            'auto_archive_days' => 30,
            
            // Materials & Job Types
            'material_cost_tracking' => false,
            'job_time_tracking' => false,
            'material_usage_reports' => true,
            'batch_processing' => true,
            'device_capacity_tracking' => true,
            
            // Client & Billing
            'client_portal' => false,
            'auto_invoice_generation' => false,
            'payment_reminders' => true,
            'client_case_photos' => true,
            'delivery_confirmations' => true,
            
            // Quality Control
            'mandatory_qc_photos' => false,
            'qc_approval_required' => true,
            'failure_cause_tracking' => true,
            'quality_metrics' => false,
            
            // Security & Access
            'two_factor_auth' => false,
            'session_timeout' => 120,
            'max_login_attempts' => 5,
            'audit_logging' => false,
        ];

        if (Storage::exists('config/site_config.json')) {
            $stored = json_decode(Storage::get('config/site_config.json'), true);
            return array_merge($defaultConfig, $stored);
        }

        return $defaultConfig;
    }

    /**
     * Save configuration to storage
     */
    private function saveConfiguration($config)
    {
        Storage::put('config/site_config.json', json_encode($config, JSON_PRETTY_PRINT));
        
        // Clear specific cache and general cache
        Cache::forget('site_config');
        Cache::flush();
    }

    /**
     * Reset configuration to defaults
     */
    public function reset()
    {
        Storage::delete('config/site_config.json');
        Cache::forget('site_config');
        Cache::flush();
        
        return redirect()->back()->with('success', 'Configuration reset to defaults!');
    }
}
