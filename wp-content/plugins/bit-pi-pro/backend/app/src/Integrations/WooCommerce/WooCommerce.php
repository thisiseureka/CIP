<?php

namespace BitApps\PiPro\src\Integrations\WooCommerce;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\PiPro\Deps\BitApps\WPKit\Http\Request\Request;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;

class WooCommerce
{
    public function getPostTypes()
    {
        $allPostTypes = [];

        foreach (get_post_types(['public' => true], 'objects') as $postType) {
            $allPostTypes[] = [
                'label' => $postType->label,
                'value' => $postType->name
            ];
        }

        return Response::success($allPostTypes);
    }

    public function getPosts(Request $request)
    {
        $validated = $request->validate(
            [
                'postType' => ['nullable', 'array'],
            ]
        );

        $posts = get_posts(
            [
                'post_type'      => \is_array($validated['postType']) && \count($validated['postType']) ? $validated['postType'] : get_post_types(['public' => true]),
                'posts_per_page' => -1,
                'post_status'    => 'any',
            ]
        );

        $allPosts = [];

        foreach ($posts as $post) {
            $allPosts[] = [
                'label' => $post->post_title,
                'value' => $post->ID
            ];
        }

        return Response::success($allPosts);
    }
}
