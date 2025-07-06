<?php

namespace BitApps\PiPro\src\Integrations\Eventin;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class EventinHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'eventCreated' => [
                'hook'          => 'eventin_event_created',
                'callback'      => [EventinTrigger::class, 'handleEventCreated'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'eventUpdated' => [
                'hook'          => 'eventin_event_updated',
                'callback'      => [EventinTrigger::class, 'handleEventUpdated'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'eventDeleted' => [
                'hook'          => 'eventin_event_deleted',
                'callback'      => [EventinTrigger::class, 'handleEventDeleted'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'speakerCreated' => [
                'hook'          => 'eventin_speaker_created',
                'callback'      => [EventinTrigger::class, 'handleSpeakerCreated'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'speakerUpdated' => [
                'hook'          => 'eventin_speaker_update',
                'callback'      => [EventinTrigger::class, 'handleSpeakerUpdated'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'speakerDeleted' => [
                'hook'          => 'eventin_speaker_deleted',
                'callback'      => [EventinTrigger::class, 'handleSpeakerDeleted'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'attendeeUpdated' => [
                'hook'          => 'eventin_attendee_updated',
                'callback'      => [EventinTrigger::class, 'handleAttendeeUpdated'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'attendeeDeleted' => [
                'hook'          => 'eventin_attendee_deleted',
                'callback'      => [EventinTrigger::class, 'handleAttendeeDeleted'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'orderCreate' => [
                'hook'          => 'eventin_after_order_create',
                'callback'      => [EventinTrigger::class, 'handleOrderCreate'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'orderDeleted' => [
                'hook'          => 'eventin_order_deleted',
                'callback'      => [EventinTrigger::class, 'handleOrderDeleted'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'scheduleDeleted' => [
                'hook'          => 'eventin_schedule_deleted',
                'callback'      => [EventinTrigger::class, 'handleScheduleDeleted'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
        ];
    }
}
