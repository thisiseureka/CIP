<?php

namespace BitApps\PiPro\src\Integrations\EventsCalendar;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class EventsCalendarTrigger
{
    private static $pluginPath = 'the-events-calendar/the-events-calendar.php';

    public static function handleAttendsEvent($attendeeId)
    {
        if (empty($attendeeId)) {
            return;
        }

        if (!\function_exists('tribe_tickets_get_attendees')) {
            return;
        }

        $attendees = tribe_tickets_get_attendees($attendeeId, 'rsvp_order');

        $attendee = [];

        if (isset($attendees[0])) {
            $attendee = $attendees[0];
        }

        if (empty($attendee)) {
            return;
        }

        $eventId = $attendee['event_id'];

        $flows = FlowService::exists('eventsCalendar', 'attendEvent');

        if (empty($flows) || !$flows) {
            return;
        }

        $data = EventsCalendarHelper::formatAttendsEventData($attendee);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data, $eventId, 'event-id');
        }
    }

    public static function handleAttendeeRegistered($attendeeId)
    {
        if (empty($attendeeId)) {
            return;
        }

        if (\is_object($attendeeId)) {
            $attendeeId = $attendeeId->ID;
        }

        if (!\function_exists('tribe_tickets_get_attendees')) {
            return;
        }

        $attendees = tribe_tickets_get_attendees($attendeeId);
        $attendee = [];

        if (isset($attendees[0])) {
            $attendee = $attendees[0];
        }

        if (empty($attendee)) {
            return;
        }

        $eventId = $attendee['event_id'];

        $flows = FlowService::exists('eventsCalendar', 'attendeeRegistered');

        if (empty($flows) || !$flows) {
            return;
        }

        $data = EventsCalendarHelper::formatAttendsEventData($attendee);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data, $eventId, 'event-id');
        }
    }

    public static function handleNewAttendee($productId, $orderId)
    {
        if (empty($productId) || empty($orderId)) {
            return;
        }

        if (!class_exists('Tribe__Tickets__Main')) {
            return;
        }

        $event = tribe_events_get_ticket_event($productId);

        $eventId = $event->ID;

        if (empty($eventId)) {
            return;
        }

        $flows = FlowService::exists('eventsCalendar', 'newAttendee');

        if (empty($flows) || !$flows) {
            return;
        }

        $data = EventsCalendarHelper::formatNewAttendeeData($event, $orderId);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data, $eventId, 'event-id');
        }
    }

    public static function handleAttendeeRegisteredWc($attendee, $attendeeData, $ticket)
    {
        if (empty($attendeeData) || empty($ticket)) {
            return;
        }

        if (!class_exists('Tribe__Tickets__Main')) {
            return;
        }

        $orderId = $attendeeData['order_id'];
        $productId = $ticket->ID;
        $event = tribe_events_get_ticket_event($productId);
        $eventId = $event->ID;

        if (empty($eventId)) {
            return;
        }

        $flows = FlowService::exists('eventsCalendar', 'attendeeRegisteredWc');

        if (empty($flows) || !$flows) {
            return;
        }

        $data = EventsCalendarHelper::formatNewAttendeeData($event, $orderId);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data, $eventId, 'event-id');
        }
    }
}
