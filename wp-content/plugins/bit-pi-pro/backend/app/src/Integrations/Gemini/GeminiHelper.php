<?php

namespace BitApps\PiPro\src\Integrations\Gemini;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Deps\BitApps\WPKit\Helpers\JSON;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Client\HttpClient;

class GeminiHelper
{
    private const BASE_URL = 'https://generativelanguage.googleapis.com/v1beta/';

    private $http;

    private $headers;

    public function __construct($headers)
    {
        $this->http = new HttpClient();
        $this->headers = $headers;
    }

    public function askGemini($data, $apiKey)
    {
        $contents = [
            [
                'parts' => [
                    ['text' => $data['message']],
                ]
            ],
        ];

        $payload = ['contents' => $contents];
        $payload = $this->setFeaturePayload($payload, $data);
        $url = self::BASE_URL . "{$data['model']}:generateContent?key={$apiKey}";

        $response = $this->http->request($url, 'POST', JSON::encode($payload), $this->headers);

        return [
            'response'    => $response,
            'payload'     => $payload,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function generateText($messages, $data, $apiKey)
    {
        $contents = [];
        foreach ($messages as $msg) {
            $contents[] = [
                'role'  => $msg['role'],
                'parts' => [['text' => $msg['value']]]
            ];
        }

        $payload = [
            'contents' => $contents
        ];
        $payload = $this->setFeaturePayload($payload, $data);
        $url = self::BASE_URL . "{$data['model']}:generateContent?key={$apiKey}";

        $response = $this->http->request($url, 'POST', JSON::encode($payload), $this->headers);

        return [
            'response'    => $response,
            'payload'     => $payload,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    private function setFeaturePayload($payload, $data)
    {
        if (!empty($data['max_token'])) {
            $payload['generationConfig']['maxOutputTokens'] = (int) $data['max_token'];
        }
        if (!empty($data['temperature'])) {
            $payload['generationConfig']['temperature'] = (float) $data['temperature'];
        }
        if (!empty($data['topP'])) {
            $payload['generationConfig']['topP'] = (float) $data['topP'];
        }
        if (!empty($data['topK'])) {
            $payload['generationConfig']['topK'] = (int) $data['topK'];
        }
        if (!empty($data['stop_sequence'])) {
            $payload['generationConfig']['stopSequences'] = [$data['stop_sequence']];
        }

        return $payload;
    }
}
