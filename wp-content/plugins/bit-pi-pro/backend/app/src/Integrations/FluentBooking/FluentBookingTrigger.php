<?php

namespace BitApps\PiPro\src\Integrations\FluentBooking;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use FluentBooking\App\Models\CalendarSlot;

final class FluentBookingTrigger
{
    private const FLUENT_BOOKING_PRO_INDEX = 'fluent-booking-pro/fluent-booking-pro.php';

    public function getEvents()
    {
        if (!is_plugin_active(self::FLUENT_BOOKING_PRO_INDEX)) {
            return Response::error(__('Fluent Booking Pro is not installed or activated', 'bit-pi'));
        }

        if (!class_exists('FluentBooking\App\Models\CalendarSlot')) {
            return Response::error(__('CalendarSlot class not found', 'bit-pi'));
        }
        $events = CalendarSlot::where('status', 'active')->get();
        if (!$events) {
            return Response::success('No events found');
        }
        $allEvents = [];
        foreach ($events as $event) {
            $allEvents[] = [
                'value' => $event->id,
                'label' => $event->title,
            ];
        }

        return Response::success($allEvents);
    }

    public static function handleFluentBookingScheduledSubmit($booking)
    {
        $eventId = $booking['event_id'];

        $flows = FlowService::exists('fluentBooking', 'bookingScheduled');

        if (empty($flows) || !$flows || empty($eventId)) {
            return;
        }

        $formData = self::handleBookingData($booking);

        IntegrationHelper::handleFlowForForm($flows, $formData, $eventId);
    }

    public static function handleFluentBookingCompletedSubmit($booking)
    {
        $eventId = $booking['event_id'];

        $flows = FlowService::exists('fluentBooking', 'bookingCompleted');

        if (empty($flows) || !$flows || empty($eventId)) {
            return;
        }

        $formData = self::handleBookingData($booking);

        IntegrationHelper::handleFlowForForm($flows, $formData, $eventId);
    }

    public static function handleFluentBookingCancelledSubmit($booking)
    {
        $eventId = $booking['event_id'];

        $flows = FlowService::exists('fluentBooking', 'bookingCancelled');

        if (empty($flows) || !$flows || empty($eventId)) {
            return;
        }

        $formData = self::handleBookingData($booking);

        IntegrationHelper::handleFlowForForm($flows, $formData, $eventId);
    }

    public static function handleBookingData($booking)
    {
        $customFieldsData = $booking->getCustomFormData(false);
        $bookingArray = $booking->toArray();

        unset($bookingArray['calendar_event']);

        $formData = [];

        foreach ($bookingArray as $key => $item) {
            if ($key === 'first_name') {
                $name[] = $item;
            } elseif ($key === 'last_name') {
                if (!empty($item)) {
                    $name[] = $item;
                }
            } elseif ($key === 'location_details') {
                $locationArrayKeys = array_keys($item);
                $formData['location_type'] = $item[$locationArrayKeys[0]];
                $formData['location_description'] = $item[$locationArrayKeys[1]];
            } else {
                $formData[$key] = $item;
            }
        }

        if ($name !== []) {
            $formData['name'] = implode(' ', $name);
        }

        $customData = [];

        if (!empty($customFieldsData)) {
            foreach ($customFieldsData as $key => $item) {
                $customData[$key] = \is_array($item) ? implode(',', $item) : $item;
            }
        }

        return array_merge($formData, $customData);
    }
}
