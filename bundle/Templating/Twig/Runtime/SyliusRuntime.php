<?php

declare(strict_types=1);

namespace Netgen\Bundle\LayoutsSyliusBundle\Templating\Twig\Runtime;

use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

use function array_reverse;

final class SyliusRuntime
{
    /**
     * @param \Sylius\Component\Channel\Repository\ChannelRepositoryInterface<\Sylius\Component\Channel\Model\ChannelInterface> $channelRepository
     * @param \Sylius\Resource\Doctrine\Persistence\RepositoryInterface<\Sylius\Component\Locale\Model\LocaleInterface> $localeRepository
     * @param string[] $componentCreateRoutes
     * @param string[] $componentUpdateRoutes
     */
    public function __construct(
        private ChannelRepositoryInterface $channelRepository,
        private RepositoryInterface $localeRepository,
        private array $componentCreateRoutes = [],
        private array $componentUpdateRoutes = [],
    ) {}

    /**
     * Returns the taxon path.
     *
     * @return array<string|null>
     */
    public function getTaxonPath(TaxonInterface $taxon): array
    {
        $parts = [$taxon->getName()];

        $parentTaxon = $taxon->getParent();
        while ($parentTaxon instanceof TaxonInterface) {
            $parts[] = $parentTaxon->getName();
            $parentTaxon = $parentTaxon->getParent();
        }

        return array_reverse($parts);
    }

    /**
     * Returns the channel name.
     */
    public function getChannelName(int|string $channelId): ?string
    {
        return $this->channelRepository->find($channelId)?->getName();
    }

    /**
     * Returns the locale name.
     */
    public function getLocaleName(string $code): ?string
    {
        return $this->localeRepository->findOneBy(['code' => $code])?->getName();
    }

    public function getComponentCreateRoute(string $componentType): ?string
    {
        return $this->componentCreateRoutes[$componentType] ?? null;
    }

    public function getComponentUpdateRoute(string $componentType): ?string
    {
        return $this->componentUpdateRoutes[$componentType] ?? null;
    }
}
