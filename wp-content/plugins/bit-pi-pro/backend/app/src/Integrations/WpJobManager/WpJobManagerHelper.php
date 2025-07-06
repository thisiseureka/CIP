<?php

namespace BitApps\PiPro\src\Integrations\WpJobManager;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;

class WpJobManagerHelper
{
    private static $userFieldsKey = [
        ['field_key' => 'id', 'field_label' => 'ID', 'wp_user_key' => 'ID'],
        ['field_key' => 'login', 'field_label' => 'Login', 'wp_user_key' => 'user_login'],
        ['field_key' => 'display_name', 'field_label' => 'Display Name', 'wp_user_key' => 'display_name'],
        ['field_key' => 'firstname', 'field_label' => 'First Name', 'wp_user_key' => 'user_firstname'],
        ['field_key' => 'lastname', 'field_label' => 'Last Name', 'wp_user_key' => 'user_lastname'],
        ['field_key' => 'email', 'field_label' => 'Email', 'wp_user_key' => 'user_email'],
        ['field_key' => 'role', 'field_label' => 'Role', 'wp_user_key' => 'roles'],
    ];

    public static function getAllJobTypes()
    {
        if (!is_plugin_active(WpJobManagerTasks::WP_JOB_MANAGER_PLUGIN_INDEX)) {
            return Response::error('WP Job Manager plugin not active');
        }

        $types = get_job_listing_types();

        $typeList[] = (object) [
            'value' => 'any',
            'label' => __('Any Job Type', 'bit-pi')
        ];

        if (!empty($types)) {
            foreach ($types as $item) {
                $typeList[] = (object) ['value' => (string) $item->term_id, 'label' => $item->name];
            }
        }

        return Response::success($typeList);
    }

    public static function getAllJobs()
    {
        if (!is_plugin_active(WpJobManagerTasks::WP_JOB_MANAGER_PLUGIN_INDEX)) {
            return Response::error('WP Job Manager plugin not active');
        }

        $jobList[] = (object) [
            'value' => 'any',
            'label' => __('Any Job', 'bit-pi')
        ];

        $args = [
            'post_type'      => 'job_listing',
            'posts_per_page' => 9999,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'post_status'    => 'publish',
        ];

        $jobs = get_posts($args);

        if (!is_wp_error($jobs) && !empty($jobs)) {
            foreach ($jobs as $job) {
                $jobList[] = (object) ['value' => (string) $job->ID, 'label' => esc_html($job->post_title)];
            }
        }

        return Response::success($jobList);
    }

    public static function getApplicationStatuses()
    {
        if (!is_plugin_active(WpJobManagerTasks::WP_JOB_APPLICATION_PLUGIN_INDEX)) {
            return Response::error('WP Job Manager Applications plugin not active');
        }

        $statusList[] = (object) [
            'value' => 'any',
            'label' => 'Any Status',
        ];

        $statuses = get_job_application_statuses();

        if (!empty($statuses) && !is_wp_error($statuses)) {
            foreach ($statuses as $key => $status) {
                $statusList[] = (object) ['value' => $key, 'label' => esc_html($status)];
            }
        }

        return Response::success($statusList);
    }

    public static function getAllUsers()
    {
        if (!is_plugin_active(WpJobManagerTasks::WP_JOB_MANAGER_PLUGIN_INDEX)) {
            return Response::error('WP Job Manager plugin not active');
        }

        $getUsers = get_users(['fields' => ['ID', 'display_name']]);

        if (empty($getUsers)) {
            return false;
        }

        $users[] = (object) [
            'value' => 'any',
            'label' => __('Any User', 'bit-pi')
        ];

        foreach ($getUsers as $item) {
            $users[] = (object) ['value' => (string) $item->ID, 'label' => $item->display_name];
        }

        return Response::success($users);
    }

    public static function formatJobPublishedData($post, $terms)
    {
        if (empty($post)) {
            return false;
        }

        $data = self::formatJobData($post);
        $data['job_listing_type'] = empty($terms) ? '' : $terms[0]->name;

        return $data;
    }

    public static function formatJobFilledData($jobId)
    {
        if (empty($jobId)) {
            return false;
        }

        $post = get_post($jobId);

        $data = self::formatJobData($post);
        $jobTypes = wpjm_get_the_job_types($post);
        $data['job_listing_type'] = empty($jobTypes) ? '' : $jobTypes[0]->name;

        return $data;
    }

    public static function formatApplicationData($applicationId, $jobId)
    {
        if (empty($applicationId) || empty($jobId)) {
            return false;
        }

        $job = get_post($jobId);
        $jobData = self::formatJobData($job);
        $jobTypes = wpjm_get_the_job_types($job);
        $jobData['job_listing_type'] = empty($jobTypes) ? '' : $jobTypes[0]->name;

        $application = get_post($applicationId);

        $applicationData['candidate_name'] = $application->post_title;
        $applicationData['candidate_email'] = get_post_meta($applicationId, '_candidate_email', true);
        $applicationData['candidate_message'] = $application->post_content;
        $attachment = get_post_meta($applicationId, '_attachment', true);

        if (!empty($attachment)) {
            $applicationData['candidate_cv'] = \is_array($attachment) ? $attachment[0] : $attachment;
        } else {
            $applicationData['candidate_cv'] = '';
        }

        return array_merge($jobData, $applicationData);
    }

