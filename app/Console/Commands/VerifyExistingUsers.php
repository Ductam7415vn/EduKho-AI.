<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class VerifyExistingUsers extends Command
{
    protected $signature = 'users:verify-existing {email?}';
    protected $description = 'Verify email for existing users (useful after adding email verification)';

    public function handle()
    {
        $email = $this->argument('email');
        
        if ($email) {
            // Verify specific user
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("User with email {$email} not found!");
                return 1;
            }
            
            $user->email_verified_at = now();
            $user->save();
            
            $this->info("✅ Email verified for user: {$user->email}");
        } else {
            // Verify all users without verification
            $unverifiedUsers = User::whereNull('email_verified_at')->get();
            
            if ($unverifiedUsers->isEmpty()) {
                $this->info("All users already have verified emails!");
                return 0;
            }
            
            $this->info("Found {$unverifiedUsers->count()} unverified users.");
            
            if ($this->confirm('Do you want to verify all existing users?')) {
                foreach ($unverifiedUsers as $user) {
                    $user->email_verified_at = now();
                    $user->save();
                    $this->info("✅ Verified: {$user->email}");
                }
                
                $this->info("\n✅ All existing users have been verified!");
            }
        }
        
        return 0;
    }
}