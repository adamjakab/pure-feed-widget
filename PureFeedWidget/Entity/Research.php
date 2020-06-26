<?php
/**
 * @Author: Adam Jakab
 * @Licence: GNU GPLv3
 * @Copyright (c) 2020. Pure Feed Widget
 */

namespace PureFeedWidget\Entity;

use stdClass;

/**
 * Class Research
 * @package PureFeedWidget\Entity
 */
class Research
{
    /** @var  string */
    private $pure_id = "";

    /** @var  string */
    private $uuid = "";

    /** @var  string */
    private $title = "";

    /** @var string */
    private $portal_url = "";

    /** @var  string */
    private $managing_org_unit = "";

    /** @var  boolean */
    private $confidential = false;

    /** @var  string */
    private $abstract = "";

    /** @var  string */
    private $publisher = "";

    /** @var  string */
    private $type = "";

    /** @var  string */
    private $category = "";

    /** @var  string */
    private $language = "";

    /** @var  string */
    private $research_area = "";

    /** @var  string */
    private $visibility = "";

    /** @var  string */
    private $publication_year = "";

    /** @var  string */
    private $publication_status = "";

    /** @var  string */
    private $pure_rendered_format = "";

    /** @var  string */
    private $pure_rendered_html = "";

    /** @var  array */
    private $persons = "";

    /** @var  array */
    private $org_units = "";


    /**
     * Person constructor.
     * @param stdClass $item
     */
    public function __construct(stdClass $item)
    {
        $this->setUpFromRawItem($item);
    }

    protected function setUpFromRawItem(stdClass $item)
    {
        $this->setPureId($item->pureId);
        $this->setUuid($item->uuid);
        $this->setTitle($item->title->value);
        $this->setPortalUrl($item->info->portalUrl);
        $this->setManagingOrgUnit($this->getFirstValue($item->managingOrganisationalUnit->name->text));
        $this->setAbstract($this->getFirstValue($item->abstract->text));
        $this->setConfidential($item->confidential);
        $this->setPublisher($this->getFirstValue($item->publisher->name->text));
        $this->setType($this->getFirstValue($item->type->term->text));
        $this->setCategory($this->getFirstValue($item->category->term->text));
        $this->setLanguage($this->getFirstValue($item->language->term->text));
        $this->setResearchArea($this->getFirstValue($item->mainResearchArea->term->text));
        $this->setVisibility($this->getFirstValue($item->visibility->value));
        //
        if (count($item->publicationStatuses[0]) > 0) {
            $firstPublicationStatus = $item->publicationStatuses[0];
            $this->setPublicationYear($firstPublicationStatus->publicationDate->year);
            $this->setPublicationStatus($this->getFirstValue($firstPublicationStatus->publicationStatus->term->text));
        }
        //
        if (count($item->renderings[0]) > 0) {
            $this->setPureRenderedFormat($item->renderings[0]->format);
            $this->setPureRenderedHtml($item->renderings[0]->html);
        }
        //
        // $persons
        // $org_units
    }

    /**
     * @param mixed $itemElement
     * @return string
     */
    protected function getFirstValue($itemElement)
    {
        $answer = "";

        if (is_array($itemElement) && count($itemElement) > 0) {
            if (property_exists($itemElement[0], "value")) {
                $answer = $itemElement[0]->value;
            }
        }

        return $answer;
    }

    /**
     * @return string
     */
    public function getPureId(): string
    {
        return $this->pure_id;
    }

    /**
     * @param string $pure_id
     */
    protected function setPureId(string $pure_id): void
    {
        $this->pure_id = $pure_id;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    protected function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    protected function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getPortalUrl(): string
    {
        return $this->portal_url;
    }

    /**
     * @param string $portal_url
     */
    protected function setPortalUrl(string $portal_url): void
    {
        $this->portal_url = $portal_url;
    }

    /**
     * @return string
     */
    public function getManagingOrgUnit(): string
    {
        return $this->managing_org_unit;
    }

    /**
     * @param string $managing_org_unit
     */
    protected function setManagingOrgUnit(string $managing_org_unit): void
    {
        $this->managing_org_unit = $managing_org_unit;
    }

    /**
     * @return bool
     */
    public function isConfidential(): bool
    {
        return $this->confidential;
    }

    /**
     * @param bool $confidential
     */
    protected function setConfidential(bool $confidential): void
    {
        $this->confidential = $confidential;
    }

    /**
     * @return string
     */
    public function getAbstract(): string
    {
        $abstract = html_entity_decode($this->abstract);
        $abstract = str_ireplace('<br/>', ' ', $abstract);
        $abstract = strip_tags($abstract);

        return html_entity_decode($abstract);
    }

    /**
     * @param string $abstract
     */
    protected function setAbstract(string $abstract): void
    {
        $this->abstract = $abstract;
    }

    /**
     * @return string
     */
    public function getPublisher(): string
    {
        return $this->publisher;
    }

    /**
     * @param string $publisher
     */
    protected function setPublisher(string $publisher): void
    {
        $this->publisher = $publisher;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    protected function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    protected function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    protected function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getResearchArea(): string
    {
        return $this->research_area;
    }

    /**
     * @param string $research_area
     */
    protected function setResearchArea(string $research_area): void
    {
        $this->research_area = $research_area;
    }

    /**
     * @return string
     */
    public function getVisibility(): string
    {
        return $this->visibility;
    }

    /**
     * @param string $visibility
     */
    protected function setVisibility(string $visibility): void
    {
        $this->visibility = $visibility;
    }

    /**
     * @return string
     */
    public function getPublicationYear(): string
    {
        return $this->publication_year;
    }

    /**
     * @param string $publication_year
     */
    protected function setPublicationYear(string $publication_year): void
    {
        $this->publication_year = $publication_year;
    }

    /**
     * @return string
     */
    public function getPublicationStatus(): string
    {
        return $this->publication_status;
    }

    /**
     * @param string $publication_status
     */
    protected function setPublicationStatus(string $publication_status): void
    {
        $this->publication_status = $publication_status;
    }

    /**
     * @return string
     */
    public function getPureRenderedFormat(): string
    {
        return $this->pure_rendered_format;
    }

    /**
     * @param string $pure_rendered_format
     */
    protected function setPureRenderedFormat(string $pure_rendered_format): void
    {
        $this->pure_rendered_format = $pure_rendered_format;
    }

    /**
     * @return string
     */
    public function getPureRenderedHtml(): string
    {
        return $this->pure_rendered_html;
    }

    /**
     * @param string $pure_rendered_html
     */
    protected function setPureRenderedHtml(string $pure_rendered_html): void
    {
        $this->pure_rendered_html = $pure_rendered_html;
    }

    /**
     * @return array
     */
    public function getPersons(): array
    {
        return $this->persons;
    }

    /**
     * @param array $person
     */
    protected function addPerson(array $person): void
    {
        array_push($this->persons, $person);
    }

    /**
     * @return array
     */
    public function getOrgUnits(): array
    {
        return $this->org_units;
    }

    /**
     * @param array $org_unit
     */
    protected function addOrgUnit(array $org_unit): void
    {
        array_push($this->org_units, $org_unit);
    }
}
