<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Parameters\Form\Mapper;

use Netgen\Layouts\Parameters\Form\Mapper;
use Netgen\Layouts\Parameters\ParameterDefinition;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class ChannelMapper extends Mapper
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

    public function mapOptions(ParameterDefinition $parameterDefinition): array
    {
        return [
            'multiple' => $parameterDefinition->getOption('multiple'),
            'choices' => $this->getChannelOptions(),
        ];
    }

    /**
     * @return array<string, int>
     */
    private function getChannelOptions(): array
    {
        $channels = $this->channelRepository->findAll();
        $channelList = [];

        foreach ($channels as $channel) {
            $channelList[(string) $channel->getName()] = (int) $channel->getId();
        }

        return $channelList;
    }
}
