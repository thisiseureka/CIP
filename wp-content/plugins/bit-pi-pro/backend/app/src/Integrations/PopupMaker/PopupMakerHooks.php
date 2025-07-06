<?php

namespace BitApps\PiPro\src\Integrations\PopupMaker;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class PopupMakerHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'popupFormSubmit' => [
                'hook'     => 'pum_sub_form_success',
                'callback' => [PopupMakerTrigger::class, 'handleSubmit'],
                'priority' => 10,
                'args'     => 1,
            ],
        ];
    }
}
