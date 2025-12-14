<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Models\PersonalAccessToken;

class TestRedisSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Redis connection and functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Redis Setup...');
        $this->newLine();

        // Test 1: Redis Connection
        $this->info('[1/4] Testing Redis connection...');
        try {
            Redis::ping();
            $this->info('✓ Redis connection: SUCCESS');
        } catch (\Exception $e) {
            $this->error('✗ Redis connection: FAILED - ' . $e->getMessage());
            return Command::FAILURE;
        }
        $this->newLine();

        // Test 2: Cache Driver
        $this->info('[2/4] Testing Cache driver...');
        try {
            Cache::put('test_key', 'test_value', 60);
            $value = Cache::get('test_key');
            
            if ($value === 'test_value') {
                $this->info('✓ Cache driver: SUCCESS');
                Cache::forget('test_key');
            } else {
                $this->error('✗ Cache driver: FAILED - Value mismatch');
            }
        } catch (\Exception $e) {
            $this->error('✗ Cache driver: FAILED - ' . $e->getMessage());
        }
        $this->newLine();

        // Test 3: Token Expiration
        $this->info('[3/4] Testing Token expiration setup...');
        try {
            $expiredCount = PersonalAccessToken::where('expires_at', '<', now())->count();
            $totalCount = PersonalAccessToken::count();
            
            $this->info("✓ Token expiration: SUCCESS");
            $this->info("  - Total tokens: {$totalCount}");
            $this->info("  - Expired tokens: {$expiredCount}");
        } catch (\Exception $e) {
            $this->error('✗ Token expiration: FAILED - ' . $e->getMessage());
        }
        $this->newLine();

        // Test 4: Redis Info
        $this->info('[4/4] Redis Information...');
        try {
            $info = Redis::info();
            $this->info('✓ Redis Version: ' . ($info['redis_version'] ?? 'N/A'));
            $this->info('✓ Connected Clients: ' . ($info['connected_clients'] ?? 'N/A'));
            $this->info('✓ Used Memory: ' . ($info['used_memory_human'] ?? 'N/A'));
            $this->info('✓ Total Keys: ' . Redis::dbSize());
        } catch (\Exception $e) {
            $this->error('✗ Redis info: FAILED - ' . $e->getMessage());
        }
        $this->newLine();

        $this->info('========================================');
        $this->info('  All tests completed!');
        $this->info('========================================');

        return Command::SUCCESS;
    }
}
