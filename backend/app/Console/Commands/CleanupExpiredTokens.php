<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PersonalAccessToken;

class CleanupExpiredTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove expired authentication tokens from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting token cleanup...');

        // Delete expired tokens
        $deletedCount = PersonalAccessToken::where('expires_at', '<', now())->delete();

        $this->info("Cleanup completed! Deleted {$deletedCount} expired token(s).");

        return Command::SUCCESS;
    }
}
