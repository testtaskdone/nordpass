<?php

namespace App\Tests\Unit;

use App\Entity\Item;
use App\Entity\User;
use App\Service\ItemService;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ItemServiceTest extends TestCase
{
    public function testCreate(): void
    {
        /** @var User */
        $user = $this->createMock(User::class);
        $data = 'secret data';

        $expectedObject = new Item();
        $expectedObject->setUser($user);
        $expectedObject->setData($data);

        $em = $this->createMock(EntityManagerInterface::class);
        $em
            ->expects($this->once())
            ->method('persist')
            ->with($expectedObject);

        $registry = $this->createMock(ManagerRegistry::class);
        $registry
            ->expects($this->once())
            ->method('getManagerForClass')
            ->with(Item::class)
            ->willReturn($em);

        $itemService = new ItemService($registry, $this->createMock(AuthorizationCheckerInterface::class));

        $itemService->create($user, $data);
    }
}
