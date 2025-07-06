<?php

namespace BitApps\PiPro\src\Integrations\SendFox;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


final class SendFoxContact
{
    private $http;

    private $baseUrl;

    /**
     * SendFoxContactService constructor.
     *
     * @param $httpClient
     * @param $baseUrl
     */
    public function __construct($httpClient, $baseUrl)
    {
        $this->http = $httpClient;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Create sendfox contact
     *
     * @param array $data
     *
     * @return collection
     */
    public function createContact($data)
    {
        return $this->http->request($this->baseUrl . 'contacts', 'POST', $data);
    }

    /**
     * Get contact list
     *
     * @param array $data
     *
     * @return array
     */
    public function contactList($data)
    {
        return $this->http->request($this->baseUrl . 'contacts', 'GET', $data);
    }

    /**
     *  Unsubscribe email
     *
     * @param string $email
     *
     * @return collection
     */
    public function unsubscribeEmail($email)
    {
        return $this->http->request($this->baseUrl . 'unsubscribe', 'POST', ['email' => $email]);
    }

    /**
     * GetContact by email
     *
     * @param string $email
     *
     * @return collection
     */
    public function getContactByEmail($email)
    {
        return $this->http->request($this->baseUrl . 'contacts?=' . $email, 'POST', []);
    }

    /**
     * Get contact by id
     *
     * @param int $id
     *
     * @return collection
     */
    public function getContactById($id)
    {
        return $this->http->request($this->baseUrl . 'contacts/' . $id, 'GET', []);
    }

    /**
     * List create
     *
     * @param array $data
     *
     * @return collection
     */
    public function listCreate($data)
    {
        return $this->http->request($this->baseUrl . 'lists', 'POST', $data);
    }

    /**
     * Get All List
     *
     * @param mixed $limit
     *
     * @return array
     */
    public function allLists($limit)
    {
        $url = empty($limit) ? $this->baseUrl . 'lists' : $this->baseUrl . 'lists?page=' . $limit;
        return $this->http->request($url, 'GET', []);
    }

    /**
     * Get List By Id
     *
     * @param int $id
     *
     * @return collection
     */
    public function listById($id)
    {
        return $this->http->request($this->baseUrl . 'lists/' . $id, 'GET', []);
    }

    /**
     * Contact remove from list
     *
     * @param int $listId
     * @param int $contactId
     *
     * @return collection
     */
    public function contactRemoveFromList($listId, $contactId)
    {
        return $this->http->request($this->baseUrl . 'lists/' . $listId . '/contacts/' . $contactId, 'POST', []);
    }
}
