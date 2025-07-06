<?php

namespace BitApps\PiPro\src\Integrations\Voxel;

use BitApps\Pi\src\Integrations\HookRegisterInterface;
use BitApps\PiPro\src\Integrations\WpActionHookListener\WpActionHookListener;

if (!\defined('ABSPATH')) {
    exit;
}

class VoxelHooks implements HookRegisterInterface
{
    public function register(): array
    {
        $voxelEvents = [
            VoxelTasks::COLLECTION_NEW_POST_CREATED => [
                'hook'          => 'voxel/app-events/post-types/collection/post:submitted',
                'callback'      => [VoxelTrigger::class, 'handleCollectionNewPost'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::COLLECTIONS_POST_UPDATED => [
                'hook'          => 'voxel/app-events/post-types/collection/post:updated',
                'callback'      => [VoxelTrigger::class, 'handleCollectionPostUpdated'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::NEW_PROFILE_CREATED => [
                'hook'          => 'voxel/app-events/post-types/profile/post:submitted',
                'callback'      => [VoxelTrigger::class, 'handleNewProfileCreated'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::PROFILE_UPDATED => [
                'hook'          => 'voxel/app-events/post-types/profile/post:updated',
                'callback'      => [VoxelTrigger::class, 'handleProfileUpdated'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::PROFILE_APPROVED => [
                'hook'          => 'voxel/app-events/post-types/profile/post:approved',
                'callback'      => [VoxelTrigger::class, 'handleProfileApproved'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::PROFILE_REJECTED => [
                'hook'          => 'voxel/app-events/post-types/profile/post:rejected',
                'callback'      => [VoxelTrigger::class, 'handleProfileRejected'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::USER_RECEIVES_MESSAGE => [
                'hook'          => 'voxel/app-events/messages/user:received_message',
                'callback'      => [VoxelTrigger::class, 'handleMessageReceived'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::USER_REGISTERED_FOR_MEMBERSHIP => [
                'hook'          => 'voxel/app-events/membership/user:registered',
                'callback'      => [VoxelTrigger::class, 'handleMembershipUserRegister'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::MEMBERSHIP_PLAN_ACTIVATED => [
                'hook'          => 'voxel/app-events/membership/plan:activated',
                'callback'      => [VoxelTrigger::class, 'handleMembershipPlanActivated'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::MEMBERSHIP_PLAN_SWITCHED => [
                'hook'          => 'voxel/app-events/membership/plan:switched',
                'callback'      => [VoxelTrigger::class, 'handleMembershipPlanSwitched'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::MEMBERSHIP_PLAN_CANCELED => [
                'hook'          => 'voxel/app-events/membership/plan:canceled',
                'callback'      => [VoxelTrigger::class, 'handleMembershipPlanCanceled'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::NEW_COMMENT_ON_TIMELINE => [
                'hook'          => 'voxel/app-events/timeline/comment:created',
                'callback'      => [VoxelTrigger::class, 'handleNewComment'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::COMMENT_NEW_REPLY => [
                'hook'          => 'voxel/app-events/timeline/comment-reply:created',
                'callback'      => [VoxelTrigger::class, 'handleCommentReply'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::PROFILE_NEW_WALL_POST => [
                'hook'          => 'voxel/app-events/post-types/profile/wall-post:created',
                'callback'      => [VoxelTrigger::class, 'handleProfileNewWallPost'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::NEW_ORDER_PLACED => [
                'hook'          => 'voxel/app-events/products/orders/customer:order_placed',
                'callback'      => [VoxelTrigger::class, 'handleNewOrder'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::ORDER_APPROVED_BY_VENDOR => [
                'hook'          => 'voxel/app-events/products/orders/vendor:order_approved',
                'callback'      => [VoxelTrigger::class, 'handleOrderApproved'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::ORDER_DECLINED_BY_VENDOR => [
                'hook'          => 'voxel/app-events/products/orders/vendor:order_declined',
                'callback'      => [VoxelTrigger::class, 'handleOrderDeclined'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::ORDER_CANCELED_BY_CUSTOMER => [
                'hook'          => 'voxel/app-events/products/orders/customer:order_canceled',
                'callback'      => [VoxelTrigger::class, 'handleOrderCancel'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::ORDERS_CLAIM_LISTING => [
                'hook'          => 'voxel/app-events/claims/claim:processed',
                'callback'      => [VoxelTrigger::class, 'handleOrderClaimListing'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::ORDER_PROMOTION_ACTIVATED => [
                'hook'          => 'voxel/app-events/promotions/promotion:activated',
                'callback'      => [VoxelTrigger::class, 'handlePromotionActivated'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::ORDER_PROMOTION_CANCELED => [
                'hook'          => 'voxel/app-events/promotions/promotion:canceled',
                'callback'      => [VoxelTrigger::class, 'handlePromotionCanceled'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::PROFILE_REVIEWED => [
                'hook'          => 'voxel/app-events/post-types/profile/review:created',
                'callback'      => [new WpActionHookListener(VoxelTasks::APP_SLUG, VoxelTasks::PROFILE_REVIEWED), 'captureHookData'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::USERS_TIMELINE_APPROVED => [
                'hook'          => 'voxel/app-events/users/timeline/status:approved',
                'callback'      => [new WpActionHookListener(VoxelTasks::APP_SLUG, VoxelTasks::USERS_TIMELINE_APPROVED), 'captureHookData'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::USERS_TIMELINE_CREATED => [
                'hook'          => 'voxel/app-events/users/timeline/status:created',
                'callback'      => [new WpActionHookListener(VoxelTasks::APP_SLUG, VoxelTasks::USERS_TIMELINE_CREATED), 'captureHookData'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::COMMENT_LIKED => [
                'hook'          => 'voxel/app-events/users/timeline/comment-liked',
                'callback'      => [new WpActionHookListener(VoxelTasks::APP_SLUG, VoxelTasks::COMMENT_LIKED), 'captureHookData'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::POST_LIKED => [
                'hook'          => 'voxel/app-events/users/timeline/post-liked',
                'callback'      => [new WpActionHookListener(VoxelTasks::APP_SLUG, VoxelTasks::POST_LIKED), 'captureHookData'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::POST_QUOTED => [
                'hook'          => 'voxel/app-events/users/timeline/post-quoted',
                'callback'      => [new WpActionHookListener(VoxelTasks::APP_SLUG, VoxelTasks::POST_QUOTED), 'captureHookData'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::POST_REPOSTED => [
                'hook'          => 'voxel/app-events/users/timeline/post-reposted',
                'callback'      => [new WpActionHookListener(VoxelTasks::APP_SLUG, VoxelTasks::POST_REPOSTED), 'captureHookData'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::USER_MENTIONED_IN_COMMENT => [
                'hook'          => 'voxel/app-events/users/timeline/mentioned-in-comment',
                'callback'      => [new WpActionHookListener(VoxelTasks::APP_SLUG, VoxelTasks::USER_MENTIONED_IN_COMMENT), 'captureHookData'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::USER_MENTIONED_IN_POST => [
                'hook'          => 'voxel/app-events/users/timeline/mentioned-in-post',
                'callback'      => [new WpActionHookListener(VoxelTasks::APP_SLUG, VoxelTasks::USER_MENTIONED_IN_POST), 'captureHookData'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::POST_FOLLOWED => [
                'hook'          => 'st_voxel_post_followed',
                'callback'      => [new WpActionHookListener(VoxelTasks::APP_SLUG, VoxelTasks::POST_FOLLOWED), 'captureHookData'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            VoxelTasks::POST_UNFOLLOWED => [
                'hook'          => 'st_voxel_post_unfollowed',
                'callback'      => [new WpActionHookListener(VoxelTasks::APP_SLUG, VoxelTasks::POST_UNFOLLOWED), 'captureHookData'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
        ];

        $voxelPostTypes = VoxelHelper::getAllPostTypes();

        if (!empty($voxelPostTypes)) {
            foreach ($voxelPostTypes as $voxelPostType) {
                $type = $voxelPostType->value;

                if (!empty($type)) {
                    $hookPrefix = 'voxel/app-events/post-types/' . $type;
                    $voxelEvents[VoxelTasks::POST_SUBMITTED] = [
                        'hook'          => $hookPrefix . '/post:submitted',
                        'callback'      => [VoxelTrigger::class, 'handlePostSubmitted'],
                        'priority'      => 10,
                        'accepted_args' => 1,
                    ];
                    $voxelEvents[VoxelTasks::POST_UPDATED] = [
                        'hook'          => $hookPrefix . '/post:updated',
                        'callback'      => [VoxelTrigger::class, 'handlePostUpdated'],
                        'priority'      => 10,
                        'accepted_args' => 1,
                    ];
                    $voxelEvents[VoxelTasks::POST_APPROVED] = [
                        'hook'          => $hookPrefix . '/post:approved',
                        'callback'      => [VoxelTrigger::class, 'handlePostApproved'],
                        'priority'      => 10,
                        'accepted_args' => 1,
                    ];
                    $voxelEvents[VoxelTasks::POST_REJECTED] = [
                        'hook'          => $hookPrefix . '/post:rejected',
                        'callback'      => [VoxelTrigger::class, 'handlePostRejected'],
                        'priority'      => 10,
                        'accepted_args' => 1,
                    ];

                    $voxelEvents[VoxelTasks::POST_REVIEWED] = [
                        'hook'          => $hookPrefix . '/review:created',
                        'callback'      => [VoxelTrigger::class, 'handlePostReviewed'],
                        'priority'      => 10,
                        'accepted_args' => 1,
                    ];
                    $voxelEvents[VoxelTasks::NEW_WALL_POST_BY_USER] = [
                        'hook'          => $hookPrefix . '/wall-post:created',
                        'callback'      => [VoxelTrigger::class, 'handleNewWallPost'],
                        'priority'      => 10,
                        'accepted_args' => 1,
                    ];
                }
            }
        }

        return $voxelEvents;
    }
}
