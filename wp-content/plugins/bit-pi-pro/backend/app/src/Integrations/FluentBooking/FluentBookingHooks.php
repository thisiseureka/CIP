<?php

namespace BitApps\PiPro\src\Integrations\FluentBooking;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class FluentBookingHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'bookingScheduled' => [
                'hook'          => 'fluent_booking/after_booking_scheduled',
                'callback'      => [FluentBookingTrigger::class, 'handleFluentBookingScheduledSubmit'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'bookingCompleted' => [
                'hook'          => 'fluent_booking/booking_schedule_completed',
                'callback'      => [FluentBookingTrigger::class, 'handleFluentBookingCompletedSubmit'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'bookingCancelled' => [
                'hook'          => 'fluent_booking/booking_schedule_cancelled',
                'callback'      => [FluentBookingTrigger::class, 'handleFluentBookingCancelledSubmit'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
        ];
    }
}
