<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\حضورController;
use App\Repository\حضورRepository;
use App\Entity\حضور;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Paginator\PaginationInterface;

class Testحضور extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock('App\Repository\حضورRepository');
        $this->controller = new حضورController($this->repository);

        $this->repository->expects($this->any())
            ->method('findAll')
            ->willReturn([
                new حضور(1, 'name1', 'email1'),
                new حضور(2, 'name2', 'email2'),
            ]);

        $this->repository->expects($this->any())
            ->method('find')
            ->willReturn(new حضور(1, 'name1', 'email1'));

        $this->repository->expects($this->any())
            ->method('save')
            ->willReturn(new حضور(1, 'name1', 'email1'));

        $this->repository->expects($this->any())
            ->method('remove')
            ->willReturn(null);
    }

    public function testGetAll()
    {
        $request = new Request();
        $response = $this->controller->getAll($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne()
    {
        $request = new Request();
        $response = $this->controller->getOne($request, 1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate()
    {
        $request = new Request();
        $request->request->set('name', 'name1');
        $request->request->set('email', 'email1');

        $response = $this->controller->create($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $request = new Request();
        $request->request->set('name', 'name1');
        $request->request->set('email', 'email1');

        $response = $this->controller->update($request, 1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $request = new Request();
        $response = $this->controller->delete($request, 1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testGetAllNotFound()
    {
        $this->repository->expects($this->any())
            ->method('findAll')
            ->willReturn([]);

        $request = new Request();
        $response = $this->controller->getAll($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testGetOneNotFound()
    {
        $this->repository->expects($this->any())
            ->method('find')
            ->willReturn(null);

        $request = new Request();
        $response = $this->controller->getOne($request, 1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}

class حضور
{
    private $id;
    private $name;
    private $email;

    public function __construct($id, $name, $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }
}


This test file covers the CRUD API operations on the 'حضور' module. It uses mocked PDO statements and the `حضورRepository` to simulate database interactions. The tests cover the following scenarios:

*   `testGetAll`: Verifies that the `getAll` method returns a successful response with a list of `حضور` entities.
*   `testGetOne`: Verifies that the `getOne` method returns a successful response with a single `حضور` entity.
*   `testCreate`: Verifies that the `create` method returns a successful response with a new `حضور` entity.
*   `testUpdate`: Verifies that the `update` method returns a successful response with an updated `حضور` entity.
*   `testDelete`: Verifies that the `delete` method returns a successful response with a deleted `حضور` entity.
*   `testGetAllNotFound`: Verifies that the `getAll` method returns a not found response when the repository returns an empty list.
*   `testGetOneNotFound`: Verifies that the `getOne` method returns a not found response when the repository returns null.

Note that this is a basic example and you may need to modify it to fit your specific use case. Additionally, you should consider using a more robust testing framework and mocking library to ensure that your tests are reliable and efficient.