<?php

namespace BitApps\PiPro\src\Integrations\FluentCrm;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class FluentCrmHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'addTag' => [
                'hook'          => 'fluentcrm_contact_added_to_tags',
                'callback'      => [FluentCrmTrigger::class, 'handleAddTag'],
                'priority'      => 20,
                'accepted_args' => 2,
            ],
            'removeTag' => [
                'hook'          => 'fluentcrm_contact_removed_from_tags',
                'callback'      => [FluentCrmTrigger::class, 'handleRemoveTag'],
                'priority'      => 20,
                'accepted_args' => 2,
            ],
            'addList' => [
                'hook'          => 'fluentcrm_contact_added_to_lists',
                'callback'      => [FluentCrmTrigger::class, 'handleAddList'],
                'priority'      => 20,
                'accepted_args' => 2,
            ],
            'removeList' => [
                'hook'          => 'fluentcrm_contact_removed_from_lists',
                'callback'      => [FluentCrmTrigger::class, 'handleRemoveList'],
                'priority'      => 20,
                'accepted_args' => 2,
            ],
            'contactCreate' => [
                'hook'          => 'fluentcrm_contact_created',
                'callback'      => [FluentCrmTrigger::class, 'handleContactCreate'],
                'priority'      => 20,
                'accepted_args' => 1,
            ],
            'crmStatusUpdated' => [
                'hook' => ['fluentcrm_subscriber_status_to_subscribed',
                    'fluentcrm_subscriber_status_to_pending',
                    'fluentcrm_subscriber_status_to_unsubscribed',
                    'fluentcrm_subscriber_status_to_bounced',
                    'fluentcrm_subscriber_status_to_complained',
                ],
                'callback'      => [FluentCrmTrigger::class, 'handleChangeStatus'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],

        ];
    }
}
