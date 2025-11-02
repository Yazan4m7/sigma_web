<?php

namespace App\Console\Commands;

use App\User;
use App\UserPermission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

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
            $permissions = UserPermission::where('user_id', $user->id)->get();
            Cache::forever('user' . $user->id, $permissions);
            $count++;
        }

        $this->info("Successfully cached permissions for {$count} users.");

        return 0;
    }
}
