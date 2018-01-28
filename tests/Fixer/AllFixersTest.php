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

use GrossbergerGeorg\PHPDevTools\Fixer\LowerHeaderCommentFixer;
use GrossbergerGeorg\PHPDevTools\Fixer\NamespaceFirstFixer;
use GrossbergerGeorg\PHPDevTools\Fixer\SingleEmptyLineFixer;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;

/**
 * Test combined fixer calls yield the desired output
 *
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class AllFixersTest extends TestCase
{
    public function dataProvider()
    {
        $variations = ['', 'NoNs'];
        $res = [];
        $dir = __DIR__ . '/../Fixtures/Combined/';

        foreach ($variations as $variation) {
            $sets = [
                'WrongOrder' . $variation,
                'NoComment' . $variation
            ];
            $exp = file_get_contents($dir . 'Expected' . $variation . '.php');

            foreach ($sets as $set) {
                $res[] = [
                    file_get_contents($dir . $set . '.php'),
                    $exp,
                    $set
                ];
            }
        }

        $res[] = [
            file_get_contents($dir . 'DeclareSrc.php'),
            file_get_contents($dir . 'DeclareExpected.php'),
            'Declare'
        ];

        return $res;
    }

    /**
     * @dataProvider dataProvider
     * @param string $data
     * @param string $expected
     * @param string $set
     */
    public function testConsecutiveFixerCalls($data, $expected, $set)
    {
        $header = <<<EOF
            This file is (c) 3000 by Someone

            It is free software; you can redistribute it and/or
            modify it under the terms of the Apache License 2.0

            For the full copyright and license information see
            <http://www.apache.org/licenses/LICENSE-2.0>
EOF;
        $file = $this->getMockBuilder(\SplFileInfo::class)->disableOriginalConstructor()->getMock();

        LowerHeaderCommentFixer::setHeader($header);

        $actual = Tokens::fromCode($data);
        $fixers = [
            new LowerHeaderCommentFixer(),
            new NamespaceFirstFixer(),
            new SingleEmptyLineFixer()
        ];

        foreach ($fixers as $fixer) {
            $fixer->fix($file, $actual);
        }

        $this->assertSame($expected, $actual->generateCode(), 'Unexpected result for data set ' . $set);
    }
}
