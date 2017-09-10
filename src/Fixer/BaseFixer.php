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

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Utils;

/**
 * Shared implementations of custom fixers
 *
 * @author Georg Großberger <contact@grossberger-ge.org>
 */
abstract class BaseFixer implements FixerInterface
{
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isMonolithicPhp();
    }

    /**
     * {@inheritdoc}
     */
    public function isRisky()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(\SplFileInfo $file)
    {
        return $file->getExtension() === 'php';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $nameParts = explode('\\', get_class($this));
        $name = substr(end($nameParts), 0, -strlen('Fixer'));

        return 'GrossbergerGeorg/' . Utils::camelCaseToUnderscore($name);
    }
}
