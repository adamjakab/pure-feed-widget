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
        if ($this->config['endpoint'] == "Research-Outputs") {
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
        return $PO->getRenderedOutput("persons.twig", ["description" => "Researchers found: "]);
    }

    /**
     * @return string
     */
    protected function getResearchOutput()
    {
        $RO = new ResearchOutput();

        return $RO->getRenderedOutput("publications.twig", ["description" => "Publications found: "]);
    }
}
