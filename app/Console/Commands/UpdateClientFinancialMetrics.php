<?php

namespace App\Console\Commands;

use App\client;
use Illuminate\Console\Command;

class UpdateClientFinancialMetrics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:update-financial-metrics {--client_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update financial metrics (income/outcome %, approx income, risk level) for all clients or a specific client';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $clientId = $this->option('client_id');
        
        if ($clientId) {
            // Update specific client
            $client = client::find($clientId);
            
            if (!$client) {
                $this->error("Client with ID {$clientId} not found.");
                return 1;
            }
            
            $this->info("Updating financial metrics for client: {$client->name}");
            $client->updateFinancialMetrics();
            $this->info("✓ Updated successfully");
            
            // Display results
            $this->displayClientMetrics($client);
            
        } else {
            // Update all clients
            $clients = client::all();
            $this->info("Updating financial metrics for " . $clients->count() . " clients...");
            
            $bar = $this->output->createProgressBar($clients->count());
            $bar->start();
            
            foreach ($clients as $client) {
                $client->updateFinancialMetrics();
                $bar->advance();
            }
            
            $bar->finish();
            $this->info("\n✓ All clients updated successfully");
            
            // Show summary
            $this->showSummary();
        }
        
        return 0;
    }
    
    /**
     * Display metrics for a single client
     */
    private function displayClientMetrics($client)
    {
        $this->table(
            ['Metric', 'Value'],
            [
                ['Income %', $client->income_percentage . '%'],
                ['Outcome %', $client->outcome_percentage . '%'],
                ['Approx Monthly Income', '$' . number_format($client->approx_income, 2)],
                ['Risk Level', strtoupper($client->risk_level)],
                ['Avg Payment Days', $client->avg_payment_days . ' days'],
                ['Current Overdue Days', $client->current_overdue_days . ' days'],
                ['Outstanding Balance', '$' . number_format($client->outstanding_balance, 2)],
            ]
        );
    }
    
    /**
     * Show summary of all clients by risk level
     */
    private function showSummary()
    {
        $this->info("\n=== Risk Level Summary ===");
        
        $summary = [
            ['Low Risk', client::where('risk_level', 'low')->count()],
            ['Medium Risk', client::where('risk_level', 'medium')->count()],
            ['High Risk', client::where('risk_level', 'high')->count()],
            ['Critical Risk', client::where('risk_level', 'critical')->count()],
        ];
        
        $this->table(['Risk Level', 'Count'], $summary);
        
        // Show high risk clients
        $highRiskClients = client::whereIn('risk_level', ['high', 'critical'])
            ->orderBy('risk_level', 'desc')
            ->orderBy('current_overdue_days', 'desc')
            ->limit(10)
            ->get();
        
        if ($highRiskClients->count() > 0) {
            $this->warn("\n⚠ Top High-Risk Clients:");
            
            $highRiskData = [];
            foreach ($highRiskClients as $client) {
                $highRiskData[] = [
                    $client->name,
                    strtoupper($client->risk_level),
                    $client->current_overdue_days . ' days',
                    '$' . number_format($client->outstanding_balance, 2)
                ];
            }
            
            $this->table(
                ['Client', 'Risk', 'Overdue Days', 'Balance'],
                $highRiskData
            );
        }
    }
}
