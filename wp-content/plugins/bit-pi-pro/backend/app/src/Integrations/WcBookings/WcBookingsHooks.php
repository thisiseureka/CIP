<?php

namespace BitApps\PiPro\src\Integrations\WcBookings;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class WcBookingsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'newBooking' => [
                'hook'          => 'woocommerce_new_booking',
                'callback'      => [WcBookingsTrigger::class, 'handleNewBooking'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'bookingConfirmed' => [
                'hook'          => 'woocommerce_booking_confirmed',
                'callback'      => [WcBookingsTrigger::class, 'handleConfirmBooking'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'bookingUnpaidToPaid' => [
                'hook'          => 'woocommerce_booking_unpaid_to_paid',
                'callback'      => [WcBookingsTrigger::class, 'handleUnpaidToPaidBooking'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'bookingStatusChanged' => [
                'hook'          => 'woocommerce_booking_status_changed',
                'callback'      => [WcBookingsTrigger::class, 'handleBookingStatusChanged'],
                'priority'      => 10,
                'accepted_args' => 4,
            ],
        ];
    }
}
