<?php

namespace BitApps\PiPro\src\Integrations\Voxel;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class VoxelTrigger
{
    public static function handleCollectionNewPost($event)
    {
        if (!property_exists($event, 'post')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::COLLECTION_NEW_POST_CREATED);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::getPostFieldsData($event->post->get_id());

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleCollectionPostUpdated($event)
    {
        if (!property_exists($event, 'post')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::COLLECTIONS_POST_UPDATED);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::getPostFieldsData($event->post->get_id());

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleNewProfileCreated($event)
    {
        if (!property_exists($event, 'post')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::NEW_PROFILE_CREATED);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::getPostFieldsData($event->post->get_id());

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleProfileUpdated($event)
    {
        if (!property_exists($event, 'post')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::PROFILE_UPDATED);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::getPostFieldsData($event->post->get_id());

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleProfileApproved($event)
    {
        if (!property_exists($event, 'post') || !property_exists($event, 'author')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::PROFILE_APPROVED);

        if (empty($flows) || !$flows) {
            return;
        }

        $fieldsData = VoxelHelper::getPostFieldsData($event->post->get_id());

        $userData = VoxelHelper::userDataByType(
            $event->author->get_id(),
            VoxelHelper::$userCommonFieldsWithWPKeys,
            'profile'
        );

        $data = array_merge($fieldsData, $userData);

        if ($data !== []) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleProfileRejected($event)
    {
        if (!property_exists($event, 'post') || !property_exists($event, 'author')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::PROFILE_REJECTED);

        if (empty($flows) || !$flows) {
            return;
        }

        $fieldsData = VoxelHelper::getPostFieldsData($event->post->get_id());
        $userData = VoxelHelper::userDataByType(
            $event->author->get_id(),
            VoxelHelper::$userCommonFieldsWithWPKeys,
            'profile'
        );

        $data = array_merge($fieldsData, $userData);

        if ($data !== []) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handlePostSubmitted($event)
    {
        if (!property_exists($event, 'post')) {
            return;
        }

        $postType = get_post_type($event->post->get_id());

        $flows = FlowService::exists('Voxel', VoxelTasks::POST_SUBMITTED);
        // $flows = VoxelHelper::flowFilter($flows, 'selectedPostType', $postType);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::getPostFieldsData($event->post->get_id());

        if (!empty($data)) {
            $data = array_merge($data, ['post_type' => $postType]);

            IntegrationHelper::handleFlowForForm($flows, $data, $postType, 'post-id', 'string');
        }
    }

    public static function handlePostUpdated($event)
    {
        if (!property_exists($event, 'post')) {
            return;
        }

        $postType = get_post_type($event->post->get_id());
        $flows = FlowService::exists('Voxel', VoxelTasks::POST_UPDATED);
        // $flows = VoxelHelper::flowFilter($flows, 'selectedPostType', $postType);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::getPostFieldsData($event->post->get_id());

        if (!empty($data)) {
            $data = array_merge($data, ['post_type' => $postType]);

            IntegrationHelper::handleFlowForForm($flows, $data, $postType, 'post-id', 'string');
        }
    }

    public static function handlePostApproved($event)
    {
        if (!property_exists($event, 'post')) {
            return;
        }

        $postType = get_post_type($event->post->get_id());

        $flows = FlowService::exists('Voxel', VoxelTasks::POST_APPROVED);

        // $flows = VoxelHelper::flowFilter($flows, 'selectedPostType', $postType);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::getPostFieldsData($event->post->get_id());

        if (!empty($data)) {
            $data = array_merge($data, ['post_type' => $postType]);

            IntegrationHelper::handleFlowForForm($flows, $data, $postType, 'post-id', 'string');
        }
    }

    public static function handlePostRejected($event)
    {
        if (!property_exists($event, 'post')) {
            return;
        }

        $postType = get_post_type($event->post->get_id());
        $flows = FlowService::exists('Voxel', VoxelTasks::POST_REJECTED);
        // $flows = VoxelHelper::flowFilter($flows, 'selectedPostType', $postType);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::getPostFieldsData($event->post->get_id());

        if (!empty($data)) {
            $data = array_merge($data, ['post_type' => $postType]);

            IntegrationHelper::handleFlowForForm($flows, $data, $postType, 'post-id', 'string');
        }
    }

    public static function handlePostReviewed($event)
    {
        if (!property_exists($event, 'post') || !property_exists($event, 'review')) {
            return;
        }

        $postType = get_post_type($event->post->get_id());
        $flows = FlowService::exists('Voxel', VoxelTasks::POST_REVIEWED);
        // $flows = VoxelHelper::flowFilter($flows, 'selectedPostType', $postType);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::getPostFieldsData($event->post->get_id());
        $data['review_content'] = $event->review->get_content();
        $data['review_created_at'] = $event->review->get_created_at();
        $data['review_details'] = $event->review->get_details();
        $data['post_type'] = $postType;

        $reviewByData = VoxelHelper::userDataByType(
            $event->review->get_user_id(),
            VoxelHelper::$userCommonFieldsWithWPKeys,
            'reviewer'
        );

        $data = array_merge($data, $reviewByData);

        if ($data !== []) {
            IntegrationHelper::handleFlowForForm($flows, $data, $postType, 'post-id', 'string');
        }
    }

    public static function handleMessageReceived($event)
    {
        if (!property_exists($event, 'message')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::USER_RECEIVES_MESSAGE);

        if (empty($flows) || !$flows) {
            return;
        }

        $data['content'] = $event->message->get_content();

        $senderData = VoxelHelper::userDataByType(
            $event->message->get_sender_id(),
            VoxelHelper::$userCommonFieldsWithWPKeys,
            'sender'
        );

        $receiverData = VoxelHelper::userDataByType(
            $event->message->get_receiver_id(),
            VoxelHelper::$userCommonFieldsWithWPKeys,
            'receiver'
        );

        $data = array_merge($data, $senderData, $receiverData);

        if ($data !== []) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleMembershipUserRegister($event)
    {
        if (!property_exists($event, 'user')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::USER_REGISTERED_FOR_MEMBERSHIP);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::userDataByType(
            $event->user->get_id(),
            VoxelHelper::$userAllFieldsWithWPkeys,
            'user'
        );

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleMembershipPlanActivated($event)
    {
        if (!property_exists($event, 'user')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::MEMBERSHIP_PLAN_ACTIVATED);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::userDataByType(
            $event->user->get_id(),
            VoxelHelper::$userAllFieldsWithWPkeys,
            'user'
        );

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleMembershipPlanSwitched($event)
    {
        if (!property_exists($event, 'user')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::MEMBERSHIP_PLAN_SWITCHED);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::userDataByType(
            $event->user->get_id(),
            VoxelHelper::$userAllFieldsWithWPkeys,
            'user'
        );

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleMembershipPlanCanceled($event)
    {
        if (!property_exists($event, 'user')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::MEMBERSHIP_PLAN_CANCELED);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::userDataByType(
            $event->user->get_id(),
            VoxelHelper::$userAllFieldsWithWPkeys,
            'user'
        );

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleNewComment($event)
    {
        if (!property_exists($event, 'reply')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::NEW_COMMENT_ON_TIMELINE);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::userDataByType(
            $event->reply->get_user_id(),
            VoxelHelper::$userAllFieldsWithWPkeys,
            'commenter'
        );

        $data['comment_id'] = $event->reply->get_id();
        $data['status_id'] = $event->reply->get_status_id();
        $data['comment_content'] = $event->reply->get_content();

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleCommentReply($event)
    {
        if (!property_exists($event, 'reply') || !property_exists($event, 'comment')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::COMMENT_NEW_REPLY);

        if (empty($flows) || !$flows) {
            return;
        }

        $replyData['reply_id'] = $event->reply->get_id();
        $replyData['reply'] = $event->reply->get_content();
        $replyData['comment_id'] = $event->comment->get_id();
        $replyData['comment'] = $event->comment->get_content();

        $replierData = VoxelHelper::userDataByType(
            $event->reply->get_user_id(),
            VoxelHelper::$userAllFieldsWithWPkeys,
            'replier'
        );

        $commenterData = VoxelHelper::userDataByType(
            $event->comment->get_user_id(),
            VoxelHelper::$userAllFieldsWithWPkeys,
            'commenter'
        );

        $data = array_merge($replyData, $replierData, $commenterData);

        if ($data !== []) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleProfileNewWallPost($event)
    {
        if (!property_exists($event, 'status') || !property_exists($event, 'author')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::PROFILE_NEW_WALL_POST);

        if (empty($flows) || !$flows) {
            return;
        }

        $profilePostData = VoxelHelper::getPostFieldsData($event->status->get_post_id());
        $profileData = VoxelHelper::userDataByType(
            $event->author->get_id(),
            VoxelHelper::$userCommonFieldsWithWPKeys,
            'profile'
        );

        $data = array_merge($profilePostData, $profileData);

        if (\function_exists('Voxel\Timeline\prepare_status_json')) {
            $statusJson = (array) \Voxel\Timeline\prepare_status_json($event->status);

            foreach ($statusJson as $value) {
                $data['wall_post'][] = $value;
            }
        }

        if ($data !== []) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleNewWallPost($event)
    {
        if (!property_exists($event, 'status') || !property_exists($event, 'post') || !property_exists($event, 'author')) {
            return;
        }

        $postType = get_post_type($event->post->get_id());
        $flows = FlowService::exists('Voxel', VoxelTasks::NEW_WALL_POST_BY_USER);
        // $flows = VoxelHelper::flowFilter($flows, 'selectedPostType', $postType);

        if (empty($flows) || !$flows) {
            return;
        }

        $postData = VoxelHelper::getPostFieldsData($event->status->get_post_id());
        $userData = VoxelHelper::userDataByType(
            $event->author->get_id(),
            VoxelHelper::$userCommonFieldsWithWPKeys,
            'user'
        );
        $data = array_merge($postData, $userData);

        if (\function_exists('Voxel\Timeline\prepare_status_json')) {
            $statusJson = (array) \Voxel\Timeline\prepare_status_json($event->status);

            foreach ($statusJson as $value) {
                $data['wall_post'][] = $value;
            }
        }

        $data['post_type'] = $postType;

        if ($data !== []) {
            IntegrationHelper::handleFlowForForm($flows, $data, $postType, 'post-id', 'string');
        }
    }

    public static function handleNewOrder($event)
    {
        if (!property_exists($event, 'order') || !property_exists($event, 'customer')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::NEW_ORDER_PLACED);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::formatOrderData($event, true);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleOrderApproved($event)
    {
        if (!property_exists($event, 'order') || !property_exists($event, 'customer')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::ORDER_APPROVED_BY_VENDOR);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::formatOrderData($event, true, true);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleOrderDeclined($event)
    {
        if (!property_exists($event, 'order') || !property_exists($event, 'customer')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::ORDER_DECLINED_BY_VENDOR);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::formatOrderData($event, true, true);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleOrderCancel($event)
    {
        if (!property_exists($event, 'order') || !property_exists($event, 'customer')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::ORDER_CANCELED_BY_CUSTOMER);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::formatOrderData($event, true);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handleOrderClaimListing($event)
    {
        if (!property_exists($event, 'order') || !property_exists($event, 'customer')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::ORDERS_CLAIM_LISTING);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::formatOrderData($event, true);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handlePromotionActivated($event)
    {
        if (!property_exists($event, 'order') || !property_exists($event, 'customer')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::ORDER_PROMOTION_ACTIVATED);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::formatOrderData($event, true);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function handlePromotionCanceled($event)
    {
        if (!property_exists($event, 'order') || !property_exists($event, 'customer')) {
            return;
        }

        $flows = FlowService::exists('Voxel', VoxelTasks::ORDER_PROMOTION_CANCELED);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = VoxelHelper::formatOrderData($event, true);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }
}
