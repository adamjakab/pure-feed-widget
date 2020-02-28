<?php
/**
 * @Author: Adam Jakab
 * @Licence: GNU GPLv3
 * @Copyright (c) 2020. Pure Feed Widget
 * @Package PureFeedWidget
 */

namespace PureFeedWidget\PureOutput;


use Exception;
use PureFeedWidget\Entity\Person;

class PersonsOutput extends Output
{
    /** @var string */
    private const __ENDPOINT__ = "/persons";


    public function __construct()
    {
        parent::__construct();
        $this->setApiEndpoint(self::__ENDPOINT__);

    }

    /**
     * This is a proxy method so that in future here we can rely on cached content instead of querying all the time
     */
    public function load()
    {
        $this->queryPure();
        $this->createElements();
    }

    /**
     * Creates Person elements from raw response
     */
    protected function createElements()
    {
        $raw = $this->getRawResponse();
        if (property_exists($raw, "items") && is_array($raw->items)) {
            foreach ($raw->items as $item) {
                $element = new Person($item);
                $this->addElement($element);
            }
        }
    }

    /**
     * Configures the request to be sent to the endpoint
     */
    protected function queryPure()
    {
        $requestBody = [];
        $requestBody["orderings"] = ["lastName"];
        if ($this->getOrganizationUuid()) {
            $requestBody["forOrganisations"] = [
                "uuids" => [$this->getOrganizationUuid()]
            ];
        }
        $requestBody["fields"] = [
            "pureId",
            "uuid",
            "name.firstName",
            "name.lastName",
            "info.*",
            "profilePhotos.url",
            "profileInformations.type.term.text.value",
            "profileInformations.value.text.value",
            "staffOrganisationAssociations.organisationalUnit.name.text.value",
            "renderings.format",
            "renderings.html"
        ];

        $requestHeaders = [];

        try {
            $this->sendRequestToPure($requestBody, $requestHeaders);
        } catch (Exception $e) {
            print($e->getMessage());
        }
    }

}