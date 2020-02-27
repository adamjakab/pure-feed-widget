<?php

namespace PureFeedWidget\PureOutput;


use Exception;
use PureFeedWidget\Entity\Research;

class ResearchOutput extends Output
{
    /** @var string */
    private const __ENDPOINT__ = "/research-outputs";


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
                $element = new Research($item);
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
        $requestBody["orderings"] = ["-publicationYear", "authorLastName"];
        if ($this->getOrganizationUuid()) {
            $requestBody["forOrganisationalUnits"] = [
                "uuids" => [$this->getOrganizationUuid()]
            ];
        }
        $requestBody["fields"] = [
            "pureId",
            "uuid",
            "title.value",
            "info.portalUrl",
            "managingOrganisationalUnit.name.text.value",
            "confidential",
            "abstract.text.value",
            "publisher.name.text.value",
            "type.term.text.value",
            "category.term.text.value",
            "language.term.text.value",
            "mainResearchArea.term.text.value",
            "visibility.value.text.value",
            "publicationStatuses.publicationDate.year",
            "publicationStatuses.publicationStatus.term.text.value",
            "personAssociations.pureId",
            "personAssociations.name.firstName",
            "personAssociations.name.lastName",
            "personAssociations.personRole.term.text.value",
            "organisationalUnits.uuid",
            "organisationalUnits.name.text.value",
            "renderings.format",
            "renderings.html"
        ];

        if ($this->getRendering() != "None") {
            $requestBody["linkingStrategy"] = "portalLinkingStrategy";
            $requestBody["renderings"] = [$this->getRendering()];
        }

        $requestHeaders = [];

        try {
            $this->sendRequestToPure($requestBody, $requestHeaders);
        } catch (Exception $e) {
            print($e->getMessage());
        }
    }

}