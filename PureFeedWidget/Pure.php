<?php
/**
 * An abstraction over Pure, to simulate the data source
 *
 * @package PureFeedWidget;
 */

namespace PureFeedWidget;



use Exception;
use PureFeedWidget\PureOutput\PersonsOutput;
use PureFeedWidget\PureOutput\ResearchOutput;
use SimpleXMLElement;
use function wp_remote_post;
use function wp_remote_retrieve_body;

/**
 * A Pure API representation.
 */
class Pure
{
    /** @var array */
    protected $config;

    /**
     * Constructs a data source.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        if($this->config['endpoint'] == "Research-Outputs")
        {
            $out = $this->getResearchOutput();
        } else if ($this->config['endpoint'] == "Persons") {
            $out = $this->getPersonsOutput();
        } else {
            $out = "No suitable endpoint was selected.";
        }

        return $out;
    }

    /**
     * Get research output from the Pure.
     *
     * @return string
     */
    protected function getPersonsOutput()
    {
        $PO = new PersonsOutput();
        $PO->setUrl($this->config["url"]);
        $PO->setApiKey($this->config["api_key"]);
        $PO->setOrganizationUuid($this->config["organization_uuid"]);
        $PO->setSize($this->config["size"]);
        $PO->setRendering($this->config["rendering"]);
        $PO->load();
        return $PO->getRenderedOutput("persons.twig");
    }

    /**
     * @return string
     */
    protected function getResearchOutput()
    {
        $RO = new ResearchOutput();

        return $RO->getRenderedOutput();
    }



    /**
     * Get research output from the Pure.
     *
     * @return string
     * @throws Exception
     */
    public function getResearchOutput_old()
    {
        $research_outputs = [];

        $endpoint = 'research-outputs';
        $order = '-publicationYear'; // Not exposed as a parameter.

        $params = [
            'size' => $size,
            'linkingStrategy' => 'portalLinkingStrategy',
            'locales' => ['locale' => 'en_GB'],
            /*'renderings' => ['rendering' => $rendering],*/
            'orderings' => ['ordering' => $order],
            /*publicationStatuses' => ['publicationStatus' => '/dk/atira/pure/researchoutput/status/published'],*/
            'publicationStatuses' => '/dk/atira/pure/researchoutput/status/published',
            /*'forOrganisationalUnits' => ['uuids' => ['uuid' => $org]],*/
        ];

        // This is the wrong place for this; would be nice if the XML
        // conversion was done from the query function, but these
        // higher level operations need to provide the top level XML
        // root element for each endpoint. Maybe refactor queries as a
        // hierarchy of classes? Or modify the function.
        $query = new SimpleXMLElement('<researchOutputsQuery/>');
        $this->array_to_xml($query, $params);

        print sprintf('PARAMS: <pre>%s</pre>', json_encode($params));


        $xml = $this->query($endpoint, $query);

        if ($xml) {
            foreach ($xml->xpath('//result/items//renderings/rendering') as $item) {
                $research_outputs[] = new Publication($item, $rendering);
            }
        }


        return $research_outputs;
    }

    /**
     * Query the API
     *
     * @param string $endpoint API endpoint, ie resource type.
     * @param SimpleXMLElement $query Query parameters as an SimpleXMLElement.
     *
     * @return SimpleXMLElement            Representation of the response.
     * @throws Exception
     */
    private function query(string $endpoint, SimpleXMLElement $query)
    {
        $xml = false;

        $url = $this->url . '/' . $endpoint . '?' . http_build_query(['apiKey' => $this->api_key]);

        $args = [
            'body' => $query->asXML(),
            /*'body'    => '<researchOutputsQuery></researchOutputsQuery>',*/
            'headers' => ['Content-Type' => 'application/xml'],
        ];


        $response = wp_remote_post($url, $args);
        if (is_array($response)) {
            $response_body = wp_remote_retrieve_body($response);
            if ($response_body) {
                $xml = simplexml_load_string($response_body);
            }
        }

        if (!$xml) {
            throw new Exception("Unable to parse response!");
        }

        print sprintf('XML: <pre>%s</pre>', htmlentities($xml->saveXML()));

        return $xml;
    }

    /**
     * Populates and XML element with an array, in place.
     *
     * @param SimpleXMLElement $object XML object to populate.
     * @param array $data Data to push to the XML object.
     *
     * @author Francis Lewis
     *
     * From here https://stackoverflow.com/a/19987539/1760439
     */
    private function array_to_xml(SimpleXMLElement $object, array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $new_object = $object->addChild($key);
                $this->array_to_xml($new_object, $value);
            } else {
                // if the key is an integer, it needs text with it to actually work.
                if ($key === (int)$key) {
                    $key = "$key";
                }

                $object->addChild($key, $value);
            }
        }
    }
}
