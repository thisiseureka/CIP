<?php

namespace BitApps\PiPro\src\Integrations\Groundhogg;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class GroundhoggHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'contactCreate' => [
                'hook'          => 'groundhogg/contact/post_create',
                'callback'      => [GroundhoggTrigger::class, 'handleGroundhoggSubmit'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'tagApplied' => [
                'hook'          => 'groundhogg/contact/tag_applied',
                'callback'      => [GroundhoggTrigger::class, 'tagApplied'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'tagRemove' => [
                'hook'          => 'groundhogg/contact/tag_removed',
                'callback'      => [GroundhoggTrigger::class, 'tagRemove'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
        ];
    }
}
