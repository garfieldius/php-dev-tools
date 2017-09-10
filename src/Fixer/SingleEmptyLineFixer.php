<?php namespace GrossbergerGeorg\PHPDevTools\Fixer;

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

use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * Ensures there are no consecutive empty lines
 *
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class SingleEmptyLineFixer extends BaseFixer
{
    public function fix(\SplFileInfo $file, Tokens $tokens)
    {
        for ($i = 0; $i < $tokens->count(); $i++) {
            if ($tokens[$i]->isGivenKind(T_WHITESPACE)) {
                if (strpos($tokens[$i]->getContent(), "\n") !== false) {
                    $content = explode("\n", $tokens[$i]->getContent());

                    if (count($content) > 3) {
                        $content = array_slice($content, -3);
                    }

                    $tokens[$i] = new Token([T_WHITESPACE, implode("\n", $content)]);
                }
            }
        }

        $tokens->clearEmptyTokens();
    }
}
