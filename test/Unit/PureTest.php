<?php
/**
 * @Author: Adam Jakab
 * @Licence: GNU GPLv3
 * @Package PureFeedWidget
 */

namespace PureFeedWidgetTest\Unit;


use ArgumentCountError;
use PHPUnit\Framework\TestCase;
use PureFeedWidget\Pure;
use ReflectionClass;
use ReflectionException;
use TypeError;

/**
 * Class PureTest
 * @package PureFeedWidgetTest\Unit
 */
class PureTest extends TestCase
{
    /** @var ReflectionClass */
    protected $ref;

    public function setUp()
    {
        parent::setUp();
        $this->ref = new ReflectionClass('PureFeedWidget\Pure');
    }

    /**
     * @throws ReflectionException
     */
    public function testConstructor()
    {
        $config = ["a" => 123];

        /** @var Pure $pure */
        $pure = $this->ref->newInstance($config);

        $prop = $this->ref->getProperty("config");
        $prop->setAccessible(true);
        $ref_config = $prop->getValue($pure);

        $this->assertIsArray($ref_config);
        $this->assertEquals($config, $ref_config);
    }

    /**
     * @expectedException ArgumentCountError
     */
    public function testConstructorArgCount()
    {
        $this->ref->newInstance();
    }

    /**
     * @expectedException TypeError
     */
    public function testConstructorException()
    {
        $this->ref->newInstance("");
    }


}