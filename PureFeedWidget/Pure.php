<?php
/**
 * @Author: Adam Jakab
 * @Licence: GNU GPLv3
 * @Copyright (c) 2020. Pure Feed Widget
 * @Package PureFeedWidget
 */

/**
 * An abstraction over Pure, to simulate the data source
 *
 * @package PureFeedWidget;
 */

namespace PureFeedWidget;

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
     * Get Persons research output from the Pure.
     *
     * @return string
     */
    protected function getPersonsOutput()
    {
        $PO = new PersonsOutput();
        $PO->setUrl($this->config["api_url"]);
        $PO->setApiKey($this->config["api_key"]);
        $PO->setOrganizationUuid($this->config["organization_uuid"]);
        $PO->setSize($this->config["size"]);
        $PO->setRendering($this->config["rendering"]);
        $PO->load();

        $renderer = new Renderer(['auto_reload' => true]);

        $context = [
            "elements" => $PO->getElements(),
            "description" => "Researchers found: "
        ];

        return $renderer->render("persons.twig", $context);
    }

    /**
     * @return string
     */
    protected function getResearchOutput()
    {
        $RO = new ResearchOutput();
        $RO->setUrl($this->config["api_url"]);
        $RO->setApiKey($this->config["api_key"]);
        $RO->setOrganizationUuid($this->config["organization_uuid"]);
        $RO->setSize($this->config["size"]);
        $RO->setRendering($this->config["rendering"]);
        $RO->load();

        $renderer = new Renderer(['auto_reload' => true]);

        $context = [
            "elements" => $RO->getElements(),
            "description" => "Publications found: "
        ];

        return $renderer->render("publications.twig", $context);
    }
}
