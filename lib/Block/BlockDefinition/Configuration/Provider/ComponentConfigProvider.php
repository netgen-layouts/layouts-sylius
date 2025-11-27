<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Block\BlockDefinition\Configuration\Provider;

use Netgen\Bundle\LayoutsBundle\Configuration\ConfigurationInterface;
use Netgen\Layouts\API\Values\Block\Block;
use Netgen\Layouts\Block\BlockDefinition\Configuration\ConfigProviderInterface;
use Netgen\Layouts\Block\BlockDefinition\Configuration\ItemViewType;
use Netgen\Layouts\Block\BlockDefinition\Configuration\ViewType;

use function array_combine;
use function array_map;
use function array_unique;
use function in_array;
use function mb_strtolower;
use function mb_trim;
use function preg_replace;
use function ucwords;

final class ComponentConfigProvider implements ConfigProviderInterface
{
    /**
     * @var array<string, \Netgen\Layouts\Block\BlockDefinition\Configuration\ViewType[]>
     */
    private array $viewTypes = [];

    public function __construct(
        private ConfigurationInterface $configuration,
    ) {}

    public function provideViewTypes(?Block $block = null): array
    {
        if (!$block instanceof Block || !$block->hasParameter('component_type')) {
            return [];
        }

        if ($block->getParameter('component_type')->isEmpty) {
            return [];
        }

        $componentType = $block->getParameter('component_type')->value;

        $this->viewTypes[$block->id->toString()] ??= $this->buildViewTypes(
            $this->resolveViewTypes($componentType),
        );

        return $this->viewTypes[$block->id->toString()];
    }

    /**
     * @return string[]
     */
    private function resolveViewTypes(string $componentType): array
    {
        $viewConfig = $this->configuration->getParameter('view');

        $defaultViewConfig = $viewConfig['sylius_resource_view']['default'] ?? [];

        $viewTypes = [];
        foreach ($defaultViewConfig as $config) {
            if (!in_array($componentType, (array) ($config['match']['sylius_component\identifier'] ?? []), true)) {
                continue;
            }

            $viewTypes = [...$viewTypes, ...((array) ($config['match']['sylius_resource\view_type'] ?? []))];
        }

        return array_unique($viewTypes);
    }

    /**
     * @param string[] $validViews
     *
     * @return array<string, \Netgen\Layouts\Block\BlockDefinition\Configuration\ViewType>
     */
    private function buildViewTypes(array $validViews): array
    {
        return array_combine(
            $validViews,
            array_map(
                fn (string $view) => ViewType::fromArray(
                    [
                        'identifier' => $view,
                        'name' => $this->humanize($view),
                        'itemViewTypes' => [
                            'standard' => ItemViewType::fromArray(
                                [
                                    'identifier' => 'standard',
                                    'name' => 'Standard',
                                ],
                            ),
                        ],
                        'validParameters' => null,
                    ],
                ),
                $validViews,
            ),
        );
    }

    /**
     * Returns the human-readable version of the provided string.
     */
    private function humanize(string $text): string
    {
        return ucwords(mb_strtolower(mb_trim(preg_replace(['/([A-Z])/', '/[_\s]+/'], ['_$1', ' '], $text) ?? '')));
    }
}
