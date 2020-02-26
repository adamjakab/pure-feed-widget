<?php


namespace PureFeedWidget\Entity;


use stdClass;

class Person
{
    /** @var  string  */
    private $pure_id;

    /** @var  string  */
    private $uuid;

    /** @var  string  */
    private $first_name;

    /** @var  string  */
    private $last_name;


    /**
     * Person constructor.
     * @param stdClass $item
     */
    public function __construct(stdClass $item)
    {
        $this->setUpFromRawItem($item);
    }

    protected function setUpFromRawItem(stdClass $item) {
        $this->setPureId($item->pureId);
        $this->setUuid($item->uuid);
        $this->setFirstName($item->name->firstName);
        $this->setLastName($item->name->lastName);
    }

    /**
     * @return string
     */
    public function getPureId(): string
    {
        print("PID: ");
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




}