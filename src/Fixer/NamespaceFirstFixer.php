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
        $prev = null;

        foreach ($tokens as $i => $token) {
            if ($token->isGivenKind(T_NAMESPACE)) {
                $namespace = [$token];
                $tokens[$i] = new Token('');
            } elseif (is_array($namespace)) {
                $namespace[] = $token;
                $tokens[$i] = new Token('');

                if (trim($token->getContent()) === ';') {
                    break;
                }
            }
        }

        if (is_array($namespace)) {
            $tokens->insertAt(1, $namespace);
            $tokens[0] = new Token([T_OPEN_TAG, "<?php\n"]);
        }
    }
}
