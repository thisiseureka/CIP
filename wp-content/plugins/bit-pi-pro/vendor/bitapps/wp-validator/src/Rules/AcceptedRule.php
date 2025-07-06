<?php

namespace BitApps\PiPro\Deps\BitApps\WPValidator\Rules;

use BitApps\PiPro\Deps\BitApps\WPValidator\Rule;

class AcceptedRule extends Rule
{
    private $message = "The :attribute must be accepted";

    public function validate($value): bool
    {
        $accepted = ['yes', 'on', '1', 1, true, 'true'];
        return in_array($value, $accepted, true);
    }

    public function message()
    {
        return $this->message;
    }
}
