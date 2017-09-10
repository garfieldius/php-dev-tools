<?php

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

use GrossbergerGeorg\PHPDevTools\Fixer\LowerHeaderCommentFixer;
use GrossbergerGeorg\PHPDevTools\Fixer\NamespaceFirstFixer;
use GrossbergerGeorg\PHPDevTools\Fixer\SingleEmptyLineFixer;

$header = 'This file is (c) ' . date('Y') . ' by Georg Großberger 
<contact@grossberger-ge.org> - <https://grossberger-ge.org/>

It is free software; you can redistribute it and/or
modify it under the terms of the Apache License 2.0

For the full copyright and license information see
the file LICENSE distributed with the source code
or <https://www.apache.org/licenses/LICENSE-2.0>';

LowerHeaderCommentFixer::setHeader($header);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests/Fixer');

return PhpCsFixer\Config::create()
    ->setUsingCache(false)
    ->registerCustomFixers([
        new LowerHeaderCommentFixer(),
        new NamespaceFirstFixer(),
        new SingleEmptyLineFixer(),
    ])
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2'                                      => true,
        'GrossbergerGeorg/lower_header_comment'      => true,
        'GrossbergerGeorg/namespace_first'           => true,
        'GrossbergerGeorg/single_empty_line'         => true,
        'no_leading_import_slash'                    => true,
        'no_trailing_comma_in_singleline_array'      => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_unused_imports'                          => true,
        'concat_space'                               => [
            'spacing' => 'one'
        ],
        'no_whitespace_in_blank_line'      => true,
        'ordered_imports'                  => true,
        'single_quote'                     => true,
        'no_empty_statement'               => true,
        'no_extra_consecutive_blank_lines' => true,
        'phpdoc_no_package'                => true,
        'phpdoc_scalar'                    => true,
        'no_blank_lines_after_phpdoc'      => true,
        'array_syntax'                     => [
            'syntax' => 'short'
        ],
        'whitespace_after_comma_in_array' => true,
        'function_typehint_space'         => true,
        'hash_to_slash_comment'           => true,
        'no_alias_functions'              => true,
        'lowercase_cast'                  => true,
        'no_leading_namespace_whitespace' => true,
        'native_function_casing'          => true,
        'self_accessor'                   => true,
        'no_short_bool_cast'              => true,
        'no_unneeded_control_parentheses' => true,
        'encoding'                        => true,
        'cast_spaces'                     => true,
        'combine_consecutive_unsets'      => true,
        'binary_operator_spaces'          => [
            'align_double_arrow' => true,
            'align_equals'       => false,
        ],
        'braces'                  => true,
        'declare_equal_normalize' => true,
        'dir_constant'            => true,
        'ereg_to_preg'            => true,
        'include'                 => true,
        'line_ending'             => true,
        'modernize_types_casting' => true,
        'new_with_braces'         => true,
        'no_php4_constructor'     => true,
        'no_useless_else'         => true,
        'ordered_class_elements'  => true,
        'psr0'                    => false,
        'short_scalar_cast'       => true,
        'standardize_not_equals'  => true,
        'phpdoc_no_empty_return'  => true,
        'phpdoc_trim'             => true,
        'phpdoc_order'            => true,
    ])
    ->setFinder($finder);
