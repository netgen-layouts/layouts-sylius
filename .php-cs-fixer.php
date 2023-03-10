<?php

// To support running PHP CS Fixer via PHAR file (e.g. in GitHub Actions)
require_once __DIR__ . '/vendor/netgen/layouts-coding-standard/lib/PhpCsFixer/Config.php';

return (new Netgen\Layouts\CodingStandard\PhpCsFixer\Config())
    ->addRules([
        'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments', 'match', 'parameters']],
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude(['vendor', 'node_modules'])
            ->in(__DIR__)
    )
;
