<?php


namespace PureFeedWidget\PureOutput;

use Exception;
use stdClass;
use WP_HTTP_Requests_Response;


class Output
{
    /** @var string */
    private $url;

    /** @var string */
    private $api_key;

    /** @var string */
    private $organization_uuid;

    /** @var string */
    private $api_endpoint = "";

    /** @var int */
    private $size = 5;

    /** @var string */
    private $rendering = "None";

    /** @var stdClass */
    private $raw_response;

    /** @var array */
    private $elements = [];


    public function __construct()
    {
        /**/
    }

    /**
     * @param array $requestBody
     * @param array $headers
     * @throws Exception
     */
    protected function sendRequestToPure(array $requestBody, array $headers)
    {
        $requestBody["size"] = $this->getSize();
        $requestBody["offset"] = 0;
        $requestBody["locales"] = ["en_GB"];

        $headers['api-key'] = $this->api_key;
        $headers['Accept'] = 'application/json';
        $headers['Content-Type'] = 'application/json';

        $args = [
            'body' => json_encode($requestBody),
            'headers' => $headers,
        ];

        $response = wp_remote_post($this->getFullEndpointUrl(), $args);
        if (is_wp_error($response)) {
            throw new Exception("WP is unable process the request. "
                . $response->get_error_message());
        }
        if (!is_array($response) || !array_key_exists("http_response", $response)) {
            throw new Exception("Bad response from server! Expected array!");
        }

        /** @var WP_HTTP_Requests_Response $http_response */
        $http_response = $response["http_response"];
        if($http_response->get_status() != 200) {
            throw new Exception("Bad request status! " . $http_response->get_data());
        }

        $response_body = wp_remote_retrieve_body($response);
        if (empty($response_body)) {
            throw new Exception("Empty response from server!");
        }

        $this->raw_response = json_decode($response_body);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new Exception("Unable to json decode server response! " . json_last_error_msg());
        }

        #print("<hr />" . htmlentities($response_body) . "<hr />");
    }



    /* Getters and Setters */

    public function getFullEndpointUrl()
    {
        return $this->getUrl() . '/' . $this->getApiEndpoint();
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = trim($url, "/");
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->api_key;
    }

    /**
     * @param string $api_key
     */
    public function setApiKey(string $api_key): void
    {
        $this->api_key = $api_key;
    }

    /**
     * @return string
     */
    public function getOrganizationUuid(): string
    {
        return $this->organization_uuid;
    }

    /**
     * @param string $organization_uuid
     */
    public function setOrganizationUuid(string $organization_uuid): void
    {
        $this->organization_uuid = $organization_uuid;
    }

    /**
     * @return string
     */
    public function getApiEndpoint(): string
    {
        return $this->api_endpoint;
    }

    /**
     * @param string $api_endpoint
     */
    protected function setApiEndpoint(string $api_endpoint): void
    {
        $this->api_endpoint = trim($api_endpoint, "/");
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getRendering(): string
    {
        return $this->rendering;
    }

    /**
     * @param string $rendering
     */
    public function setRendering(string $rendering): void
    {
        $this->rendering = $rendering;
    }

    /**
     * @return stdClass
     */
    public function getRawResponse(): stdClass
    {
        return  $this->raw_response instanceof stdClass ? $this->raw_response : new stdClass();
    }

    /**
     * @return array
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @return int
     */
    public function getElementCount(): int
    {
        return count($this->elements);
    }

    /**
     * @param mixed $element
     */
    protected function addElement($element): void
    {
        array_push($this->elements, $element);
    }


}