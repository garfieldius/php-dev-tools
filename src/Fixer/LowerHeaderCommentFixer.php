<?php
namespace GrossbergerGeorg\PHPDevTools\Fixer;

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
    const YEAR_MARKER = '__YEAR__';

    private static $headerComment = '';

    private static $testComment = '';

    /**
     * Set the header to use for PHP files
     *
     * Just the text of the header, must not containt the comment tokens /* or //
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

        if (stripos(self::$headerComment, self::YEAR_MARKER) !== false) {
            self::$testComment = '/' . preg_quote(self::$headerComment, '/') . '/';
            self::$testComment = str_replace(self::YEAR_MARKER, '[0-9]{4}', self::$testComment);
            self::$headerComment = str_replace(self::YEAR_MARKER, date('Y'), self::$headerComment);
        }
    }

    public static function getFullHeader()
    {
        return self::$headerComment;
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
        $hasCodeBefore = false;
        $clearIndexes = [];

        for ($i = 1; $i < $tokens->count(); $i++) {
            if ($tokens[$i]->isGivenKind(T_NAMESPACE) || $tokens[$i]->isGivenKind(T_DECLARE)) {
                $hasCodeBefore = true;

                while ($tokens[$i]->getContent() !== ';') {
                    $i++;
                }
                $i++;
                $index = $i + 1;
            } elseif (!$tokens[$i]->isWhitespace() && !$tokens[$i]->isGivenKind(T_COMMENT)) {
                break;
            } elseif (self::$testComment && $tokens[$i]->isComment()) {
                if (preg_match(self::$testComment, $tokens[$i]->getContent())) {
                    return;
                }
            }

            $clearIndexes[] = $i;
        }

        foreach ($clearIndexes as $i) {
            $tokens[$i] = new Token('');
        }

        $tokens->insertAt($index, [
            new Token([T_WHITESPACE, "\n" . ($hasCodeBefore ? "\n" : '')]),
            new Token([T_COMMENT, self::$headerComment]),
            new Token([T_WHITESPACE, "\n\n"])
        ]);

        $tokens->clearEmptyTokens();
    }
}
