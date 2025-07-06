<?php

namespace BitApps\PiPro\src\Integrations\WpForo;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use wpforo\classes\Topics;

final class WpForoTrigger
{
    public static function handleWPForoTopicAdd($topic, $forum)
    {
        if (empty($topic) || empty($forum)) {
            return false;
        }

        $flows = FlowService::exists('wpForo', 'topicAdded');

        if (!$flows) {
            return;
        }

        $topicAddData = WpForoHelper::formatTopicAddData($topic, $forum);

        IntegrationHelper::handleFlowForForm($flows, $topicAddData);
    }

    public static function handleWPForoPostAdd($post, $topic, $forum)
    {
        if (empty($post) || empty($topic) || empty($forum)) {
            return false;
        }

        $flows = FlowService::exists('wpForo', 'postAdded');

        if (empty($flows) || !$flows) {
            return;
        }

        $postAddData = WpForoHelper::formatPostAddData($post, $topic, $forum);

        IntegrationHelper::handleFlowForForm($flows, $postAddData, $topic['topicid'], 'topic-id');
    }

    public static function handleWPForoUpVote($reaction, $post, $userid)
    {
        if (empty($reaction) || empty($post) || empty($userid)) {
            return false;
        }

        $voteType = $reaction === 1 ? 'up' : 'down';

        if ($voteType === 'down') {
            return;
        }

        $flows = FlowService::exists('wpForo', 'postUpvoted');

        if (empty($flows) || !$flows) {
            return;
        }

        $voteUpData = WpForoHelper::formatVoteData($post, $userid);

        IntegrationHelper::handleFlowForForm($flows, $voteUpData, $post['topicid'], 'topic-id');
    }

    public static function handleWPForoDownVote($reaction, $post, $userid)
    {
        if (empty($reaction) || empty($post) || empty($userid)) {
            return false;
        }

        $voteType = $reaction === 1 ? 'up' : 'down';

        if ($voteType === 'up') {
            return;
        }

        $flows = FlowService::exists('wpForo', 'postDownvoted');

        if (empty($flows) || !$flows) {
            return;
        }

        $voteUpData = WpForoHelper::formatVoteData($post, $userid);

        IntegrationHelper::handleFlowForForm($flows, $voteUpData, $post['topicid'], 'topic-id');
    }

    public static function handleWPForoLike($reaction, $post)
    {
        if (empty($reaction) || empty($post)) {
            return false;
        }

        $userid = $reaction['userid'];
        $reactionType = $reaction['type'];

        if ($reactionType === 'down') {
            return;
        }

        $flows = FlowService::exists('wpForo', 'postLiked');

        if (empty($flows) || !$flows) {
            return;
        }

        $reactionData = WpForoHelper::formatReactionData($post, $userid);

        IntegrationHelper::handleFlowForForm($flows, $reactionData, $post['topicid'], 'topic-id');
    }

    public static function handleWPForoDislike($reaction, $post)
    {
        if (empty($reaction) || empty($post)) {
            return false;
        }

        $userid = $reaction['userid'];
        $reactionType = $reaction['type'];

        if ($reactionType === 'up') {
            return;
        }

        $flows = FlowService::exists('wpForo', 'postDisliked');

        if (empty($flows) || !$flows) {
            return;
        }

        $reactionData = WpForoHelper::formatReactionData($post, $userid);

        IntegrationHelper::handleFlowForForm($flows, $reactionData, $post['topicid'], 'topic-id');
    }

    public static function handleWPForoGetsUpVote($reaction, $post, $voterId)
    {
        if (empty($reaction) || empty($post) || empty($voterId)) {
            return false;
        }

        $voteType = $reaction === 1 ? 'up' : 'down';

        if ($voteType === 'down') {
            return;
        }

        $authorId = $post['userid'];

        $flows = FlowService::exists('wpForo', 'postGetsUpvoted');

        if (!$flows) {
            return;
        }

        $voteGetsData = WpForoHelper::formatGetsVoteData($authorId, $post, $voterId);

        IntegrationHelper::handleFlowForForm($flows, $voteGetsData, $authorId, 'user-id');
    }

    public static function handleWPForoGetsDownVote($reaction, $post, $voterId)
    {
        if (empty($reaction) || empty($post) || empty($voterId)) {
            return false;
        }

        $voteType = $reaction === 1 ? 'up' : 'down';

        if ($voteType === 'up') {
            return;
        }

        $authorId = $post['userid'];

        $flows = FlowService::exists('wpForo', 'postGetsDownvoted');

        if (!$flows) {
            return;
        }

        $voteGetsData = WpForoHelper::formatGetsVoteData($authorId, $post, $voterId);

        IntegrationHelper::handleFlowForForm($flows, $voteGetsData, $authorId, 'user-id');
    }

    public static function handleWPForoGetsLike($reaction, $post)
    {
        if (empty($reaction) || empty($post)) {
            return false;
        }

        $reactionType = $reaction['type'];

        if ($reactionType === 'down') {
            return;
        }

        $authorId = $reaction['post_userid'];
        $likerData = $reaction['userid'];

        $flows = FlowService::exists('wpForo', 'postGetsLiked');

        if (!$flows) {
            return;
        }

        $getsLikeData = WpForoHelper::formatGetsReactionData($post, $authorId, $likerData, 'liker');

        IntegrationHelper::handleFlowForForm($flows, $getsLikeData, $authorId, 'user-id');
    }

    public static function handleWPForoGetsDislike($reaction, $post)
    {
        if (empty($reaction) || empty($post)) {
            return false;
        }

        $reactionType = $reaction['type'];

        if ($reactionType === 'up') {
            return;
        }

        $authorId = $reaction['post_userid'];
        $likerData = $reaction['userid'];

        $flows = FlowService::exists('wpForo', 'postGetsDisliked');

        if (!$flows) {
            return;
        }

        $getsLikeData = WpForoHelper::formatGetsReactionData($post, $authorId, $likerData, 'disliker');

        IntegrationHelper::handleFlowForForm($flows, $getsLikeData, $authorId, 'user-id');
    }

    public static function handleWPForoAnswer($answerStatus, $post)
    {
        if (empty($post)) {
            return false;
        }

        $flows = FlowService::exists('wpForo', 'postAnswered');

        if (empty($flows) || !$flows) {
            return;
        }

        $answerData = WpForoHelper::formatAnswerData($answerStatus, $post);

        IntegrationHelper::handleFlowForForm($flows, $answerData, $post['topicid'], 'topic-id');
    }

    public static function handleWPForoGetsAnswer($answerStatus, $post)
    {
        if (empty($post)) {
            return false;
        }

        $wpForoTopics = new Topics();

        $getTopic = $wpForoTopics->get_topic($post['topicid']);

        if (empty($getTopic)) {
            return false;
        }

        $questionAuthorId = $getTopic['userid'];

        $flows = FlowService::exists('wpForo', 'postGotAnswer');

        if (!$flows) {
            return;
        }

        $getsAnswerData = WpForoHelper::formatAnswerGetsData($answerStatus, $post, $questionAuthorId);

        IntegrationHelper::handleFlowForForm($flows, $getsAnswerData, $questionAuthorId, 'user-id');
    }
}
