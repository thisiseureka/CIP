<?php

namespace BitApps\PiPro\src\Integrations\JetEngine;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use WP_Query;

final class JetEngineHelper
{
    public static function commentFields()
    {
        return [
            [
                'name'  => 'comment_id',
                'type'  => 'text',
                'label' => __('Comment ID', 'bit-pi')
            ],
            [
                'name'  => 'comment_post_ID',
                'type'  => 'text',
                'label' => __('Comment Post ID', 'bit-pi')
            ],
            [
                'name'  => 'user_id',
                'type'  => 'text',
                'label' => __('Comment Author ID', 'bit-pi')
            ],
            [
                'name'  => 'comment_author',
                'type'  => 'text',
                'label' => __('Comment Author Name', 'bit-pi')
            ],
            [
                'name'  => 'comment_author_email',
                'type'  => 'text',
                'label' => __('Comment Author Email', 'bit-pi')
            ],
            [
                'name'  => 'comment_author_IP',
                'type'  => 'text',
                'label' => __('Comment Author IP', 'bit-pi')
            ],
            [
                'name'  => 'comment_agent',
                'type'  => 'text',
                'label' => __('Comment Author Agent', 'bit-pi')
            ],
            [
                'name'  => 'comment_author_url',
                'type'  => 'text',
                'label' => __('Comment Author URL', 'bit-pi')
            ],
            [
                'name'  => 'comment_content',
                'type'  => 'text',
                'label' => __('Comment Content', 'bit-pi')
            ],
            [
                'name'  => 'comment_type',
                'type'  => 'text',
                'label' => __('Comment Type', 'bit-pi')
            ],
            [
                'name'  => 'comment_parent',
                'type'  => 'text',
                'label' => __('Comment Parent ID', 'bit-pi')
            ],
            [
                'name'  => 'comment_date',
                'type'  => 'text',
                'label' => __('Comment Date', 'bit-pi')
            ],
            [
                'name'  => 'comment_date_gmt',
                'type'  => 'text',
                'label' => __('Comment Date Time', 'bit-pi')
            ],

        ];
    }

    public static function postFields()
    {
        return [
            [
                'name'  => 'ID',
                'type'  => 'text',
                'label' => __('Post Id', 'bit-pi')
            ],
            [
                'name'  => 'post_title',
                'type'  => 'text',
                'label' => __('Post Title', 'bit-pi')
            ],
            [
                'name'  => 'post_content',
                'type'  => 'text',
                'label' => __('Post Content', 'bit-pi')
            ],
            [
                'name'  => 'post_excerpt',
                'type'  => 'text',
                'label' => __('Post Excerpt', 'bit-pi')
            ],
            [
                'name'  => 'guid',
                'type'  => 'text',
                'label' => __('Post URL', 'bit-pi')
            ],
            [
                'name'  => 'post_type',
                'type'  => 'text',
                'label' => __('Post Type', 'bit-pi')
            ],
            [
                'name'  => 'post_author',
                'type'  => 'text',
                'label' => __('Post Author ID', 'bit-pi')
            ],
            [
                'name'  => 'comment_status',
                'type'  => 'text',
                'label' => __('Post Comment Status', 'bit-pi')
            ],
            [
                'name'  => 'comment_count',
                'type'  => 'text',
                'label' => __('Post Comment Count', 'bit-pi')
            ],
            [
                'name'  => 'post_status',
                'type'  => 'text',
                'label' => __('Post Status', 'bit-pi')
            ],
            [
                'name'  => 'post_date',
                'type'  => 'text',
                'label' => __('Post Created Date', 'bit-pi')
            ],
            [
                'name'  => 'post_modified',
                'type'  => 'text',
                'label' => __('Post Modified Date', 'bit-pi')
            ],
            [
                'name'  => 'meta_key',
                'type'  => 'text',
                'label' => __('Meta Key', 'bit-pi')
            ],
            [
                'name'  => 'meta_value',
                'type'  => 'text',
                'label' => __('Meta Value', 'bit-pi')
            ],
        ];
    }

    public static function getPostTypes()
    {
        $cptArguments = [
            'public'          => true,
            'capability_type' => 'post',
        ];

        $types = get_post_types($cptArguments, 'object');

        $lists = [];

        foreach ($types as $key => $type) {
            $lists[$key]['value'] = $type->name;
            $lists[$key]['label'] = $type->label;
        }

        return $lists;
    }

    public static function getPostTitles()
    {
        $query = new WP_Query(
            [
                'post_type' => 'post',
                'nopaging'  => true,
            ]
        );

        $posts = $query->get_posts();

        $postTitles = [];

        foreach ($posts as $key => $post) {
            $postTitles[$key]['value'] = $post->ID;
            $postTitles[$key]['label'] = $post->post_title;
        }

        return $postTitles;
    }
}
