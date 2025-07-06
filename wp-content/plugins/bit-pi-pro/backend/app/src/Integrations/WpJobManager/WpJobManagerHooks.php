<?php

namespace BitApps\PiPro\src\Integrations\WpJobManager;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class WpJobManagerHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            WpJobManagerTasks::JOB_PUBLISHED => [
                'hook'          => 'transition_post_status',
                'callback'      => [WpJobManagerTrigger::class, 'handleWpJobManagerJobPublished'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            WpJobManagerTasks::SPECIFIC_JOB_NOT_FILLED => [
                'hook'          => 'job_manager_my_job_do_action',
                'callback'      => [WpJobManagerTrigger::class, 'handleSpecificTypeJobNotFilled'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            WpJobManagerTasks::JOB_UPDATED => [
                'hook'          => 'job_manager_user_edit_job_listing',
                'callback'      => [WpJobManagerTrigger::class, 'handleJobUpdate'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            WpJobManagerTasks::APPLICATION_SPECIFIC_TYPE_SUBMITTED => [
                'hook'          => 'job_manager_applications_new_job_application',
                'callback'      => [WpJobManagerTrigger::class, 'handleApplicationSubmitSpecificType'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            WpJobManagerTasks::JOB_TYPE_APPLICATION_STATUS_CHANGED => [
                'hook'          => 'post_updated',
                'callback'      => [WpJobManagerTrigger::class, 'handleJobTypeApplicationStatusChange'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            WpJobManagerTasks::APPLY_WITH_RESUME => [
                'hook'          => 'resume_manager_apply_with_resume',
                'callback'      => [WpJobManagerTrigger::class, 'handleApplyWithResume'],
                'priority'      => 10,
                'accepted_args' => 4,
            ],
            WpJobManagerTasks::JOB_FILLED => [
                'hook'          => 'job_manager_my_job_do_action',
                'callback'      => [WpJobManagerTrigger::class, 'handleWpJobManagerJobFilled'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            WpJobManagerTasks::JOB_NOT_FILLED => [
                'hook'          => 'job_manager_my_job_do_action',
                'callback'      => [WpJobManagerTrigger::class, 'handleWpJobManagerJobNotFilled'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            WpJobManagerTasks::SPECIFIC_JOB_FILLED => [
                'hook'          => 'job_manager_my_job_do_action',
                'callback'      => [WpJobManagerTrigger::class, 'handleSpecificTypeJobFilled'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            WpJobManagerTasks::APPLICATION_SUBMITTED => [
                'hook'          => 'job_manager_applications_new_job_application',
                'callback'      => [WpJobManagerTrigger::class, 'handleApplicationSubmit'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            WpJobManagerTasks::APPLICATION_STATUS_CHANGED => [
                'hook'          => 'post_updated',
                'callback'      => [WpJobManagerTrigger::class, 'handleApplicationStatusChange'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
