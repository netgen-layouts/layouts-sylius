<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper;

use Netgen\Layouts\Layout\Resolver\Form\ConditionType\Mapper;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class Channel extends Mapper
{
    private ChannelRepositoryInterface $channelRepository;

    public function __construct(ChannelRepositoryInterface $channelRepository)
    {
        $this->channelRepository = $channelRepository;
    }

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
            if (!is_int($channel->getId()) || !is_string($channel->getName())) {
                continue;
            }

            $channelList[$channel->getName()] = $channel->getId();
        }

        return $channelList;
    }
}
