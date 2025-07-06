<?php

namespace BitApps\PiPro\src\Integrations\Amelia;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class AmeliaHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'appointmentAdded' => [
                'hook'          => 'amelia_before_appointment_added',
                'callback'      => [AmeliaTrigger::class, 'appointmentAdded'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'statusUpdate' => [
                'hook'          => 'amelia_before_appointment_status_updated',
                'callback'      => [AmeliaTrigger::class, 'statusUpdate'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'appointmentCancelled' => [
                'hook'          => 'amelia_before_appointment_status_updated',
                'callback'      => [AmeliaTrigger::class, 'appointmentCancelled'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'appointmentRejected' => [
                'hook'          => 'amelia_before_appointment_status_updated',
                'callback'      => [AmeliaTrigger::class, 'appointmentRejected'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'bookingAdded' => [
                'hook'          => 'amelia_before_booking_added',
                'callback'      => [AmeliaTrigger::class, 'bookingAdded'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'bookingCancelled' => [
                'hook'          => 'amelia_after_booking_canceled',
                'callback'      => [AmeliaTrigger::class, 'bookingCancelled'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'bookingRescheduled' => [
                'hook'          => 'amelia_before_booking_rescheduled',
                'callback'      => [AmeliaTrigger::class, 'bookingRescheduled'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'eventAdded' => [
                'hook'          => 'amelia_before_event_added',
                'callback'      => [AmeliaTrigger::class, 'eventAdded'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'eventUpdated' => [
                'hook'          => 'amelia_after_event_updated',
                'callback'      => [AmeliaTrigger::class, 'eventUpdated'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'eventBookingAdded' => [
                'hook'          => 'amelia_before_event_booking_saved',
                'callback'      => [AmeliaTrigger::class, 'eventBookingAdded'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'eventBookingUpdated' => [
                'hook'          => 'amelia_after_event_booking_updated',
                'callback'      => [AmeliaTrigger::class, 'eventBookingUpdated'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'eventBookingDeleted' => [
                'hook'          => 'amelia_before_event_booking_deleted',
                'callback'      => [AmeliaTrigger::class, 'eventBookingDeleted'],
                'priority'      => 10,
                'accepted_args' => 2,
            ]
        ];
    }
}
