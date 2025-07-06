<?php
namespace BitApps\PiPro\Deps\BitApps\WPValidator\Rules;

use BitApps\PiPro\Deps\BitApps\WPValidator\Helpers;
use BitApps\PiPro\Deps\BitApps\WPValidator\Rule;

class RequiredRule extends Rule
{
    use Helpers;

    private $message = 'The :attribute field is required';

    public function validate($value): bool
    {
        return !$this->isEmpty($value);
    }

    public function message()
    {
        return $this->message;
    }
}
