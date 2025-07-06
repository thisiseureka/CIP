<?php

namespace BitApps\PiPro\src\Integrations\Eventin;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


class EventinHelper
{
    public static function getFields($data)
    {
        $id = \is_string($data) ? $data : $data->id;

        if (empty($id)) {
            return;
        }

        $fields = [];

        if ($id === 'eventin-1') {
            $fields = self::eventFields();
        } elseif ($id === 'eventin-2') {
            $fields = self::eventFields('eventUpdated');
        } elseif ($id === 'eventin-3') {
            $fields = self::eventDeleteFields();
        } elseif ($id === 'eventin-4') {
            $fields = self::speakerFields();
        } elseif ($id === 'eventin-5') {
            $fields = self::speakerFields('speakerUpdated');
        } elseif ($id === 'eventin-6') {
            $fields = self::speakerDeleteFields();
        } elseif ($id === 'eventin-7') {
            $fields = self::attendeeFields();
        } elseif ($id === 'eventin-8') {
            $fields = self::attendeeDeleteFields();
        } elseif ($id === 'eventin-9') {
            $fields = self::orderCreateFields();
        } elseif ($id === 'eventin-10') {
            $fields = self::orderDeleteFields();
        } elseif ($id === 'eventin-11') {
            $fields = self::scheduleDeleteFields();
        }

        return $fields;
    }

    public static function formatEventCreatedData($request)
    {
        if (empty($request)) {
            return false;
        }

        return self::eventData($request);
    }

    public static function formatEventUpdatedData($request)
    {
        if (empty($request)) {
            return false;
        }

        return self::eventData($request, 'eventUpdated');
    }

    public static function formatSpeakerCreatedData($request)
    {
        if (empty($request)) {
            return false;
        }

        return self::speakerData($request);
    }

    public static function formatSpeakerUpdatedData($request)
    {
        if (empty($request)) {
            return false;
        }

        return self::speakerData($request, 'speakerUpdated');
    }

    public static function formatAttendeeUpdatedData($request)
    {
        if (empty($request)) {
            return false;
        }

        $requestBody = json_decode($request->get_body(), true);

        $data['attendee_id'] = $requestBody['id'] ?? '';
        $data['attendee_name'] = $requestBody['etn_name'] ?? '';
        $data['attendee_email'] = $requestBody['etn_email'] ?? '';
        $data['attendee_phone'] = $requestBody['etn_phone'] ?? '';
        $data['ticket_id'] = $requestBody['etn_unique_ticket_id'] ?? '';
        $data['ticket_name'] = $requestBody['ticket_name'] ?? '';
        $data['ticket_slug'] = $requestBody['ticket_slug'] ?? '';
        $data['ticket_status'] = $requestBody['etn_attendeee_ticket_status'] ?? '';
        $data['ticket_price'] = $requestBody['etn_ticket_price'] ?? '';
        $data['payment_status'] = $requestBody['etn_status'] ?? '';
        $data['event_id'] = $requestBody['etn_event_id'] ?? '';
        $data['event_name'] = $requestBody['event_name'] ?? '';
        $data['order_id'] = $requestBody['eventin_order_id'] ?? '';

        return $data;
    }

    public static function formatOrderCreateData($order)
    {
        if (empty($order)) {
            return false;
        }

        $orderId = $order->id;

        $data['order_id'] = $orderId;
        $data['customer_fname'] = get_post_meta($orderId, 'customer_fname', true);
        $data['customer_lname'] = get_post_meta($orderId, 'customer_lname', true);
        $data['customer_email'] = get_post_meta($orderId, 'customer_email', true);
        $data['status'] = get_post_meta($orderId, 'status', true);

        $tickets = get_post_meta($orderId, 'tickets', true);

        $data['ticket_slug'] = $tickets[0]['ticket_slug'] ?? '';
        $data['ticket_quantity'] = $tickets[0]['ticket_quantity'] ?? '';
        $data['total_price'] = get_post_meta($orderId, 'total_price', true);
        $data['date_time'] = get_post_meta($orderId, 'date_time', true);
        $data['event_id'] = get_post_meta($orderId, 'event_id', true);

        if (!empty($data['event_id'])) {
            $event = get_post($data['event_id'], ARRAY_A);
            $eventName = $event['post_title'];
        }

        $data['event_name'] = $eventName ?? '';

        return $data;
    }

