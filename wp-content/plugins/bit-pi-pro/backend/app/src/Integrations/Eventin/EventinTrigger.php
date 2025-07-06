<?php

namespace BitApps\PiPro\src\Integrations\Eventin;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class EventinTrigger
{
    private static $pluginPath = 'wp-event-solution/eventin.php';

    public static function handleEventCreated($event, $request)
    {
        if (empty($event) || empty($request)) {
            return;
        }

        $flows = FlowService::exists('eventin', 'eventCreated');

        if (empty($flows) || !$flows) {
            return;
        }

        $data = EventinHelper::formatEventCreatedData($request);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleEventUpdated($event, $request)
    {
        if (empty($event) || empty($request)) {
            return;
        }

        $flows = FlowService::exists('eventin', 'eventUpdated');

        if (empty($flows) || !$flows) {
            return;
        }

        $data = EventinHelper::formatEventUpdatedData($request);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleEventDeleted($eventId)
    {
        if (empty($eventId)) {
            return;
        }

        $flows = FlowService::exists('eventin', 'eventDeleted');

        if (empty($flows) || !$flows) {
            return;
        }

        $data = ['event_id' => $eventId];

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    public static function handleSpeakerCreated($created, $request)
    {
        if (empty($created) || empty($request)) {
            return;
        }

        $flows = FlowService::exists('eventin', 'speakerCreated');

        if (empty($flows) || !$flows) {
            return;
        }

        $data = EventinHelper::formatSpeakerCreatedData($request);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleSpeakerUpdated($speaker, $request)
    {
        if (empty($speaker) || empty($request)) {
            return;
        }

        $flows = FlowService::exists('eventin', 'speakerUpdated');

        if (empty($flows) || !$flows) {
            return;
        }

        $data = EventinHelper::formatSpeakerUpdatedData($request);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleSpeakerDeleted($speakerId)
    {
        if (empty($speakerId)) {
            return;
        }

        $flows = FlowService::exists('eventin', 'speakerDeleted');

        if (empty($flows) || !$flows) {
            return;
        }

        $data = ['id' => $speakerId];

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    public static function handleAttendeeUpdated($attendee, $request)
    {
        if (empty($attendee) || empty($request)) {
            return;
        }

        $flows = FlowService::exists('eventin', 'attendeeUpdated');

        if (empty($flows) || !$flows) {
            return;
        }

        $data = EventinHelper::formatAttendeeUpdatedData($request);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleAttendeeDeleted($attendeeId)
    {
        if (empty($attendeeId)) {
            return;
        }

        $flows = FlowService::exists('eventin', 'attendeeDeleted');

        if (empty($flows) || !$flows) {
            return;
        }

        $data = ['id' => $attendeeId];

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    public static function handleOrderCreate($order)
    {
        if (empty($order)) {
            return;
        }

        $flows = FlowService::exists('eventin', 'orderCreate');

        if (empty($flows) || !$flows) {
            return;
        }

        $data = EventinHelper::formatOrderCreateData($order);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleOrderDeleted($orderId)
    {
        if (empty($orderId)) {
            return;
        }

        $flows = FlowService::exists('eventin', 'orderDeleted');

        if (empty($flows) || !$flows) {
            return;
        }

        $data = ['id' => $orderId];

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    public static function handleScheduleDeleted($scheduleId)
    {
        if (empty($scheduleId)) {
            return;
        }

        $flows = FlowService::exists('eventin', 'scheduleDeleted');

        if (empty($flows) || !$flows) {
            return;
        }

        $data = ['id' => $scheduleId];

        IntegrationHelper::handleFlowForForm($flows, $data);
    }
}
