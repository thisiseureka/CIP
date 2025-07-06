<?php

namespace BitApps\PiPro\src\Integrations\Github;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Deps\BitApps\WPKit\Helpers\JSON;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Client\HttpClient;

class GithubHelper
{
    private const BASE_URL = 'https://api.github.com';

    private $http;

    private $headers;

    public function __construct($headers)
    {
        $this->http = new HttpClient();
        $this->headers = $headers;
    }

    public function createGist($data)
    {
        $payload = [
            'description' => $data['description'],
            'public'      => $data['public'],
            'files'       => [
                $data['filename'] => [
                    'content' => $data['content']
                ]
            ]
        ];
        $url = self::BASE_URL . '/gists';
        $response = $this->http->request($url, 'POST', JSON::encode($payload), $this->headers);

        return [
            'response'    => $response,
            'payload'     => $payload,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function createIssue($data)
    {
        $payload = [
            'title'     => $data['title'],
            'body'      => $data['body'],
            'assignees' => [$data['assignee']],
            'labels'    => [$data['label']]
        ];
        $url = self::BASE_URL . "/repos/{$data['owner']}/{$data['repository']}/issues";
        $response = $this->http->request($url, 'POST', JSON::encode($payload), $this->headers);

        return [
            'response'    => $response,
            'payload'     => $payload,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function createIssueComment($data)
    {
        $payload = [
            'body' => $data['comment']
        ];
        $url = self::BASE_URL . "/repos/{$data['owner']}/{$data['repository']}/issues/{$data['issue_number']}/comments";
        $response = $this->http->request($url, 'POST', JSON::encode($payload), $this->headers);

        return [
            'response'    => $response,
            'payload'     => $payload,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function getUser($data)
    {
        $url = self::BASE_URL . "/users/{$data['name']}";
        $response = $this->http->request($url, 'GET', null, $this->headers);

        return [
            'response'    => $response,
            'payload'     => $data,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function getRepository($data)
    {
        $url = self::BASE_URL . "/repos/{$data['owner']}/{$data['repository']}";
        $response = $this->http->request($url, 'GET', null, $this->headers);

        return [
            'response'    => $response,
            'payload'     => $data,
            'status_code' => $this->http->getResponseCode()
        ];
    }
}
