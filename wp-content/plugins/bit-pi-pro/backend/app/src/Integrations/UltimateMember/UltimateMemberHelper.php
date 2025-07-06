<?php

namespace BitApps\PiPro\src\Integrations\UltimateMember;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


class UltimateMemberHelper
{
    public static function getAllLoginAndRegistrationForm($formType)
    {
        $args = [
            'posts_per_page'   => 999,
            'orderby'          => 'title',
            'order'            => 'ASC',
            'post_type'        => 'um_form',
            'post_status'      => 'publish',
            'suppress_filters' => true,
            'fields'           => ['ids', 'titles'],
            'meta_query'       => [
                [
                    'key'     => '_um_mode',
                    'value'   => $formType,
                    'compare' => 'LIKE',
                ],
            ],
        ];

        $formsList = get_posts($args);
        $formName = ucfirst($formType);
        foreach ($formsList as $form) {
            $allForms[] = [
                'value' => "{$form->ID}",
                'label' => "{$formName} via {$form->post_title}",
            ];
        }

        return $allForms;
    }

    public static function getRoles()
    {
        $roles = [];
        foreach (wp_roles()->roles as $roleName => $roleInfo) {
            $roles[] = [
                'value' => $roleName,
                'label' => $roleInfo['name'],
            ];
        }

        return $roles;
    }
}
