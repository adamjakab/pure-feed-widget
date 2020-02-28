<?php
/**
 * @Author: Adam Jakab
 * @Licence: GNU GPLv3
 * @Copyright (c) 2020. Pure Feed Widget
 * @Package PureFeedWidget
 */

namespace PureFeedWidget\Entity;


use stdClass;

class Person
{
    /** @var  string */
    private $pure_id = "";

    /** @var  string */
    private $uuid = "";

    /** @var  string */
    private $first_name = "";

    /** @var  string */
    private $last_name = "";

    /** @var string */
    private $portal_url = "";

    /** @var string */
    private $photo_url = "";


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
        $this->setFirstName($item->name->firstName);
        $this->setLastName($item->name->lastName);
        $this->setPortalUrl($item->info->portalUrl);
        $this->setPhotoUrl($item->profilePhotos);
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
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    protected function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     */
    protected function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->first_name . " " . $this->last_name;
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
    public function getPhotoUrl(): string
    {
        return $this->photo_url;
    }

    /**
     * Get the first available url
     * @param array|null $photos
     */
    protected function setPhotoUrl($photos): void
    {
        if (is_array($photos)) {
            foreach ($photos as $photo) {
                if (property_exists($photo, "url")) {
                    if (!is_null($photo->url) && !empty($photo->url)) {
                        $this->photo_url = $photo->url;
                        break;
                    }
                }
            }
        }
    }


}