<?php

namespace BitApps\PiPro\src\Integrations\OpenAi;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\Deps\BitApps\WPKit\Helpers\JSON;
use BitApps\Pi\Deps\BitApps\WPKit\Http\Client\HttpClient;

class OpenAiService
{
    private $baseUrl;

    private $http;

    private $headers;

    /**
     * OpenAiService constructor.
     *
     * @param string $baseUrl
     * @param array  $headers
     */
    public function __construct($baseUrl, $headers)
    {
        $this->baseUrl = $baseUrl;
        $this->http = new HttpClient();
        $this->headers = $headers;
    }

    public function createCompletion($fieldMapData)
    {
        unset($fieldMapData['prompt'], $fieldMapData['content'], $fieldMapData['advance-feature']);
        $endPoint = $this->baseUrl . '/chat/completions';
        $responseDecodeFormat = JSON::decode($fieldMapData['response_format']);
        $fieldMapData['response_format'] = $responseDecodeFormat;
        $completionRequestData = JSON::encode($fieldMapData);
        $response = $this->http->request($endPoint, 'POST', $completionRequestData, $this->headers);

        return [
            'response'    => $response,
            'payload'     => $completionRequestData,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    /**
     * Generate Image.
     *
     * @param mixed $generateImageBody
     *
     * @return array
     */
    public function generateImage($generateImageBody)
    {
        $endPoint = $this->baseUrl . '/images/generations';
        $imageRequestData = JSON::encode($generateImageBody);
        $response = $this->http->request($endPoint, 'POST', $imageRequestData, $this->headers);

        return [
            'response'    => $response,
            'payload'     => $imageRequestData,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    /**
     * Generate Audio.
     *
     * @param array $fieldMapData
     *
     * @return array
     */
    public function generateAudio($fieldMapData)
    {
        $endPoint = $this->baseUrl . '/audio/speech';
        $audioRequestData = JSON::encode($fieldMapData);
        $response = $this->http->request($endPoint, 'POST', $audioRequestData, $this->headers);

        return [
            'response'    => $response,
            'payload'     => $audioRequestData,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    /**
     * List of Batches according to Limit.
     *
     * @param array $batchLimit
     *
     * @return array
     */
    public function listBatches($batchLimit)
    {
        $endPoint = add_query_arg('limit', $batchLimit, $this->baseUrl . '/batches');

        $response = $this->http->request($endPoint, 'GET', [], $this->headers);

        $statusCode = $this->http->getResponseCode();

        return [
            'response'    => $response,
            'payload'     => $batchLimit,
            'status_code' => $statusCode
        ];
    }

    /**
     * Get A Batch.
     *
     * @param mixed $batchId
     *
     * @return array
     */
    public function getBatch($batchId)
    {
        $endPoint = $this->baseUrl . '/batches/' . $batchId;

        $response = $this->http->request($endPoint, 'GET', [], $this->headers);

        $statusCode = $this->http->getResponseCode();

        return [
            'response'    => $response,
            'payload'     => $batchId,
            'status_code' => $statusCode
        ];
    }

    /**
     * Cancel A Batch.
     *
     * @param mixed $batchId
     *
     * @return array
     */
    public function cancelBatch($batchId)
    {
        $endPoint = $this->baseUrl . '/batches/' . $batchId . '/cancel';

        $response = $this->http->request($endPoint, 'POST', [], $this->headers);

        $statusCode = $this->http->getResponseCode();

        return [
            'response'    => $response,
            'payload'     => $batchId,
            'status_code' => $statusCode
        ];
    }

    /**
     * Create Moderation.
     *
     * @param mixed $data
     *
     * @return array
     */
    public function createModeration($data)
    {
        $endPoint = $this->baseUrl . '/moderations';
        $moderationRequestData = JSON::encode($data);
        $response = $this->http->request($endPoint, 'POST', $moderationRequestData, $this->headers);

        return [
            'response'    => $response,
            'payload'     => $moderationRequestData,
            'status_code' => $this->http->getResponseCode()
        ];
    }
}
