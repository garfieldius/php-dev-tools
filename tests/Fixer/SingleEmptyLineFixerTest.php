<?php namespace GrossbergerGeorg\PHPDevTools\Tests\Fixer;

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

use GrossbergerGeorg\PHPDevTools\Fixer\SingleEmptyLineFixer;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;

/**
 * Test the single empty line fixer
 *
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class SingleEmptyLineFixerTest extends TestCase
{
    public function singleEmptyLineData()
    {
        $result = [];
        $sets = 2;
        $dir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
                  'Fixtures' . DIRECTORY_SEPARATOR . 'SingleEmptyLineFixer' . DIRECTORY_SEPARATOR;

        for ($i = 1; $i <= $sets; $i++) {
            $name = str_pad((string) $i, 3, '0', STR_PAD_LEFT);
            $result[] = [
                $name,
                file_get_contents($dir . $name . 'Src.php'),
                file_get_contents($dir . $name . 'Expected.php')
            ];
        }

        return $result;
    }

    /**
     * @dataProvider singleEmptyLineData
     * @param string $set
     * @param string $src
     * @param string $expected
     */
    public function testSingleEmptyLine($set, $src, $expected)
    {
        $file = $this->getMockBuilder(\SplFileInfo::class)->disableOriginalConstructor()->getMock();

        $actual = Tokens::fromCode($src);
        $fixer = new SingleEmptyLineFixer();
        $fixer->fix($file, $actual);

        $this->assertSame($expected, $actual->generateCode(), 'Invalid output for data set ' . $set);
    }
}
