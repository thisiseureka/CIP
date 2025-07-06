<?php

namespace BitApps\PiPro\src\Integrations\Slack;

use BitApps\Pi\Deps\BitApps\WPKit\Helpers\JSON;
use BitApps\Pi\Deps\BitApps\WPKit\Http\Client\HttpClient;

if (!\defined('ABSPATH')) {
    exit;
}
class SlackService
{
    private const BASE_URL = 'https://slack.com/api';

    private $http;

    private $headers;

    /**
     * Slack constructor.
     *
     * @param array  $headers
     */
    public function __construct($headers)
    {
        $this->http = new HttpClient();
        $this->headers = $headers;
    }

    /**
     * Create a Channel.
     *
     * @param mixed $fieldMapData
     *
     * @return array
     */
    public function createChannel($fieldMapData)
    {
        $endPoint = self::BASE_URL . '/conversations.create';
        $createChannelData = JSON::encode($fieldMapData);
        $response = $this->http->request($endPoint, 'POST', $createChannelData, $this->headers);

        return [
            'response' => $response,
            'payload'  => $createChannelData
        ];
    }

    /**
     * Send Message to a Channel.
     *
     * @param mixed $fieldMapData
     *
     * @return array
     */
    public function sendMessageToChannel($fieldMapData)
    {
        $endPoint = self::BASE_URL . '/chat.postMessage';
        $channelMessageData = JSON::encode($fieldMapData);

        if ($fieldMapData['post_at']) {
            return $this->scheduleMessage($fieldMapData);
        }

        $response = $this->http->request($endPoint, 'POST', $channelMessageData, $this->headers);

        return [
            'response' => $response,
            'payload'  => $channelMessageData
        ];
    }

    /**
     * Send Direct Message.
     *
     * @param mixed $fieldMapData
     * @param mixed $userId
     *
     * @return array
     */
    public function sendDirectMessage($fieldMapData, $userId)
    {
        $endPoint = self::BASE_URL . '/conversations.open';
        $directMessageData = JSON::encode($userId);
        $response = $this->http->request($endPoint, 'POST', $directMessageData, $this->headers);
        $fieldMapData = array_merge($fieldMapData, ['channel' => $response->channel->id]);

        if ($fieldMapData['post_at']) {
            return $this->scheduleMessage($fieldMapData);
        }

        $this->sendMessageToChannel($fieldMapData);

        return [
            'response' => $response,
            'payload'  => $directMessageData
        ];
    }

    /**
     * Send Schedule Message.
     *
     * @param mixed $fieldMapData
     *
     * @return array
     */
    public function scheduleMessage($fieldMapData)
    {
        $endPoint = self::BASE_URL . '/chat.scheduleMessage';
        $scheduleMessageData = JSON::encode($fieldMapData);
        $response = $this->http->request($endPoint, 'POST', $scheduleMessageData, $this->headers);

        return [
            'response' => $response,
            'payload'  => $scheduleMessageData
        ];
    }

    /**
     * Join a Channel.
     *
     * @param mixed $fieldMapData
     *
     * @return array
     */
    public function joinChannel($fieldMapData)
    {
        $endPoint = self::BASE_URL . '/conversations.join';
        $joinChannelData = JSON::encode($fieldMapData);
        $response = $this->http->request($endPoint, 'POST', $joinChannelData, $this->headers);

        return [
            'response' => $response,
            'payload'  => $joinChannelData
        ];
    }

    /**
     * Find User By Email.
     *
     * @param mixed $fieldMapData
     *
     * @return array
     */
    public function findUserByEmail($fieldMapData)
    {
        $endPoint = self::BASE_URL . '/users.lookupByEmail?email=' . $fieldMapData['email'];
        $response = $this->http->request($endPoint, 'GET', [], $this->headers);

        return [
            'response' => $response,
            'payload'  => ['email' => $fieldMapData['email']]
        ];
    }
}
