<?php

return [
    'ngbm_rule' => [
        ['id' => 1, 'status' => 1, 'layout_id' => 1, 'comment' => null],
        ['id' => 2, 'status' => 0, 'layout_id' => 2, 'comment' => null],
        ['id' => 3, 'status' => 1, 'layout_id' => 3, 'comment' => null],
        ['id' => 4, 'status' => 1, 'layout_id' => 4, 'comment' => null],
        ['id' => 5, 'status' => 1, 'layout_id' => 5, 'comment' => null],
        ['id' => 6, 'status' => 1, 'layout_id' => 4, 'comment' => null],
        ['id' => 7, 'status' => 1, 'layout_id' => 5, 'comment' => null],
    ],
    'ngbm_rule_data' => [
        ['rule_id' => 1, 'enabled' => 1, 'priority' => 7],
        ['rule_id' => 2, 'enabled' => 1, 'priority' => 6],
        ['rule_id' => 3, 'enabled' => 1, 'priority' => 5],
        ['rule_id' => 4, 'enabled' => 1, 'priority' => 4],
        ['rule_id' => 5, 'enabled' => 1, 'priority' => 3],
        ['rule_id' => 6, 'enabled' => 1, 'priority' => 2],
        ['rule_id' => 7, 'enabled' => 1, 'priority' => 1],
    ],
    'ngbm_rule_target' => [
        ['id' => 1, 'status' => 1, 'rule_id' => 1, 'type' => 'sylius_product', 'value' => 72],
        ['id' => 2, 'status' => 1, 'rule_id' => 1, 'type' => 'sylius_product', 'value' => 73],
        ['id' => 3, 'status' => 0, 'rule_id' => 2, 'type' => 'sylius_product', 'value' => 74],
        ['id' => 4, 'status' => 1, 'rule_id' => 3, 'type' => 'sylius_product', 'value' => 75],
        ['id' => 5, 'status' => 1, 'rule_id' => 3, 'type' => 'sylius_product', 'value' => 155],
        ['id' => 6, 'status' => 1, 'rule_id' => 3, 'type' => 'sylius_product', 'value' => 156],
        ['id' => 7, 'status' => 1, 'rule_id' => 4, 'type' => 'sylius_taxon', 'value' => 2],
        ['id' => 8, 'status' => 1, 'rule_id' => 4, 'type' => 'sylius_taxon', 'value' => 43],
        ['id' => 9, 'status' => 1, 'rule_id' => 5, 'type' => 'sylius_taxon', 'value' => 5],
        ['id' => 10, 'status' => 1, 'rule_id' => 5, 'type' => 'sylius_taxon', 'value' => 13],
        ['id' => 11, 'status' => 1, 'rule_id' => 6, 'type' => 'sylius_taxon_product', 'value' => 2],
        ['id' => 12, 'status' => 1, 'rule_id' => 6, 'type' => 'sylius_taxon_product', 'value' => 43],
        ['id' => 13, 'status' => 1, 'rule_id' => 6, 'type' => 'sylius_taxon_product', 'value' => 5],
        ['id' => 14, 'status' => 1, 'rule_id' => 7, 'type' => 'sylius_taxon_product', 'value' => 13],
    ],
];