    public static function formatApplicationStatusData($applicationId, $newStatus, $oldStatus)
    {
        if (empty($applicationId) || empty($newStatus) || empty($oldStatus)) {
            return false;
        }

        $application = get_post($applicationId);
        $jobId = $application->post_parent;
        $job = get_post($jobId);
        $jobData = self::formatJobData($job);
        $jobTypes = wpjm_get_the_job_types($job);
        $jobData['job_listing_type'] = empty($jobTypes) ? '' : $jobTypes[0]->name;
        $applicationData['candidate_name'] = $application->post_title;
        $applicationData['candidate_email'] = get_post_meta($applicationId, '_candidate_email', true);
        $applicationData['candidate_message'] = $application->post_content;
        $attachment = get_post_meta($applicationId, '_attachment', true);

        if (!empty($attachment)) {
            $applicationData['candidate_cv'] = \is_array($attachment) ? $attachment[0] : $attachment;
        } else {
            $applicationData['candidate_cv'] = '';
        }

        $statusData['new_status'] = $newStatus;
        $statusData['old_status'] = $oldStatus;

        return array_merge($jobData, $applicationData, $statusData);
    }

    public static function formatJobData($post)
    {
        $postData = (array) $post;

        $postMeta = get_post_meta($post->ID);

        $postMetaFormatted['application_email_url'] = empty($postMeta['_application']) ? '' : $postMeta['_application'][0];
        $postMetaFormatted['company_website'] = empty($postMeta['_company_website']) ? '' : $postMeta['_company_website'][0];
        $postMetaFormatted['company_twitter'] = empty($postMeta['_company_twitter']) ? '' : $postMeta['_company_twitter'][0];
        $postMetaFormatted['location'] = empty($postMeta['_job_location']) ? '' : $postMeta['_job_location'][0];
        $postMetaFormatted['company_name'] = empty($postMeta['_company_name']) ? '' : $postMeta['_company_name'][0];
        $postMetaFormatted['company_tagline'] = empty($postMeta['_company_tagline']) ? '' : $postMeta['_company_tagline'][0];
        $postMetaFormatted['company_video'] = empty($postMeta['_company_video']) ? '' : $postMeta['_company_video'][0];
        $postMetaFormatted['remote_position'] = (!empty($postMeta['_remote_position']) && $postMeta['_remote_position'][0]) ? 'Yes' : 'No';
        $postMetaFormatted['is_job_featured'] = (!empty($postMeta['_featured']) && $postMeta['_featured'][0]) ? 'Yes' : 'No';
        $postMetaFormatted['job_expiry_date'] = empty($postMeta['_job_expires']) ? '' : $postMeta['_job_expires'][0];

        return array_merge($postData, $postMetaFormatted, self::userDataByType($post->post_author, 'job_owner'));
    }

    public static function handleResumeData($resumeId, $applicationMessage)
    {
        if (empty($resumeId)) {
            return false;
        }

        $resume = get_post($resumeId);
        $resumeData = [];
        $resumeData['resume_id'] = $resume->ID;
        $resumeData['guid'] = $resume->guid;
        $resumeData['candidate_name'] = $resume->post_title;
        $resumeData['candidate_email'] = get_post_meta($resumeId, '_candidate_email', true);
        $resumeData['professional_title'] = get_post_meta($resumeId, '_candidate_title', true);
        $resumeData['location'] = get_post_meta($resumeId, '_candidate_location', true);
        $resumeData['photo'] = get_post_meta($resumeId, '_candidate_photo', true);
        $resumeData['video'] = get_post_meta($resumeId, '_candidate_video', true);
        $resumeData['urls'] = $resumeData['education'] = $resumeData['experience'] = '';
        $urls = get_post_meta($resumeId, '_links', true);

        if (!empty($urls)) {
            $resumeData['urls'] = wp_json_encode(maybe_unserialize($urls));
        }

        $education = get_post_meta($resumeId, '_candidate_education', true);

        if (!empty($education)) {
            $resumeData['education'] = wp_json_encode(maybe_unserialize($education));
        }

        $experience = get_post_meta($resumeId, '_candidate_experience', true);

        if (!empty($experience)) {
            $resumeData['experience'] = wp_json_encode(maybe_unserialize($experience));
        }

        $resumeData['application_message'] = $applicationMessage;

        return $resumeData;
    }

    public static function userDataByType($id, $type = null)
    {
        $userData = get_userdata(\intval($id));

        $formattedUserData = [];

        foreach (self::$userFieldsKey as $item) {
            $key = $item['wp_user_key'];

            $data = \is_array($userData->{$key}) ? implode(',', $userData->{$key}) : $userData->{$key};

            $formattedUserData[(empty($type) ? '' : $type . '_') . $item['field_key']] = $data;
        }

        return $formattedUserData;
    }
}
