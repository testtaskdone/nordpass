<?php

namespace App\Tests;

use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class ItemControllerTest extends WebTestCase
{
    private const USERNAME = 'john';

    /** @var UserRepository */
    private $userRepository;
    /** @var ItemRepository */
    private $itemRepository;
    /** @var KernelBrowser */
    private $client;

    protected function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->userRepository = static::$container->get(UserRepository::class);
        $this->itemRepository = static::$container->get(ItemRepository::class);
    }

    public function testCreate(): int
    {
        $this->client->loginUser($this->userRepository->findOneByUsername(self::USERNAME));
        
        $data = uniqid('very secure new item data', true);

        $newItemData = ['data' => $data];

        $this->client->request('POST', '/item', $newItemData);
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', '/item');
        $this->assertResponseIsSuccessful();

        $this->assertStringContainsString($data, $this->client->getResponse()->getContent());

        $item = $this->itemRepository->findOneBy(['data' => $data]);
        $this->assertNotNull($item);

        return $item->getId();
    }

    /**
     * @depends testCreate
     */
    public function testUpdate(int $id): int
    {
        $this->client->loginUser($this->userRepository->findOneByUsername(self::USERNAME));

        $data = uniqid('very secure updated item data', true);

        $requestBoundary = sprintf(
            file_get_contents(__DIR__ . '/../Fixtures/Item/form_data_tpl'),
            $id,
            $data
        );

        $this->client->request('PUT', '/item', [], [], [], $requestBoundary);
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', '/item');
        $this->assertResponseIsSuccessful();

        $this->assertStringContainsString($data, $this->client->getResponse()->getContent());

        $this->assertEquals($this->itemRepository->find($id)->getData(), $data);

        return $id;
    }

    /**
     * @depends testUpdate
     */
    public function testDelete(int $id): void
    {
        $this->client->loginUser($this->userRepository->findOneByUsername(self::USERNAME));

        $this->client->request('DELETE', '/item/' . $id);
        $this->assertResponseIsSuccessful();

        $this->assertNull($this->itemRepository->find($id));
    }
}
