<?php


namespace PureFeedWidget\PureOutput;


use Exception;
use stdClass;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\FilesystemLoader as TwigFSLoader;


class Output
{
    /** @var string */
    private $url;

    /** @var string */
    private $api_key;

    /** @var string */
    private $organization_uuid;

    /** @var string  */
    private $api_endpoint = "";

    /** @var string  */
    private $projectRootFolder = "";

    /** @var int  */
    private $size = 5;

    /** @var string  */
    private $rendering = "None";

    /** @var stdClass */
    private $raw_response;

    /** @var array  */
    private $elements = [];


    public function __construct()
    {
        $this->projectRootFolder = dirname(dirname(__DIR__));
    }

    /**
     * @param array $requestBody
     * @param array $headers
     * @throws Exception
     */
    protected function sendRequestToPure(array $requestBody, array $headers )
    {
        $url = trim($this->url, "/") . '/' . trim($this->api_endpoint, "/")
            . '?' . http_build_query(['apiKey' => $this->api_key]);


        $requestBody["size"] = $this->getSize();
        $requestBody["offset"] = 0;
        $requestBody["locales"] = ["en_GB"];

        $headers['Accept'] = 'application/json';
        $headers['Content-Type'] = 'application/json';

        $args = [
            'body' => json_encode($requestBody),
            'headers' => $headers,
        ];

        $response = wp_remote_post($url, $args);
        if (is_wp_error($response)) {
            throw new Exception("WP is unable process the request. "
                . $response->get_error_message());
        }
        if (!is_array($response)) {
            throw new Exception("Bad response from server! Expected array!");
        }

        $response_body = wp_remote_retrieve_body($response);
        if(empty($response_body)){
            throw new Exception("Empty response from server!");
        }

        $this->raw_response = json_decode($response_body);
        if(json_last_error() != JSON_ERROR_NONE){
            throw new Exception("Unable to json decode server response! " . json_last_error_msg());
        }

        # print("<hr />" . htmlentities($response_body) . "<hr />");
    }


    public function getRenderedOutput(string $template, array $context = [])
    {
        $twig = $this->getTwig();
        $template = $twig->load($template);
        $context["name"] = "PERSONS";
        $context["elements"] = $this->getElements();
        return $template->render($context);
    }

    /**
     * @return TwigEnvironment
     */
    protected function getTwig()
    {
        $loader = new TwigFSLoader($this->projectRootFolder . "/templates");

        $twigEnvOptions = [
            'cache' => $this->projectRootFolder . "/tmp",
            'debug' => true,
            'auto_reload' => true,
            'strict_variables' => true
        ];

        return new TwigEnvironment($loader, $twigEnvOptions);
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
        $this->url = $url;
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
        $this->api_endpoint = $api_endpoint;
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
        return $this->raw_response;
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