<?php

declare(strict_types=1);

return [
    'nglayouts_rule_group' => [
        ['id' => 1, 'status' => 1, 'uuid' => '00000000-0000-0000-0000-000000000000', 'depth' => 0, 'path' => '/1/', 'parent_id' => null, 'name' => '', 'description' => ''],
    ],
    'nglayouts_rule_group_data' => [
        ['rule_group_id' => 1, 'enabled' => 1, 'priority' => 0],
    ],
    'nglayouts_rule' => [
        ['id' => 1, 'status' => 1, 'uuid' => '38203a0a-a801-4974-ba05-4856a36b5f3b', 'rule_group_id' => 1, 'layout_uuid' => '81168ed3-86f9-55ea-b153-101f96f2c136', 'description' => ''],
        ['id' => 2, 'status' => 0, 'uuid' => '4936c777-d9a2-4c2b-bb24-84dc8850fd8b', 'rule_group_id' => 1, 'layout_uuid' => '71cbe281-430c-51d5-8e21-c3cc4e656dac', 'description' => ''],
        ['id' => 3, 'status' => 1, 'uuid' => 'e07be662-4168-432b-a170-57000c568d60', 'rule_group_id' => 1, 'layout_uuid' => 'd8e55af7-cf62-5f28-ae15-331b457d82e9', 'description' => ''],
        ['id' => 4, 'status' => 1, 'uuid' => 'a74c864b-48cf-49a1-af71-8f016c8a78e8', 'rule_group_id' => 1, 'layout_uuid' => '8626a1ca-6413-5f54-acef-de7db06272ce', 'description' => ''],
        ['id' => 5, 'status' => 1, 'uuid' => 'a90f576f-072e-464c-892f-0de623520e53', 'rule_group_id' => 1, 'layout_uuid' => '399ad9ac-777a-50ba-945a-06e9f57add12', 'description' => ''],
        ['id' => 6, 'status' => 1, 'uuid' => 'fcfb2d79-6d82-4d49-afd6-555e49b53c98', 'rule_group_id' => 1, 'layout_uuid' => '8626a1ca-6413-5f54-acef-de7db06272ce', 'description' => ''],
        ['id' => 7, 'status' => 1, 'uuid' => '56fad9c2-b0ea-43d3-af5b-c5fea565f1b5', 'rule_group_id' => 1, 'layout_uuid' => '399ad9ac-777a-50ba-945a-06e9f57add12', 'description' => ''],
    ],
    'nglayouts_rule_data' => [
        ['rule_id' => 1, 'enabled' => 1, 'priority' => 7],
        ['rule_id' => 2, 'enabled' => 1, 'priority' => 6],
        ['rule_id' => 3, 'enabled' => 1, 'priority' => 5],
        ['rule_id' => 4, 'enabled' => 1, 'priority' => 4],
        ['rule_id' => 5, 'enabled' => 1, 'priority' => 3],
        ['rule_id' => 6, 'enabled' => 1, 'priority' => 2],
        ['rule_id' => 7, 'enabled' => 1, 'priority' => 1],
    ],
    'nglayouts_rule_target' => [
        ['id' => 1, 'status' => 1, 'uuid' => 'a3d7c5d4-d24f-4fd9-b1ec-2d4237b47a8d', 'rule_id' => 1, 'type' => 'sylius_product', 'value' => 72],
        ['id' => 2, 'status' => 1, 'uuid' => 'd0bbe17b-b082-4213-aa1b-0aee888b8ab2', 'rule_id' => 1, 'type' => 'sylius_product', 'value' => 73],
        ['id' => 3, 'status' => 0, 'uuid' => 'b0c08123-e49c-4a03-a224-2e4833a82017', 'rule_id' => 2, 'type' => 'sylius_product', 'value' => 74],
        ['id' => 4, 'status' => 1, 'uuid' => '5cef6e1e-7c8d-4beb-bed7-a76038f859d7', 'rule_id' => 3, 'type' => 'sylius_product', 'value' => 75],
        ['id' => 5, 'status' => 1, 'uuid' => '10074187-c725-4ede-aef7-2857ba0c2401', 'rule_id' => 3, 'type' => 'sylius_product', 'value' => 155],
        ['id' => 6, 'status' => 1, 'uuid' => '9f1fb1f0-01c3-431e-b8e9-11aa4e8185dd', 'rule_id' => 3, 'type' => 'sylius_product', 'value' => 156],
        ['id' => 7, 'status' => 1, 'uuid' => '66dfc4ba-aaac-4a98-b64a-978d88b776ec', 'rule_id' => 4, 'type' => 'sylius_taxon', 'value' => 2],
        ['id' => 8, 'status' => 1, 'uuid' => '5151d29a-9a50-4a70-ad0c-6a08c7d0feb7', 'rule_id' => 4, 'type' => 'sylius_taxon', 'value' => 43],
        ['id' => 9, 'status' => 1, 'uuid' => '69b5c0bd-3275-45dc-8d84-e389f511d1c4', 'rule_id' => 5, 'type' => 'sylius_taxon', 'value' => 5],
        ['id' => 10, 'status' => 1, 'uuid' => '3364f6ab-2774-43bb-b837-3556f348ae34', 'rule_id' => 5, 'type' => 'sylius_taxon', 'value' => 13],
        ['id' => 11, 'status' => 1, 'uuid' => '88c3d400-c764-4614-844e-3142e4562596', 'rule_id' => 6, 'type' => 'sylius_taxon_product', 'value' => 2],
        ['id' => 12, 'status' => 1, 'uuid' => 'd2311434-2d52-4c69-b016-ec4d2a8ea073', 'rule_id' => 6, 'type' => 'sylius_taxon_product', 'value' => 43],
        ['id' => 13, 'status' => 1, 'uuid' => 'faad2665-8bba-4751-9ea6-a69a0d5db13f', 'rule_id' => 6, 'type' => 'sylius_taxon_product', 'value' => 5],
        ['id' => 14, 'status' => 1, 'uuid' => '40aadd7e-01ae-49ea-80e2-fa1187b8342e', 'rule_id' => 7, 'type' => 'sylius_taxon_product', 'value' => 13],
        ['id' => 15, 'status' => 1, 'uuid' => 'dbc264ac-3ade-4388-bc26-b82487d71699', 'rule_id' => 7, 'type' => 'sylius_page', 'value' => 'homepage'],
        ['id' => 16, 'status' => 1, 'uuid' => '24136aac-c0b6-401d-9df6-f8c5ec19c1b2', 'rule_id' => 7, 'type' => 'sylius_page', 'value' => 'cart_summary'],
    ],
];
