<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testتذاكر extends TestCase
{
    private MockObject $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
    }

    public function testGetتذاكر(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);
        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['id' => 1, 'name' => 'تذكرة 1']]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM تذاكر WHERE id = :id')
            ->willReturn($stmt);

        $تذاكر = new تذاكر($this->pdo);
        $result = $تذاكر->get(1);

        $this->assertEquals([['id' => 1, 'name' => 'تذكرة 1']], $result);
    }

    public function testPostتذاكر(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['name' => 'تذكرة جديدة']);
        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO تذاكر (name) VALUES (:name)')
            ->willReturn($stmt);

        $تذاكر = new تذاكر($this->pdo);
        $result = $تذاكر->create(['name' => 'تذكرة جديدة']);

        $this->assertTrue($result);
    }

    public function testPutتذاكر(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['id' => 1, 'name' => 'تذكرة محدثة']);
        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE تذاكر SET name = :name WHERE id = :id')
            ->willReturn($stmt);

        $تذاكر = new تذاكر($this->pdo);
        $result = $تذاكر->update(1, ['name' => 'تذكرة محدثة']);

        $this->assertTrue($result);
    }

    public function testDeleteتذاكر(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);
        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM تذاكر WHERE id = :id')
            ->willReturn($stmt);

        $تذاكر = new تذاكر($this->pdo);
        $result = $تذاكر->delete(1);

        $this->assertTrue($result);
    }
}

class تذاكر
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get(int $id): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM تذاكر WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO تذاكر (name) VALUES (:name)');
        $stmt->execute($data);
        return $stmt->rowCount() > 0;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('UPDATE تذاكر SET name = :name WHERE id = :id');
        $stmt->execute(array_merge($data, ['id' => $id]));
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM تذاكر WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }
}