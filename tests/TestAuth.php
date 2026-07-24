<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Auth\Auth;

class TestAuth extends TestCase
{
    private $auth;
    private $container;
    private $request;
    private $response;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->auth = new Auth($this->container);
    }

    public function testLoginSuccess()
    {
        // Mock database connection
        $dbMock = $this->createMock(\PDO::class);
        $this->container->method('get')->with('db')->willReturn($dbMock);

        // Mock database query
        $stmtMock = $this->createMock(\PDOStatement::class);
        $dbMock->method('prepare')->willReturn($stmtMock);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetch')->willReturn(['username' => 'test', 'password' => 'test']);

        // Set request data
        $this->request->method('getParsedBody')->willReturn(['username' => 'test', 'password' => 'test']);

        // Call login method
        $result = $this->auth->login($this->request, $this->response);

        // Assert session is set
        $this->assertTrue(isset($_SESSION['username']));

        // Assert response status code
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    public function testLoginFailure()
    {
        // Mock database connection
        $dbMock = $this->createMock(\PDO::class);
        $this->container->method('get')->with('db')->willReturn($dbMock);

        // Mock database query
        $stmtMock = $this->createMock(\PDOStatement::class);
        $dbMock->method('prepare')->willReturn($stmtMock);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetch')->willReturn(false);

        // Set request data
        $this->request->method('getParsedBody')->willReturn(['username' => 'test', 'password' => 'test']);

        // Call login method
        $result = $this->auth->login($this->request, $this->response);

        // Assert session is not set
        $this->assertFalse(isset($_SESSION['username']));

        // Assert response status code
        $this->assertEquals(401, $this->response->getStatusCode());
    }

    public function testRegisterSuccess()
    {
        // Mock database connection
        $dbMock = $this->createMock(\PDO::class);
        $this->container->method('get')->with('db')->willReturn($dbMock);

        // Mock database query
        $stmtMock = $this->createMock(\PDOStatement::class);
        $dbMock->method('prepare')->willReturn($stmtMock);
        $stmtMock->method('execute')->willReturn(true);

        // Set request data
        $this->request->method('getParsedBody')->willReturn(['username' => 'test', 'password' => 'test', 'confirm_password' => 'test']);

        // Call register method
        $result = $this->auth->register($this->request, $this->response);

        // Assert response status code
        $this->assertEquals(201, $this->response->getStatusCode());
    }

    public function testRegisterFailure()
    {
        // Mock database connection
        $dbMock = $this->createMock(\PDO::class);
        $this->container->method('get')->with('db')->willReturn($dbMock);

        // Mock database query
        $stmtMock = $this->createMock(\PDOStatement::class);
        $dbMock->method('prepare')->willReturn($stmtMock);
        $stmtMock->method('execute')->willReturn(false);

        // Set request data
        $this->request->method('getParsedBody')->willReturn(['username' => 'test', 'password' => 'test', 'confirm_password' => 'test']);

        // Call register method
        $result = $this->auth->register($this->request, $this->response);

        // Assert response status code
        $this->assertEquals(400, $this->response->getStatusCode());
    }
}