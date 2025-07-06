<?php

namespace BitApps\PiPro\src\Integrations\FluentCrm;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use FluentCrm\App\Models\Lists;
use FluentCrm\App\Models\Tag;

final class FluentCrmHelper
{
    private const FLUENT_CRM_PLUGIN_INDEX = 'fluent-crm/fluent-crm.php';

    public static function getContactData($email)
    {
        $contactApi = FluentCrmApi('contacts');

        $contact = $contactApi->getContact($email);

        $customFields = $contact->custom_fields();

        $data = [
            'prefix'         => $contact->prefix,
            'first_name'     => $contact->first_name,
            'last_name'      => $contact->last_name,
            'full_name'      => $contact->full_name,
            'email'          => $contact->email,
            'timezone'       => $contact->timezone,
            'address_line_1' => $contact->address_line_1,
            'address_line_2' => $contact->address_line_2,
            'city'           => $contact->city,
            'state'          => $contact->state,
            'postal_code'    => $contact->postal_code,
            'country'        => $contact->country,
            'ip'             => $contact->ip,
            'phone'          => $contact->phone,
            'source'         => $contact->source,
            'date_of_birth'  => $contact->date_of_birth,
        ];

        if (!empty($customFields)) {
            foreach ($customFields as $key => $value) {
                $data[$key] = $value;
            }
        }

        $lists = $contact->lists;
        $fluentCrmLists = [];
        foreach ($lists as $list) {
            $fluentCrmLists[] = (object) [
                'value' => $list->id,
                'label' => $list->title
            ];
        }

        $data['tags'] = implode(', ', array_column($contact->tags->toArray() ?? [], 'title'));

        $data['lists'] = $fluentCrmLists;

        return $data;
    }

    public static function getFluentCrmTags()
    {
        if (!is_plugin_active(self::FLUENT_CRM_PLUGIN_INDEX)) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Fluent CRM'));
        }

        $tags[] = [
            'value' => 'any',
            'label' => 'Any Tag',
        ];

        $fluentCrmTags = self::fluentCrmTags();

        $tags = array_merge($tags, (array) $fluentCrmTags);

        return Response::success($tags);
    }

    public static function getFluentCrmList()
    {
        if (!is_plugin_active(self::FLUENT_CRM_PLUGIN_INDEX)) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Fluent CRM'));
        }

        $lists[] = [
            'value' => 'any',
            'label' => 'Any List',
        ];
        $fluentCrmLists = self::fluentCrmLists();

        $lists = array_merge($lists, (array) $fluentCrmLists);

        return Response::success($lists);
    }

    public static function getFluentCrmStatus()
    {
        if (!is_plugin_active(self::FLUENT_CRM_PLUGIN_INDEX)) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Fluent CRM'));
        }

        $status[] = [
            'value' => 'any',
            'label' => 'Any status',
        ];
        $fluentCrmStatus = self::fluentCrmStatus();

        $status = array_merge($status, (array) $fluentCrmStatus);

        return Response::success($status);
    }

    private static function fluentCrmStatus()
    {
        $statuses = [
            'subscribed'   => 'Subscribed',
            'pending'      => 'Pending',
            'unsubscribed' => 'Unsubscribed',
            'bounced'      => 'Bounced',
            'complained'   => 'Complained',
        ];

        $fluentCrmStatus = [];

        foreach ($statuses as $key => $status) {
            $fluentCrmStatus[] = [
                'value' => $key,
                'label' => $status
            ];
        }

        return $fluentCrmStatus;
    }

    private static function fluentCrmTags()
    {
        $tags = Tag::get();

        $fluentCrmTags = [];

        foreach ($tags as $tag) {
            $fluentCrmTags[] = [
                'value' => $tag->id,
                'label' => $tag->title
            ];
        }

        return $fluentCrmTags;
    }

    private static function fluentCrmLists()
    {
        $lists = Lists::get();

        $fluentCrmLists = [];

        foreach ($lists as $list) {
            $fluentCrmLists[] = [
                'value' => $list->id,
                'label' => $list->title
            ];
        }

        return $fluentCrmLists;
    }
}
