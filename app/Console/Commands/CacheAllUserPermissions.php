<?php

namespace App\Console\Commands;

use App\User;
use App\Support\AuthenticatedUserCache;
use App\Support\UserPermissionsCache;
use Illuminate\Console\Command;

class CacheAllUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache permissions for all users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Caching permissions for all users...');

        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            UserPermissionsCache::prime($user->id);
            AuthenticatedUserCache::prime($user->id);
            $count++;
        }

        $this->info("Successfully cached permissions for {$count} users.");

        return 0;
    }
}