    public static function flowFilter($flows, $key, $value)
    {
        $filteredFlows = [];

        if (\is_array($flows) || \is_object($flows)) {
            foreach ($flows as $flow) {
                if (\is_string($flow->flow_details)) {
                    $flow->flow_details = json_decode($flow->flow_details);
                }

                if (
                    !isset($flow->flow_details->{$key})
                            || $flow->flow_details->{$key} === 'any'
                            || $flow->flow_details->{$key} == $value
                            || $flow->flow_details->{$key} === ''
                            || $value === 'empty_terms'
                ) {
                    $filteredFlows[] = $flow;
                }
            }
        }

        return $filteredFlows;
    }

    private static function eventData($request, $type = 'eventCreated')
    {
        $eventBody = json_decode($request->get_body(), true);

        if (empty($eventBody)) {
            return false;
        }

        $data['title'] = $eventBody['title'] ?? '';
        $data['start_date'] = $eventBody['start_date'] ?? '';
        $data['end_date'] = $eventBody['end_date'] ?? '';
        $data['end_date'] = $eventBody['end_date'] ?? '';
        $data['start_time'] = $eventBody['start_time'] ?? '';
        $data['end_time'] = $eventBody['end_time'] ?? '';
        $data['event_type'] = $eventBody['event_type'] ?? '';
        $address = $integration = $customUrl = '';

        if ($eventBody['event_type'] === 'offline') {
            $address = $eventBody['location']['address'] ?? '';
        }

        if ($eventBody['event_type'] === 'online') {
            if (isset($eventBody['location']['integration'])) {
                $integration = ucwords(str_replace('_', ' ', $eventBody['location']['integration']));
            }

            if (isset($eventBody['location']['custom_url'])) {
                $customUrl = $eventBody['location']['custom_url'];
            }
        }

        $data['address'] = $address;
        $data['integration'] = $integration;
        $data['custom_url'] = $customUrl;
        $data['timezone'] = $eventBody['timezone'] ?? '';
        $data['recurring_enabled'] = $eventBody['recurring_enabled'] ?? '';

        if ($type === 'eventUpdated') {
            $data['link'] = $eventBody['link'] ?? '';
            $data['status'] = $eventBody['status'] ?? '';
        }

        return $data;
    }

    private static function speakerData($request, $type = 'speakerCreated')
    {
        $requestBody = json_decode($request->get_body(), true);

        $data['name'] = $requestBody['name'] ?? '';

        if (isset($requestBody['category'])) {
            if (\is_array($requestBody['category'])) {
                $data['category'] = implode(',', $requestBody['category']);
            } else {
                $data['category'] = $requestBody['category'];
            }
        } else {
            $data['category'] = '';
        }

        $data['designation'] = $requestBody['designation'] ?? '';

        if (isset($requestBody['speaker_group'])) {
            if (\is_array($requestBody['speaker_group'])) {
                $data['speaker_group'] = implode(',', $requestBody['speaker_group']);
            } else {
                $data['speaker_group'] = $requestBody['speaker_group'];
            }
        } else {
            $data['speaker_group'] = '';
        }

        $data['email'] = $requestBody['email'] ?? '';
        $data['company_name'] = $requestBody['company_name'] ?? '';
        $data['company_url'] = $requestBody['company_url'] ?? '';
        $data['summary'] = $requestBody['summary'] ?? '';
        $data['image'] = $requestBody['image'] ?? '';
        $data['company_logo'] = $requestBody['company_logo'] ?? '';

        if ($type === 'speakerUpdated') {
            $data['id'] = $requestBody['id'] ?? '';
            $data['author_url'] = $requestBody['author_url'] ?? '';
        }

        return $data;
    }

