<?php

namespace App\Modules\Ingredient\Core\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\Ingredient\Domain\Entities\Inventory;

class IngredientStockAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Inventory $inventory)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line(
                $this->getMessageBody()
            )
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function getMessageBody(): string
    {
        return sprintf('Ingredient %s stock level under threshold with percentage amount of %%%d', $this->inventory->ingredient->name, $this->inventory->current_level_percentage);
    }
}
