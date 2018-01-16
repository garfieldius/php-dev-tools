<?php
namespace GrossbergerGeorg\PHPDevTools\Testing;

/*
 * This file is (c) 2018 by Georg Großberger
 * <contact@grossberger-ge.org> - <https://grossberger-ge.org/>
 *
 * It is free software; you can redistribute it and/or
 * modify it under the terms of the Apache License 2.0
 *
 * For the full copyright and license information see
 * the file LICENSE distributed with the source code
 * or <https://www.apache.org/licenses/LICENSE-2.0>
 */

use TYPO3\CMS\Core\Core\ApplicationContext;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Setup TYPO3 related information before running a test
 *
 * This allows calling TYPO3 API functions without setting
 * up an entire TYPO3 context
 *
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
abstract class TYPO3TestCase extends AbstractTestCase
{
    public static function setUpBeforeClass()
    {
        if (!defined('LF')) {
            define('LF', "\n");
        }

        if (!defined('CR')) {
            define('CR', "\r");
        }

        if (!defined('CRLF')) {
            define('CRLF', "\r\n");
        }

        if (!defined('PATH_site')) {
            define('PATH_site', getcwd() ?: '/');
        }

        if (!defined('TYPO3_MODE')) {
            define('TYPO3_MODE', 'BE');
        }

        if (!defined('TYPO3_REQUESTTYPE')) {
            define('TYPO3_REQUESTTYPE_FE', 1);
            define('TYPO3_REQUESTTYPE_BE', 2);
            define('TYPO3_REQUESTTYPE_CLI', 4);
            define('TYPO3_REQUESTTYPE_AJAX', 8);
            define('TYPO3_REQUESTTYPE_INSTALL', 16);
            define('TYPO3_REQUESTTYPE', TYPO3_REQUESTTYPE_BE | TYPO3_REQUESTTYPE_CLI);
        }

        if (!GeneralUtility::getApplicationContext()) {
            $ctx = new ApplicationContext(getenv('TYPO3_CONTEXT') ?: 'Testing');
            GeneralUtility::presetApplicationContext($ctx);
        }
    }

    /**
     * @param array $returnMap
     * @return \PHPUnit\Framework\MockObject\Builder\InvocationMocker|ObjectManager
     */
    protected function mockObjectManager(array $returnMap = [])
    {
        return $this->makeMock(ObjectManager::class)
            ->expects($this->any())
            ->method('get')
            ->willReturnMap($returnMap);
    }
}
