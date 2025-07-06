<?php

namespace BitApps\PiPro\src\Integrations\EventsCalendar;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class EventsCalendarHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'attendEvent' => [
                'hook'          => ['event_tickets_checkin', 'eddtickets_checkin', 'rsvp_checkin', 'wootickets_checkin'],
                'callback'      => [EventsCalendarTrigger::class, 'handleAttendsEvent'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'attendeeRegistered' => [
                'hook' => [
                    'event_tickets_rsvp_attendee_created',
                    'event_ticket_woo_attendee_created',
                    'event_ticket_edd_attendee_created',
                    'event_tickets_tpp_attendee_created',
                    'event_tickets_tpp_attendee_updated',
                    'tec_tickets_commerce_attendee_after_create',
                ],
                'callback'      => [EventsCalendarTrigger::class, 'handleAttendeeRegistered'],
                'priority'      => 10,
                'accepted_args' => 5,
            ],
            'newAttendee' => [
                'hook'          => ['event_tickets_rsvp_tickets_generated_for_product', 'event_tickets_woocommerce_tickets_generated_for_product', 'event_tickets_tpp_tickets_generated_for_product'],
                'callback'      => [EventsCalendarTrigger::class, 'handleNewAttendee'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'attendeeRegisteredWc' => [
                'hook'          => 'tribe_tickets_attendee_repository_create_attendee_for_ticket_after_create',
                'callback'      => [EventsCalendarTrigger::class, 'handleAttendeeRegisteredWc'],
                'priority'      => 10,
                'accepted_args' => 4,
            ],
        ];
    }
}
