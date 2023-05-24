<?php

namespace App\Jobs;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class ModuleMigrate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private TenantWithDatabase $tenant
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->tenant->run(
            function (Tenant $tenant) {
                foreach ($tenant->modules as $module) {
                    Artisan::call('migrate', [
                        '--force' => true, // This needs to be true to run migrations in production.
                        '--path' => [base_path("modules/{$module::$name}/database/migrations")],
                        '--realpath' => true,
                    ]);
                }
            }
        );
    }
}
