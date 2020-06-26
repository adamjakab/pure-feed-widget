<?php
/**
 * @Author: Adam Jakab
 * @Licence: GNU GPLv3
 * @Copyright (c) 2020. Pure Feed Widget
 */

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
 * Class Pure
 * @package PureFeedWidget
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
     *
     * @return string
     * @throws Exception
     * @todo: template should become a configuration option of the widget
     */
    public function getOutput()
    {
        if ($this->getConfigValue("endpoint") == "Research-Outputs") {
            $output = new ResearchOutput();
            $template = "publications.twig";
        } else if ($this->getConfigValue("endpoint") == "Persons") {
            $output = new PersonsOutput();
            $template = "persons.twig";
        } else {
            return "No suitable endpoint was selected.";
        }

        $output->setUrl($this->getConfigValue("api_url"));
        $output->setApiKey($this->getConfigValue("api_key"));
        $output->setOrganizationUuid($this->getConfigValue("organization_uuid"));
        $output->setSize($this->getConfigValue("size"));
        $output->setRendering($this->getConfigValue("rendering"));
        $output->load();

        $context = [
            "elements" => $output->getElements(),
            "description" => ""
        ];

        $renderer = new Renderer(['auto_reload' => true]);

        return $renderer->render($template, $context);
    }

    /**
     * @param $attrib
     * @return mixed
     * @throws Exception
     */
    protected function getConfigValue($attrib)
    {
        if (!array_key_exists($attrib, $this->config)) {
            throw new Exception(sprintf("Cnfiguration attribute[%s] does not exist!", $attrib));
        }

        return $this->config[$attrib];
    }
}
