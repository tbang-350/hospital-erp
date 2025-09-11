<?php

namespace App\Console\Commands;

use App\Models\InventoryItem;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckLowStockItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:check-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for low stock and expired inventory items and create notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking inventory for low stock and expired items...');
        
        // Check for low stock items
        $lowStockItems = InventoryItem::where('quantity', '<=', 10)
            ->where('quantity', '>', 0)
            ->get();
            
        foreach ($lowStockItems as $item) {
            NotificationService::createLowStockNotification($item);
        }
        
        $this->info('Found ' . $lowStockItems->count() . ' low stock items.');
        
        // Check for expired items
        $expiredItems = InventoryItem::whereNotNull('expiry_date')
            ->where('expiry_date', '<=', Carbon::now())
            ->get();
            
        foreach ($expiredItems as $item) {
            NotificationService::createExpiredItemNotification($item);
        }
        
        $this->info('Found ' . $expiredItems->count() . ' expired items.');
        
        // Check for items expiring soon (within 7 days)
        $expiringSoonItems = InventoryItem::whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [Carbon::now(), Carbon::now()->addDays(7)])
            ->get();
            
        foreach ($expiringSoonItems as $item) {
            NotificationService::createExpiringSoonNotification($item);
        }
        
        $this->info('Found ' . $expiringSoonItems->count() . ' items expiring soon.');
        
        $this->info('Inventory check completed successfully!');
        
        return Command::SUCCESS;
    }
}
