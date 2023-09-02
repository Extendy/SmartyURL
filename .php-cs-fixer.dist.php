<?php

use CodeIgniter\CodingStandard\CodeIgniter4;
use Nexus\CsConfig\Factory;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->files()
    ->in([
        __DIR__ . '/app/',
        __DIR__ . '/public',
        __DIR__ . '/src/',
    ])
    ->exclude('build')
    ->append([
        __FILE__,
        __DIR__ . '/rector.php',
    ]);

$overrides = [
    'php_unit_data_provider_name' => [
        'prefix' => 'provide',
        'suffix' => '',
    ],
    'php_unit_data_provider_static'      => true,
    'php_unit_data_provider_return_type' => true,
    'no_extra_blank_lines'               => [
        'tokens' => [
            'attribute',
            'break',
            'case',
            'continue',
            'curly_brace_block',
            'default',
            'extra',
            'parenthesis_brace_block',
            'return',
            'square_brace_block',
            'switch',
            'throw',
            'use',
        ],
    ],
];

$options = [
    'cacheFile' => 'build/.php-cs-fixer.no-header.cache',
    'finder'    => $finder,
];

return Factory::create(new CodeIgniter4(), $overrides, $options)->forProjects();
