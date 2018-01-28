<?php
namespace GrossbergerGeorg\PHPDevTools\Fixer;

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

use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * Fixer to ensure the namespace comes as first element
 *
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class NamespaceFirstFixer extends BaseFixer
{
    public function fix(\SplFileInfo $file, Tokens $tokens)
    {
        $namespace = null;
        $insertAt = 1;
        $replaces = [];

        for ($i = 0; $i < count($tokens); $i++) {
            if ($tokens[$i]->isGivenKind(T_DECLARE)) {
                while (true) {
                    if ($i > count($tokens)) {
                        return;
                    }

                    $i++;
                    $insertAt = $i;

                    if ($tokens[$i - 1]->getContent() === ';') {
                        $i--;
                        break;
                    }
                }

                if ($tokens[$i + 1]->getContent() === "\n") {
                    $insertAt++;
                }
            } elseif ($tokens[$i]->isGivenKind(T_NAMESPACE)) {
                $namespace = [new Token([T_NAMESPACE, 'namespace'])];
                $replaces[] = $i;
            } elseif (is_array($namespace)) {
                $namespace[] = $tokens[$i];
                $replaces[] = $i;

                if ($tokens[$i]->getContent() === ';') {
                    break;
                }
            }
        }

        if ($insertAt === 1) {
            $tokens[0] = new Token([T_OPEN_TAG, "<?php\n"]);
        }

        if (is_array($namespace)) {
            foreach ($replaces as $i) {
                $tokens[$i] = new Token('');
            }

            $tokens->clearEmptyTokens();
            $tokens->insertAt($insertAt, $namespace);

            $prev = $tokens[$insertAt - 1]->getContent();

            if (strpos($prev, "\n") === false && $insertAt > 1) {
                $tokens->insertAt($insertAt, [new Token([T_WHITESPACE, "\n"])]);
            }
        }
    }
}
