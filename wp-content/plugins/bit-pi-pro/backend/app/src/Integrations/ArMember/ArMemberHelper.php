<?php

namespace BitApps\PiPro\src\Integrations\ArMember;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


class ArMemberHelper
{
    public static function getRegisterFormFields()
    {
        $formId = 101;
        global $wpdb;

        $allRawFields = $wpdb->get_results(
            $wpdb->prepare('SELECT arm_form_field_option FROM wp_arm_form_field WHERE arm_form_field_form_id = %d', $formId),
            ARRAY_A
        );
        $allFields = [];
        foreach ($allRawFields as $singleFields) {
            $individualFields = [];
            $extractFields = maybe_unserialize($singleFields['arm_form_field_option']);
            foreach ($extractFields as $key => $exField) {
                if ($key == 'meta_key' || $key == 'label' || $key == 'type') {
                    $individualFields[$key === 'meta_key' ? 'name' : $key] = $exField;
                }
            }

            if ($individualFields['name'] != 'user_pass' && $individualFields['name'] != '') {
                $allFields[] = $individualFields;
            }
        }

        return $allFields;
    }

    public static function getInfoPlan($planId)
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'arm_subscription_plans';
        $plan = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tableName} WHERE `arm_subscription_plan_id` = %d", $planId));
        if (empty($plan)) {
            return;
        }

        $fieldWithValue = [];
        $neededKeys = [
            'arm_subscription_plan_id',
            'arm_subscription_plan_name',
            'arm_subscription_plan_options',
            'arm_subscription_plan_description',
            'arm_subscription_plan_amount',
            'arm_subscription_plan_amount',
            'arm_subscription_plan_role',
        ];
        foreach ($plan as $key => $value) {
            if (\in_array($key, $neededKeys)) {
                if ($key == 'arm_subscription_plan_options') {
                    $value = maybe_unserialize($value);
                    foreach ($value as $k => $v) {
                        $fieldWithValue[$k] = $v;
                    }
                } else {
                    $fieldWithValue[$key] = $value;
                }
            }
        }

        return $fieldWithValue;
    }

    public static function armemberUserInfo($userId)
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'arm_members';
        $userInfo = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tableName} WHERE `arm_user_id` = %d", $userId));
        if (empty($userInfo)) {
            return;
        }

        $mapedFields = ['arm_user_nicename', 'arm_user_email', 'arm_display_name'];
        foreach ($userInfo as $key => $value) {
            if (\in_array($key, $mapedFields)) {
                $armUserInfo[$key] = $value;
            }
        }

        return $armUserInfo;
    }

    public static function userAndPlanData($userId, $planId)
    {
        $userInfo = self::armemberUserInfo($userId);
        $finalData['user_id'] = $userId;
        $finalData['arm_user_nicename'] = $userInfo['arm_user_nicename'];
        $finalData['arm_user_email'] = $userInfo['arm_user_email'];
        $finalData['arm_display_name'] = $userInfo['arm_display_name'];
        $finalData['plan_id'] = $planId;
        $planIdDetail = self::getInfoPlan($planId);

        return array_merge($finalData, $planIdDetail);
    }
}
