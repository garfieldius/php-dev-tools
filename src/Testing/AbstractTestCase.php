<?php
namespace GrossbergerGeorg\PHPDevTools\Testing;

/*
 * This file is (c) 2017 by Georg Großberger
 * <contact@grossberger-ge.org> - <https://grossberger-ge.org/>
 *
 * It is free software; you can redistribute it and/or
 * modify it under the terms of the Apache License 2.0
 *
 * For the full copyright and license information see
 * the file LICENSE distributed with the source code
 * or <https://www.apache.org/licenses/LICENSE-2.0>
 */

use PHPUnit\Framework\TestCase;

/**
 * Add simple helper methods to any PHPUnit test case
 *
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * Sets the given data as property value in the object
     *
     * Uses reflection to set properties of protected and
     * private properties.
     *
     * @param object $obj
     * @param string $property
     * @param mixed $value
     */
    protected function inject($obj, $property, $value)
    {
        $reflObj = new \ReflectionObject($obj);
        $reflProp = $reflObj->getProperty($property);

        $reflProp->setAccessible(true);
        $reflProp->setValue($obj, $value);
    }

    /**
     * Generates a basic mock for the given class
     *
     * Constructor is disabled!
     *
     * @param string $cls
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function makeMock($cls)
    {
        return $this->getMockBuilder($cls)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
