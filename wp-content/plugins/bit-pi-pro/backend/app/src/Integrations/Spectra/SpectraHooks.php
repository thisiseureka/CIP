<?php

namespace BitApps\PiPro\src\Integrations\Spectra;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class SpectraHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'spectraFormSubmit' => [
                'hook'     => 'uagb_form_success',
                'callback' => [SpectraTrigger::class, 'spectraHandler'],
                'priority' => 10,
                'args'     => 1,
            ],
        ];
    }
}
