<?php

namespace BitApps\PiPro\src\Integrations\WordPress;

use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Model\FlowNode;
use BitApps\Pi\src\Integrations\HookRegisterInterface;
use BitApps\PiPro\Config;
use BitApps\PiPro\src\Integrations\WpActionHookListener\WpActionHookListener;

if (!\defined('ABSPATH')) {
    exit;
}

class WordPressHooks implements HookRegisterInterface
{
    /**
     * Register the hooks for WordPress.
     */
    public function register(): array
    {
        $triggers = [
            'addUserRole' => [
                'hook'          => 'add_user_role',
                'callback'      => [new WpActionHookListener(WordPressTasks::getAppSlug(), 'addUserRole', ['user_id', 'role']), 'captureHookData'],
                'priority'      => 10,
                'accepted_args' => 2
            ],
            'postStatusUpdated' => [
                'hook'          => 'transition_post_status',
                'callback'      => [WordPressTrigger::class, 'postStatusUpdated'],
                'priority'      => 10,
                'accepted_args' => 3
            ],
            'postTrashed' => [
                'hook'          => 'wp_trash_post',
                'callback'      => [new WpActionHookListener(WordPressTasks::getAppSlug(), 'postTrashed', ['post_id', 'post_status']), 'captureHookData'],
                'priority'      => 10,
                'accepted_args' => 2
            ],
            'postDeleted' => [
                'hook'          => 'deleted_post',
                'callback'      => [new WpActionHookListener(WordPressTasks::getAppSlug(), 'postDeleted', ['post_id', 'post']), 'captureHookData'],
                'priority'      => 10,
                'accepted_args' => 2
            ],
            'doAction' => [
                'hook'          => Config::VAR_PREFIX . 'do_action',
                'callback'      => [WordPressTrigger::class, 'captureDoActionHookData'],
                'priority'      => 10,
                'accepted_args' => 2
            ]
        ];


        $this->registerCustomHooks($triggers);

        $this->registerUserDefinedHooks($triggers);

        return $triggers;
    }

    /**
     * Register user defined hooks.
     *
     * @param mixed $triggers
     */
    private function registerUserDefinedHooks(&$triggers)
    {
        $flowNodes = FlowNode::select(['field_mapping'])->where('app_slug', 'wordPress')->get();

        if (!\is_array($flowNodes)) {
            return $triggers;
        }

        foreach ($flowNodes as $flowNode) {
            $hookName = $flowNode->field_mapping->configs->{'hook-name'}->value ?? null;

            if (!$hookName) {
                continue;
            }

            $machineSlug = Utility::convertToMachineSlug($hookName);

            $triggers[$machineSlug] = [
                'hook'          => $hookName,
                'callback'      => [new WpActionHookListener(WordPressTasks::getAppSlug(), 'addAction'), 'captureHookData'],
                'priority'      => 10,
                'accepted_args' => PHP_INT_MAX
            ];
        }

        return $triggers;
    }

    /**
     * Register custom hook triggers.
     *
     * @param mixed $triggers
     */
    private function registerCustomHooks(&$triggers)
    {
        $customHooks = WordPressTasks::getHookList();

        foreach ($customHooks as $hookName => $customHook) {
            $numberOfArguments = $customHook['args'] ?? 0;

            $priority = $customHook['priority'] ?? 10;

            $machineSlug = Utility::convertToMachineSlug($hookName);

            $triggers[$machineSlug] = [
                'hook'          => $hookName,
                'callback'      => [new WpActionHookListener(WordPressTasks::getAppSlug(), $machineSlug), 'captureHookData'],
                'priority'      => $priority,
                'accepted_args' => $numberOfArguments
            ];
        }

        return $triggers;
    }
}
