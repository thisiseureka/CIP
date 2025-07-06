<?php

namespace BitApps\PiPro\src\Integrations\Amelia;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class AmeliaTrigger
{
    public static function appointmentAdded($appointment, $service, $paymentData)
    {
        return self::execute(
            'appointmentAdded',
            [
                'appointment'  => $appointment,
                'service'      => $service,
                'payment_data' => $paymentData
            ]
        );
    }

    public static function statusUpdated($appointment, $status)
    {
        return self::execute('statusUpdated', ['appointment' => $appointment, 'status' => $status]);
    }

    public static function appointmentCancelled($appointment, $status)
    {
        if (empty($status) || $status !== 'canceled') {
            return;
        }

        return self::execute('appointmentCancelled', ['appointment' => $appointment, 'status' => $status]);
    }

    public static function appointmentRejected($appointment, $status)
    {
        if (empty($status) || $status !== 'rejected') {
            return;
        }

        return self::execute('appointmentRejected', ['appointment' => $appointment, 'status' => $status]);
    }

    public static function bookingAdded($appointment)
    {
        return self::execute('bookingAdded', $appointment);
    }

    public static function bookingCancelled($booking)
    {
        return self::execute('bookingCancelled', $booking);
    }

    public static function bookingRescheduled($oldAppointment, $booking, $bookingStart)
    {
        return self::execute(
            'bookingRescheduled',
            [
                'old_appointment' => $oldAppointment,
                'booking'         => $booking,
                'booking_start'   => $bookingStart
            ]
        );
    }

    public static function eventAdded($event)
    {
        return self::execute('eventAdded', $event);
    }

    public static function eventUpdated($event)
    {
        return self::execute('eventUpdated', $event);
    }

    public static function eventBookingAdded($booking, $reservation)
    {
        return self::execute('eventBookingAdded', ['booking' => $booking, 'reservation' => $reservation]);
    }

    public static function eventBookingUpdated($booking, $oldBooking)
    {
        return self::execute('eventBookingUpdated', ['booking' => $booking, 'old_booking' => $oldBooking]);
    }

    public static function eventBookingDeleted($booking, $event)
    {
        return self::execute('eventBookingDeleted', ['booking' => $booking, 'event' => $event]);
    }

    private static function execute($machineSlug, $data)
    {
        $flows = FlowService::exists('amelia', $machineSlug);

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    // TODO:: need to implement
    private static function isPluginInstalled()
    {
        return class_exists('\AmeliaBooking\Plugin');
    }
}
