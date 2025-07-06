<?php

namespace BitApps\PiPro\src\Integrations\Notion;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\src\API\BaseAPI;
use BitApps\Pi\src\Authorization\OAuth2\OAuth2Authorization;

class NotionHelper extends BaseAPI
{
    private const NOTION_API_VERSION = 'v1';

    private const BASE_URL = 'https://api.notion.com/';

    public function __construct(OAuth2Authorization $authorization)
    {
        parent::__construct($authorization, self::BASE_URL . self::NOTION_API_VERSION . '/', 'application/json');
        $this->http->setHeader('Notion-Version', '2022-06-28');
    }

    public function processedData($data)
    {
        $payLoadData = [];
        foreach ($data as $key => $value) {
            $payLoadData[$key] = $value;
        }

        return $payLoadData;
    }

    public function createPage($parentPageId, $data)
    {
        $data = $this->processedData($data);

        $payload = [
            'parent' => [
                'type'    => 'page_id',
                'page_id' => $parentPageId
            ],
            'properties' => [
                'title' => [
                    'title' => [
                        [
                            'type' => 'text',
                            'text' => [
                                'content' => $data['title']
                            ]
                        ],
                    ]
                ]
            ]
        ];

        // Add icon if provided
        if (!empty($data['icon'])) {
            $payload['icon'] = [
                'type'     => 'external',
                'external' => [
                    'url' => $data['icon']
                ]
            ];
        }

        // Add cover if provided
        if (!empty($data['cover'])) {
            $payload['cover'] = [
                'type'     => 'external',
                'external' => [
                    'url' => $data['cover']
                ]
            ];
        }

        // Add content block if provided
        if (!empty($data['content'])) {
            $payload['children'] = [
                [
                    'object'    => 'block',
                    'type'      => 'paragraph',
                    'paragraph' => [
                        'rich_text' => [
                            [
                                'type' => 'text',
                                'text' => [
                                    'content' => $data['content']
                                ]
                            ],
                        ]
                    ]
                ],
            ];
        }

        $this->setPayload($payload);
        $response = (array) $this->post('pages');

        return [
            'response'    => $response,
            'payload'     => $payload,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function createNotionPageAllFields($formData, $parentPageId, $titleId)
    {
        $properties = [];
        $emptyObject = json_decode('{}');
        foreach ($formData as $key => $value) {
            $properties[$value] = [$key => $emptyObject];
        }

        $payload = [
            'parent' => [
                'type'    => 'page_id',
                'page_id' => $parentPageId
            ],
            'title' => [
                [
                    'type' => 'text',
                    'text' => [
                        'content' => $titleId
                    ]
                ],
            ],
            'properties' => $properties
        ];
        $this->setPayload($payload);

        $response = (array) $this->post('databases');

        return [
            'response'    => $response,
            'payload'     => $payload,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function createDatabase($data, $page_id, $titleId)
    {
        return $this->createNotionPageAllFields($data, $page_id, $titleId);
    }

    public function createDatabaseItem($data, $databaseId)
    {
        $properties = [];

        foreach ($data as $field_name => $field_info) {
            $field_info_array = explode(',', $field_name);

            switch ($field_info_array[1]) {
                case 'title':
                    $properties[$field_info_array[0]] = [
                        'title' => [
                            [
                                'type' => 'text',
                                'text' => ['content' => $field_info]
                            ],
                        ]
                    ];

                    break;

                case 'rich_text':
                    $properties[$field_info_array[0]] = [
                        'rich_text' => [
                            [
                                'type' => 'text',
                                'text' => ['content' => $field_info]
                            ],
                        ]
                    ];

                    break;

                case 'select':
                    $properties[$field_info_array[0]] = [
                        'select' => [
                            'name' => $field_info
                        ]
                    ];

                    break;

                case 'multi_select':
                    $multi_values = explode(',', $field_info); // Assume comma separated for multi-select
                    $multi_options = array_map(
                        function ($val) {
                            return ['name' => trim($val)];
                        },
                        $multi_values
                    );
                    $properties[$field_info_array[0]] = [
                        'multi_select' => $multi_options
                    ];

                    break;

                case 'people':
                    $user_ids = explode(',', $field_info); // Assume comma separated Notion user IDs
                    $people = array_map(
                        function ($id) {
                            return [
                                'object' => 'user',
                                'id'     => trim($id)
                            ];
                        },
                        $user_ids
                    );
                    $properties[$field_info_array[0]] = [
                        'people' => $people
                    ];

                    break;

                case 'date':
                    $properties[$field_info_array[0]] = [
                        'date' => [
                            'start' => $field_info
                        ]
                    ];

                    break;

                case 'email':
                    $properties[$field_info_array[0]] = [
                        'email' => $field_info
                    ];

                    break;

                case 'url':
                    $properties[$field_info_array[0]] = [
                        'url' => $field_info
                    ];

                    break;

                case 'checkbox':
                    $properties[$field_info_array[0]] = [
                        'checkbox' => filter_var($field_info, FILTER_VALIDATE_BOOLEAN)
                    ];

                    break;
            }
        }

        $payload = [
            'parent' => [
                'database_id' => $databaseId
            ],
            'properties' => $properties
        ];

        $this->setPayload($payload);
        $response = (array) $this->post('pages');

        return [
            'response'    => $response,
            'payload'     => $payload,
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function getUser($userId)
    {
        $response = (array) $this->get("users/{$userId}");

        return [
            'response'    => $response,
            'payload'     => [$userId],
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function retrieveUser()
    {
        $response = (array) $this->get('users/me');

        return [
            'response'    => $response,
            'payload'     => [],
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function getDatabase($databaseId)
    {
        $response = (array) $this->get("databases/{$databaseId}");

        return [
            'response'    => $response,
            'payload'     => [$databaseId],
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function getPage($pageId)
    {
        $response = (array) $this->get("pages/{$pageId}");

        return [
            'response'    => $response,
            'payload'     => [$pageId],
            'status_code' => $this->http->getResponseCode()
        ];
    }

    public function allUsersList($starCursor, $pageSize)
    {
        $this->setQueryParam('start_cursor', $starCursor);
        $this->setQueryParam('page_size', $pageSize);
        $response = (array) $this->get('users');

        return [
            'response'    => $response,
            'payload'     => [[$starCursor, $pageSize]],
            'status_code' => $this->http->getResponseCode()
        ];
    }

    private function setQueryParam($key, $value)
    {
        if (!empty($value)) {
            $this->http->setQueryParam($key, $value);
        }
    }
}
