<?php

// To support running PHP CS Fixer via PHAR file (e.g. in GitHub Actions)
require_once __DIR__ . '/vendor/netgen/layouts-coding-standard/lib/PhpCsFixer/Config.php';

return Netgen\Layouts\CodingStandard\PhpCsFixer\Config::create()
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude(['vendor', 'node_modules'])
            ->in(__DIR__)
    )
;
