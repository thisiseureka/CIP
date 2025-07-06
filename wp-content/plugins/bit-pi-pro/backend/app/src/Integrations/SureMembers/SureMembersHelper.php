<?php

namespace BitApps\PiPro\src\Integrations\SureMembers;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\PiPro\Deps\BitApps\WPKit\Http\Request\Request;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use SureMembers\Inc\Access_Groups;

class SureMembersHelper
{
    public const SUREMEMBER_PLUGIN_INDEX = 'suremembers/suremembers.php';

    public static function getSureMembersGroups(Request $request)
    {
        if (!is_plugin_active(self::SUREMEMBER_PLUGIN_INDEX)) {
            // translators: %s: Plugin Version
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'SureMembers'));
        }

        $validated = $request->validate(
            [
                'machineSlug' => ['required', 'string', 'sanitize:text']
            ]
        );

        $allGroups = [];

        if ($validated['machineSlug'] === 'groupUpdated') {
            $allGroups = [
                [
                    'value' => 'any',
                    'label' => 'Any Group'
                ],
            ];
        }

        $accessGroups = Access_Groups::get_active();

        if (!empty($accessGroups)) {
            foreach ($accessGroups as $key => $accessGroup) {
                $allGroups[] = [
                    'value' => $key,
                    'label' => $accessGroup
                ];
            }
        }

        return Response::success($allGroups);
    }

    public static function sureMembersGrantOrRevoke($userId, $accessGroupId)
    {
        $groupData = (array) get_post(\intval($accessGroupId));

        if ($groupData === []) {
            return false;
        }

        $userData = get_userdata(\intval($userId));

        if (empty($userData) || !isset($userData->ID)) {
            return false;
        }

        $formattedUserData = [
            'wp_user_id'     => $userData->ID,
            'user_login'     => $userData->user_login,
            'display_name'   => $userData->display_name,
            'user_firstname' => $userData->user_firstname,
            'user_lastname'  => $userData->user_lastname,
            'user_email'     => $userData->user_email,
            'user_role'      => \is_array($userData->roles)
                                    ? implode(',', $userData->roles) : $userData->roles,
        ];

        return array_merge($groupData, $formattedUserData);
    }

    public static function sureMembersGroupUpdated($suremembersPostId)
    {
        $groupData = (array) get_post(\intval($suremembersPostId));

        if ($groupData === []) {
            return false;
        }

        $sanitizedPost = sanitize_post($_POST);

        $sanitizedPostData = [];

        if (isset($sanitizedPost['suremembers_post'])) {
            $suremembersPost = $sanitizedPost['suremembers_post'];
            $sanitizedPostData['suremembers_group_rules'] = implode(',', $suremembersPost['rules']);
            $sanitizedPostData['suremembers_user_roles'] = implode(',', $suremembersPost['suremembers_user_roles']);
            $suremembersRedirectUrl = wp_parse_url($suremembersPost['restrict']['redirect_url']);
            $redirectURLPath = trim($suremembersRedirectUrl['path'], '/');
            $sanitizedPostData['suremembers_redirect_url'] = $redirectURLPath;
            $sanitizedPostData['suremembers_unauthorized_action'] = $suremembersPost['restrict']['unauthorized_action'];
            $sanitizedPostData['suremembers_preview_content'] = $suremembersPost['restrict']['preview_content'];
        }

        return array_merge($groupData, $sanitizedPostData);
    }
}
