<?php

namespace App\Providers;

use App\Events\ReservationCreated;
use App\Events\ReservationDeleted;
use App\Events\ReservationUpdated;
use App\Listeners\DeleteQrCodeAndReceipt;
use App\Listeners\GenerateQrCodeAndReceipt;
use App\Listeners\UpdateReceipt;
use App\Listeners\SendMailReservationBookedStatus;
use App\Listeners\SendMailReservationCancelledStatus;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ReservationCreated::class => [
            GenerateQrCodeAndReceipt::class,
            SendMailReservationBookedStatus::class
        ],
        ReservationUpdated::class => [
            UpdateReceipt::class,
        ],
        ReservationDeleted::class => [
            DeleteQrCodeAndReceipt::class,
            SendMailReservationCancelledStatus::class
        ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
