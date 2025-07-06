<?php

namespace BitApps\PiPro\src\Integrations\Voxel;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


class VoxelTasks
{
    public const APP_SLUG = 'voxel';

    public const COLLECTION_NEW_POST_CREATED = 'collectionNewPostCreated';

    public const COLLECTIONS_POST_UPDATED = 'collectionPostUpdated';

    public const NEW_PROFILE_CREATED = 'newProfileCreated';

    public const PROFILE_UPDATED = 'profileUpdated';

    public const PROFILE_APPROVED = 'profileApproved';

    public const PROFILE_REJECTED = 'profileRejected';

    public const POST_SUBMITTED = 'postSubmitted';

    public const POST_UPDATED = 'postUpdated';

    public const POST_APPROVED = 'postApproved';

    public const POST_REJECTED = 'postRejected';

    public const POST_REVIEWED = 'postReviewed';

    public const USER_RECEIVES_MESSAGE = 'userReceivesMessage';

    public const USER_REGISTERED_FOR_MEMBERSHIP = 'membershipRegistered';

    public const MEMBERSHIP_PLAN_ACTIVATED = 'membershipActivated';

    public const MEMBERSHIP_PLAN_SWITCHED = 'membershipSwitched';

    public const MEMBERSHIP_PLAN_CANCELED = 'membershipCanceled';

    public const NEW_COMMENT_ON_TIMELINE = 'newComment';

    public const COMMENT_NEW_REPLY = 'commentReply';

    public const PROFILE_NEW_WALL_POST = 'profileWallPost';

    public const NEW_WALL_POST_BY_USER = 'newUserWallPost';

    public const NEW_ORDER_PLACED = 'newOrderPlaced';

    public const ORDER_APPROVED_BY_VENDOR = 'orderApprovedByVendor';

    public const ORDER_DECLINED_BY_VENDOR = 'orderDeclinedByVendor';

    public const ORDER_CANCELED_BY_CUSTOMER = 'orderCanceledByCustomer';

    public const ORDERS_CLAIM_LISTING = 'ordersClaimListing';

    public const ORDER_PROMOTION_ACTIVATED = 'orderPromotionActivated';

    public const ORDER_PROMOTION_CANCELED = 'orderPromotionCanceled';

    public const PROFILE_REVIEWED = 'profileReviewed';

    public const USERS_TIMELINE_APPROVED = 'usersTimelineApproved';

    public const USERS_TIMELINE_CREATED = 'usersTimelineCreated';

    public const COMMENT_LIKED = 'commentLiked';

    public const POST_LIKED = 'postLiked';

    public const POST_QUOTED = 'postQuoted';

    public const POST_REPOSTED = 'postReposted';

    public const USER_MENTIONED_IN_COMMENT = 'userMentionedInComment';

    public const USER_MENTIONED_IN_POST = 'userMentionedInPost';

    public const POST_FOLLOWED = 'postFollowed';

    public const POST_UNFOLLOWED = 'postUnfollowed';

    // public const POST_REVIEW_APPROVED = 'postReviewApproved';

    // public const TIMELINE_POST_APPROVED = 'timelinePostApproved';

    // public const WALL_POST_APPROVED = 'wallPostApproved';
}
