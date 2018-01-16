<?php
namespace GrossbergerGeorg\PHPDevTools\Tests\Fixer;

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

use GrossbergerGeorg\PHPDevTools\Fixer\NamespaceFirstFixer;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;

/**
 * Check if namespace_first fixer always puts the namespace on top
 *
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class NamespaceFirstFixerTest extends TestCase
{
    /**
     * @return array
     */
    public function namespaceDataProvider()
    {
        $result = [];
        $sets = ['NamespaceAfterComment', 'NamespaceFirst', 'NamespaceNoClass'];
        $dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
                  'Fixtures' . DIRECTORY_SEPARATOR . 'NamespaceFixer' . DIRECTORY_SEPARATOR;

        foreach ($sets as $set) {
            $result[] = [
                $set,
                file_get_contents($dir . $set . 'Src.php'),
                file_get_contents($dir . $set . 'Expected.php')
            ];
        }

        return $result;
    }
    /**
     * @dataProvider namespaceDataProvider
     * @param string $data
     * @param string $expected
     */
    public function testNamespaceFirstFixerPutsNamespaceAfterOpenTag($set, $data, $expected)
    {
        $file = $this->getMockBuilder(\SplFileInfo::class)->disableOriginalConstructor()->getMock();

        $actual = Tokens::fromCode($data);
        $fixer = new NamespaceFirstFixer();
        $fixer->fix($file, $actual);
        $this->assertSame($expected, $actual->generateCode(), 'Unexpected result for data set ' . $set);

        $fixer->fix($file, $actual);
        $this->assertSame($expected, $actual->generateCode(), 'Corrected data was changed with set ' . $set);
    }

    public function testNamespaceCorrectDoesNotChangeCode()
    {
        $dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
                 'Fixtures' . DIRECTORY_SEPARATOR . 'NamespaceFixer' . DIRECTORY_SEPARATOR;
        $data = file_get_contents($dir . 'NamespaceCorrect.php');

        $file = $this->getMockBuilder(\SplFileInfo::class)->disableOriginalConstructor()->getMock();

        $actual = Tokens::fromCode($data);
        $fixer = new NamespaceFirstFixer();
        $fixer->fix($file, $actual);

        $this->assertSame($data, $actual->generateCode());
    }
}