    private static function eventFields($type = 'eventCreated', array $unsetFields = [])
    {
        $fields = [
            [
                'name'  => 'title',
                'type'  => 'text',
                'label' => __('Event Title', 'bit-pi'),
            ],
            [
                'name'  => 'start_date',
                'type'  => 'text',
                'label' => __('Start Date', 'bit-pi'),
            ],
            [
                'name'  => 'end_date',
                'type'  => 'text',
                'label' => __('End Date', 'bit-pi'),
            ],
            [
                'name'  => 'end_date',
                'type'  => 'text',
                'label' => __('End Date', 'bit-pi'),
            ],
            [
                'name'  => 'start_time',
                'type'  => 'text',
                'label' => __('Start Time', 'bit-pi'),
            ],
            [
                'name'  => 'end_time',
                'type'  => 'text',
                'label' => __('End Time', 'bit-pi'),
            ],
            [
                'name'  => 'event_type',
                'type'  => 'text',
                'label' => __('Event Type', 'bit-pi'),
            ],
            [
                'name'  => 'address',
                'type'  => 'text',
                'label' => __('Address', 'bit-pi'),
            ],
            [
                'name'  => 'integration',
                'type'  => 'text',
                'label' => __('Integration Name', 'bit-pi'),
            ],
            [
                'name'  => 'custom_url',
                'type'  => 'text',
                'label' => __('Custom URL', 'bit-pi'),
            ],
            [
                'name'  => 'timezone',
                'type'  => 'text',
                'label' => __('Time Zone', 'bit-pi'),
            ],
            [
                'name'  => 'recurring_enabled',
                'type'  => 'text',
                'label' => __('Recurring Enabled', 'bit-pi'),
            ],
        ];

        if ($type === 'eventUpdated') {
            $eventUpdatedFields = [
                [
                    'name'  => 'link',
                    'type'  => 'text',
                    'label' => __('Event Link', 'bit-pi'),
                ],
                [
                    'name'  => 'status',
                    'type'  => 'text',
                    'label' => __('Event Status', 'bit-pi'),
                ],
            ];

            $fields = array_merge($fields, $eventUpdatedFields);
        }

        foreach ($unsetFields as $fieldKey) {
            unset($fields[$fieldKey]);
        }

        return $fields;
    }

    private static function eventDeleteFields()
    {
        return [
            [
                'name'  => 'event_id',
                'type'  => 'text',
                'label' => __('Event Id', 'bit-pi'),
            ],
        ];
    }

    private static function speakerFields($type = 'speakerCreated', array $unsetFields = [])
    {
        $fields = [
            [
                'name'  => 'name',
                'type'  => 'text',
                'label' => __('Full Name', 'bit-pi'),
            ],
            [
                'name'  => 'category',
                'type'  => 'text',
                'label' => __('Role', 'bit-pi'),
            ],
            [
                'name'  => 'designation',
                'type'  => 'text',
                'label' => __('Job Title', 'bit-pi'),
            ],
            [
                'name'  => 'speaker_group',
                'type'  => 'text',
                'label' => __('Speaker Group', 'bit-pi'),
            ],
            [
                'name'  => 'email',
                'type'  => 'email',
                'label' => __('Email Address', 'bit-pi'),
            ],
            [
                'name'  => 'company_name',
                'type'  => 'text',
                'label' => __('Company Name', 'bit-pi'),
            ],
            [
                'name'  => 'company_url',
                'type'  => 'text',
                'label' => __('Company URL', 'bit-pi'),
            ],
            [
                'name'  => 'summary',
                'type'  => 'text',
                'label' => __('Speaker Bio', 'bit-pi'),
            ],
            [
                'name'  => 'image',
                'type'  => 'text',
                'label' => __('Speaker Photo', 'bit-pi'),
            ],
            [
                'name'  => 'company_logo',
                'type'  => 'text',
                'label' => __('Company logo', 'bit-pi'),
            ],
        ];

        if ($type === 'speakerUpdated') {
            $speakerUpdatedFields = [
                [
                    'name'  => 'id',
                    'type'  => 'text',
                    'label' => __('Speaker Id', 'bit-pi'),
                ],
                [
                    'name'  => 'author_url',
                    'type'  => 'text',
                    'label' => __('Author (Speaker) URL', 'bit-pi'),
                ],
            ];

            $fields = array_merge($speakerUpdatedFields, $fields);
        }

        foreach ($unsetFields as $fieldKey) {
            unset($fields[$fieldKey]);
        }

        return $fields;
    }

    private static function speakerDeleteFields()
    {
        return [
            [
                'name'  => 'id',
                'type'  => 'text',
                'label' => __('Speaker Id', 'bit-pi'),
            ],
        ];
    }

