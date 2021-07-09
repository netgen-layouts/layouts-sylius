<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Layout\Resolver\Form\ConditionType\Mapper;

use Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper\Channel;
use Netgen\Layouts\Sylius\Tests\Stubs\Channel as ChannelStub;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class ChannelTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject&\Sylius\Component\Channel\Repository\ChannelRepositoryInterface
     */
    private MockObject $channelRepository;

    private Channel $mapper;

    protected function setUp(): void
    {
        $this->channelRepository = $this->createMock(ChannelRepositoryInterface::class);

        $this->mapper = new Channel(
            $this->channelRepository,
        );
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper\Channel::getFormType
     */
    public function testGetFormType(): void
    {
        self::assertSame(ChoiceType::class, $this->mapper->getFormType());
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper\Channel::getChannelList
     * @covers \Netgen\Layouts\Sylius\Layout\Resolver\Form\ConditionType\Mapper\Channel::getFormOptions
     */
    public function testGetFormOptions(): void
    {
        $channels = [
            new ChannelStub(1, 'WEBSHOP', 'Webshop'),
            new ChannelStub(2, 'OTHER_SHOP', 'Other shop'),
        ];

        $channelList = [
            'Webshop' => 1,
            'Other shop' => 2,
        ];

        $this->channelRepository
            ->expects(self::once())
            ->method('findAll')
            ->willReturn($channels);

        self::assertSame(
            [
                'choices' => $channelList,
                'choice_translation_domain' => false,
                'multiple' => true,
                'expanded' => true,
            ],
            $this->mapper->getFormOptions(),
        );
    }
}
