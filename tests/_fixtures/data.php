<?php

return array(
    'ngbm_rule' => array(
        array('id' => 1, 'status' => 1, 'layout_id' => 1, 'comment' => null),
        array('id' => 2, 'status' => 0, 'layout_id' => 2, 'comment' => null),
        array('id' => 3, 'status' => 1, 'layout_id' => 3, 'comment' => null),
        array('id' => 4, 'status' => 1, 'layout_id' => 4, 'comment' => null),
        array('id' => 5, 'status' => 1, 'layout_id' => 5, 'comment' => null),
        array('id' => 6, 'status' => 1, 'layout_id' => 4, 'comment' => null),
        array('id' => 7, 'status' => 1, 'layout_id' => 5, 'comment' => null),
    ),
    'ngbm_rule_data' => array(
        array('rule_id' => 1, 'enabled' => 1, 'priority' => 7),
        array('rule_id' => 2, 'enabled' => 1, 'priority' => 6),
        array('rule_id' => 3, 'enabled' => 1, 'priority' => 5),
        array('rule_id' => 4, 'enabled' => 1, 'priority' => 4),
        array('rule_id' => 5, 'enabled' => 1, 'priority' => 3),
        array('rule_id' => 6, 'enabled' => 1, 'priority' => 2),
        array('rule_id' => 7, 'enabled' => 1, 'priority' => 1),
    ),
    'ngbm_rule_target' => array(
        array('id' => 1, 'status' => 1, 'rule_id' => 1, 'type' => 'sylius_product', 'value' => 72),
        array('id' => 2, 'status' => 1, 'rule_id' => 1, 'type' => 'sylius_product', 'value' => 73),
        array('id' => 3, 'status' => 0, 'rule_id' => 2, 'type' => 'sylius_product', 'value' => 74),
        array('id' => 4, 'status' => 1, 'rule_id' => 3, 'type' => 'sylius_product', 'value' => 75),
        array('id' => 5, 'status' => 1, 'rule_id' => 3, 'type' => 'sylius_product', 'value' => 155),
        array('id' => 6, 'status' => 1, 'rule_id' => 3, 'type' => 'sylius_product', 'value' => 156),
        array('id' => 7, 'status' => 1, 'rule_id' => 4, 'type' => 'sylius_taxon', 'value' => 2),
        array('id' => 8, 'status' => 1, 'rule_id' => 4, 'type' => 'sylius_taxon', 'value' => 43),
        array('id' => 9, 'status' => 1, 'rule_id' => 5, 'type' => 'sylius_taxon', 'value' => 5),
        array('id' => 10, 'status' => 1, 'rule_id' => 5, 'type' => 'sylius_taxon', 'value' => 13),
        array('id' => 11, 'status' => 1, 'rule_id' => 6, 'type' => 'sylius_taxon_product', 'value' => 2),
        array('id' => 12, 'status' => 1, 'rule_id' => 6, 'type' => 'sylius_taxon_product', 'value' => 43),
        array('id' => 13, 'status' => 1, 'rule_id' => 6, 'type' => 'sylius_taxon_product', 'value' => 5),
        array('id' => 14, 'status' => 1, 'rule_id' => 7, 'type' => 'sylius_taxon_product', 'value' => 13),
    ),
);