    private static function attendeeFields(array $unsetFields = [])
    {
        $fields = [
            [
                'name'  => 'attendee_id',
                'type'  => 'text',
                'label' => __('Attendee ID', 'bit-pi'),
            ],
            [
                'name'  => 'attendee_name',
                'type'  => 'text',
                'label' => __('Attendee Name', 'bit-pi'),
            ],
            [
                'name'  => 'attendee_email',
                'type'  => 'text',
                'label' => __('Attendee Email', 'bit-pi'),
            ],
            [
                'name'  => 'attendee_phone',
                'type'  => 'text',
                'label' => __('Attendee Phone', 'bit-pi'),
            ],
            [
                'name'  => 'ticket_id',
                'type'  => 'text',
                'label' => __('Ticket ID', 'bit-pi'),
            ],
            [
                'name'  => 'ticket_name',
                'type'  => 'text',
                'label' => __('Ticket Name', 'bit-pi'),
            ],
            [
                'name'  => 'ticket_slug',
                'type'  => 'text',
                'label' => __('Ticket Slug', 'bit-pi'),
            ],
            [
                'name'  => 'ticket_status',
                'type'  => 'text',
                'label' => __('Ticket Status', 'bit-pi'),
            ],
            [
                'name'  => 'ticket_price',
                'type'  => 'text',
                'label' => __('Ticket Price', 'bit-pi'),
            ],
            [
                'name'  => 'payment_status',
                'type'  => 'text',
                'label' => __('Payment Status', 'bit-pi'),
            ],
            [
                'name'  => 'event_id',
                'type'  => 'text',
                'label' => __('Event ID', 'bit-pi'),
            ],
            [
                'name'  => 'event_name',
                'type'  => 'text',
                'label' => __('Event Name', 'bit-pi'),
            ],
            [
                'name'  => 'order_id',
                'type'  => 'text',
                'label' => __('Order ID', 'bit-pi'),
            ],
        ];

        foreach ($unsetFields as $fieldKey) {
            unset($fields[$fieldKey]);
        }

        return $fields;
    }

    private static function attendeeDeleteFields()
    {
        return [
            [
                'name'  => 'id',
                'type'  => 'text',
                'label' => __('Attendee Id', 'bit-pi'),
            ],
        ];
    }

    private static function orderCreateFields(array $unsetFields = [])
    {
        $fields = [
            [
                'name'  => 'order_id',
                'type'  => 'text',
                'label' => __('Order ID', 'bit-pi'),
            ],
            [
                'name'  => 'customer_fname',
                'type'  => 'text',
                'label' => __('Customer First Name', 'bit-pi'),
            ],
            [
                'name'  => 'customer_lname',
                'type'  => 'text',
                'label' => __('Customer Last Name', 'bit-pi'),
            ],
            [
                'name'  => 'customer_email',
                'type'  => 'text',
                'label' => __('Customer Email', 'bit-pi'),
            ],
            [
                'name'  => 'status',
                'type'  => 'text',
                'label' => __('Status', 'bit-pi'),
            ],
            [
                'name'  => 'ticket_slug',
                'type'  => 'text',
                'label' => __('Ticket Slug', 'bit-pi'),
            ],
            [
                'name'  => 'ticket_quantity',
                'type'  => 'text',
                'label' => __('Ticket Quantity', 'bit-pi'),
            ],
            [
                'name'  => 'total_price',
                'type'  => 'text',
                'label' => __('Total Price', 'bit-pi'),
            ],
            [
                'name'  => 'date_time',
                'type'  => 'text',
                'label' => __('Order Date Time', 'bit-pi'),
            ],
            [
                'name'  => 'event_id',
                'type'  => 'text',
                'label' => __('Event ID', 'bit-pi'),
            ],
            [
                'name'  => 'event_name',
                'type'  => 'text',
                'label' => __('Event Name', 'bit-pi'),
            ],
        ];

        foreach ($unsetFields as $fieldKey) {
            unset($fields[$fieldKey]);
        }

        return $fields;
    }

    private static function orderDeleteFields()
    {
        return [
            [
                'name'  => 'id',
                'type'  => 'text',
                'label' => __('Order Id', 'bit-pi'),
            ],
        ];
    }

    private static function scheduleDeleteFields()
    {
        return [
            [
                'name'  => 'id',
                'type'  => 'text',
                'label' => __('Schedule Id', 'bit-pi'),
            ],
        ];
    }
}
