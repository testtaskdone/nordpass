<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\User;
use App\Security\ItemVoter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ItemService
{
    /** @var ManagerRegistry */
    private $registry;
    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    public function __construct(ManagerRegistry $registry, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->registry = $registry;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function create(User $user, string $data): void
    {
        $item = new Item();
        $item->setUser($user);
        $item->setData($data);

        $em = $this->getEntityManager();

        $em->persist($item);
        $em->flush();
    }

    public function update(Item $item, string $data): void
    {
        $item->setData($data);
        $this->getEntityManager()->flush();
    }

    public function delete(Item $item): void
    {
        $em = $this->getEntityManager();
        $em->remove($item);

        $em->flush();
    }

    public function isGrantedUpdate(Item $item): bool
    {
        return $this->authorizationChecker->isGranted(ItemVoter::ACTION_UPDATE, $item);
    }

    public function isGrantedDelete(Item $item): bool
    {
        return $this->authorizationChecker->isGranted(ItemVoter::ACTION_DELETE, $item);
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->registry->getManagerForClass(Item::class);
    }
}
