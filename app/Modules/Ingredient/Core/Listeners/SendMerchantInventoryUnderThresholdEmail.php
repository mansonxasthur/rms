<?php

namespace App\Modules\Ingredient\Core\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Modules\Ingredient\Domain\Entities\Inventory;
use App\Modules\Ingredient\Core\Events\InventoryUnderThreshold;
use App\Modules\Ingredient\Domain\Repositories\StockAlertRepository;
use App\Modules\Ingredient\Core\Notifications\IngredientStockAlertNotification;

class SendMerchantInventoryUnderThresholdEmail implements ShouldQueue
{
    public function __construct(protected StockAlertRepository $stockAlertRepository)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(InventoryUnderThreshold $event): void
    {
        if (! $this->stockAlertRepository->hasActiveAlert($event->inventory)) {
            $this->notifyMerchantForUnderThresholdInventory($event->inventory);
            $this->stockAlertRepository->createAlert($event->inventory);
        }
    }

    private function notifyMerchantForUnderThresholdInventory(Inventory $inventory): void
    {
        Notification::route('mail', config('app.merchant_email'))
            ->notify(
                new IngredientStockAlertNotification($inventory)
            );
    }
}
