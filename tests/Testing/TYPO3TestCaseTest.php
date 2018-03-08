<?php
declare(strict_types=1);

namespace GrossbergerGeorg\PHPDevTools\Tests\Testing;

/*
 * (c) 2018 by Georg Großberger <contact@grossberger-ge.org>
 *
 * This is free software; you can redistribute it and/or
 * modify it under the terms of the GPLv3 license
 *
 * For the full copyright and license information see
 * <https://www.gnu.org/licenses/gpl-3.0.html>
 */
use GrossbergerGeorg\PHPDevTools\Testing\TYPO3TestCase;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Core\ApplicationContext;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Description
 *
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class TYPO3TestCaseTest extends TYPO3TestCase
{
    public function testCommonConstantsAreDefined()
    {
        $this->assertTrue(defined('TYPO3_MODE'));
        $this->assertTrue(defined('LF'));
        $this->assertTrue(defined('TYPO3_REQUESTTYPE'));
        $this->assertTrue(defined('TYPO3_REQUESTTYPE_CLI'));
        $this->assertTrue(defined('TYPO3_REQUESTTYPE_BE'));
        $this->assertSame(TYPO3_REQUESTTYPE_BE | TYPO3_REQUESTTYPE_CLI, TYPO3_REQUESTTYPE);
        $this->assertInstanceOf(ApplicationContext::class, GeneralUtility::getApplicationContext());
    }

    public function testObjectManagerMock()
    {
        $obj = \SplFixedArray::fromArray([1, 2, 3]);
        $cls = get_class($obj);
        $om = $this->mockObjectManager([
            [$cls, $obj]
        ]);
        $this->assertInstanceOf(ObjectManager::class, $om);
        $this->assertSame($obj, $om->get($cls));
    }
}
