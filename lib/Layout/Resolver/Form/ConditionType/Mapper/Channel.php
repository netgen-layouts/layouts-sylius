<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper;

use Netgen\Layouts\Layout\Resolver\Form\ConditionType\Mapper;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class Channel extends Mapper
{
    public function __construct(private ChannelRepositoryInterface $channelRepository) {}

    public function getFormType(): string
    {
        return ChoiceType::class;
    }

    public function getFormOptions(): array
    {
        return [
            'choices' => $this->getChannelList(),
            'choice_translation_domain' => false,
            'multiple' => true,
            'expanded' => true,
        ];
    }

    /**
     * @return array<string, int>
     */
    private function getChannelList(): array
    {
        $channels = $this->channelRepository->findAll();
        $channelList = [];

        /** @var \Sylius\Component\Channel\Model\ChannelInterface $channel */
        foreach ($channels as $channel) {
            $channelList[(string) $channel->getName()] = (int) $channel->getId();
        }

        return $channelList;
    }
}
