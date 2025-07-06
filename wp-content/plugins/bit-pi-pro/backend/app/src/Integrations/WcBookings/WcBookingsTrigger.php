<?php

namespace BitApps\PiPro\src\Integrations\WcBookings;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class WcBookingsTrigger
{
    public static function handleNewBooking($bookingId)
    {
        self::processBookingEvent($bookingId, 'newBooking');
    }

    public static function handleConfirmBooking($bookingId)
    {
        self::processBookingEvent($bookingId, 'bookingConfirmed');
    }

    public static function handleUnpaidToPaidBooking($bookingId)
    {
        self::processBookingEvent($bookingId, 'bookingUnpaidToPaid');
    }

    public static function handleBookingStatusChanged($oldStatus, $newStatus, $bookingId, $booking)
    {
        $wasInCart = 'was-in-cart';

        if (
            !self::isValidBookingRequest($bookingId)
            || $newStatus === $wasInCart
            || $oldStatus === $wasInCart
            || empty($flows = FlowService::exists('wcBookings', 'bookingStatusChanged'))
        ) {
            return;
        }

        $flows = WcBookingsHelper::getFilteredFlows($flows, 'booking_status_changed', $newStatus);
        if (empty($flows)) {
            return;
        }

        $data = array_merge(
            WcBookingsHelper::mapBookingData($booking, $bookingId),
            ['new_status' => $newStatus, 'old_status' => $oldStatus]
        );

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    private static function processBookingEvent($bookingId, $eventType)
    {
        if (!self::isValidBookingRequest($bookingId) || empty($flows = FlowService::exists('wcBookings', $eventType))) {
            return;
        }

        $data = WcBookingsHelper::mapBookingData(get_wc_booking($bookingId), $bookingId);
        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    private static function isValidBookingRequest($bookingId)
    {
        return $bookingId !== ''
            && class_exists('WooCommerce')
            && self::isActivate();
    }

    // private function isPluginActivated()
    // {
    //     if (!self::isActivate()) {
    //         return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'WooCommerce Bookings'));
    //     }
    // }

    private static function isActivate()
    {
        return class_exists('\WC_Booking');
    }
}
