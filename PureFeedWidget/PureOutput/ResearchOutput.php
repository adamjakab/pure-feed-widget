<?php
namespace PureFeedWidget\PureOutput;


class ResearchOutput extends Output
{
    /** @var string  */
    private const __ENDPOINT__ = "/research-outputs";


    public function __construct()
    {
        parent::__construct();
        $this->setApiEndpoint(self::__ENDPOINT__);
    }


    public function load() {

    }

}