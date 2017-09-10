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
 * A custom variant of the HeaderCommentFixer of php-cs-fixer
 * which puts the header after the namespace declaration
 *
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
class LowerHeaderCommentFixer extends BaseFixer
{
    private static $headerComment = '';

    /**
     * Set the header to use for PHP files
     *
     * Must not containt the comment tokens /* or //
     *
     * @param string $header
     */
    public static function setHeader($header)
    {
        $header = trim((string) $header);

        if (!empty($header)) {
            self::$headerComment = "/*\n";

            foreach (explode("\n", $header) as $line) {
                self::$headerComment .= rtrim(' * ' . trim($line)) . "\n";
            }

            self::$headerComment .= ' */';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return self::$headerComment !== '' && $tokens->isMonolithicPhp();
    }

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, Tokens $tokens)
    {
        $index = 1;
        $hasNs = false;

        for ($i = 1; $i < $tokens->count(); $i++) {
            if ($tokens[$i]->isGivenKind(T_NAMESPACE)) {
                $hasNs = true;

                while ($tokens[$i]->getContent() !== ';') {
                    $i++;
                }
                $i++;
                $index = $i + 1;
            } elseif (!$tokens[$i]->isWhitespace() && !$tokens[$i]->isGivenKind(T_COMMENT)) {
                break;
            }
            $tokens[$i] = new Token('');
        }

        $tokens->insertAt($index, [
            new Token([T_WHITESPACE, "\n" . ($hasNs ? "\n" : '')]),
            new Token([T_COMMENT, self::$headerComment]),
            new Token([T_WHITESPACE, "\n\n"])
        ]);

        $tokens->clearEmptyTokens();
    }
}
