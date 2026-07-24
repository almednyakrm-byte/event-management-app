<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testأحداث extends TestCase
{
    private $pdo;
    private $statement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
    }

    public function testGetأحداث()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM أحداث')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->statement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'أحداث 1'],
                ['id' => 2, 'name' => 'أحداث 2'],
            ]);

        $result = $this->getأحداث($this->pdo);
        $this->assertEquals([
            ['id' => 1, 'name' => 'أحداث 1'],
            ['id' => 2, 'name' => 'أحداث 2'],
        ], $result);
    }

    public function testPostأحداث()
    {
        $data = ['name' => 'أحداث 3'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO أحداث (name) VALUES (:name)')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->postأحداث($this->pdo, $data);
        $this->assertEquals(1, $result);
    }

    public function testPutأحداث()
    {
        $id = 1;
        $data = ['name' => 'أحداث 1 updated'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE أحداث SET name = :name WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->putأحداث($this->pdo, $id, $data);
        $this->assertEquals(1, $result);
    }

    public function testDeleteأحداث()
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM أحداث WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->deleteأحداث($this->pdo, $id);
        $this->assertEquals(1, $result);
    }

    private function getأحداث(PDO $pdo)
    {
        $statement = $pdo->prepare('SELECT * FROM أحداث');
        $statement->execute();
        return $statement->fetchAll();
    }

    private function postأحداث(PDO $pdo, array $data)
    {
        $statement = $pdo->prepare('INSERT INTO أحداث (name) VALUES (:name)');
        $statement->bindParam(':name', $data['name']);
        $statement->execute();
        return $statement->rowCount();
    }

    private function putأحداث(PDO $pdo, int $id, array $data)
    {
        $statement = $pdo->prepare('UPDATE أحداث SET name = :name WHERE id = :id');
        $statement->bindParam(':name', $data['name']);
        $statement->bindParam(':id', $id);
        $statement->execute();
        return $statement->rowCount();
    }

    private function deleteأحداث(PDO $pdo, int $id)
    {
        $statement = $pdo->prepare('DELETE FROM أحداث WHERE id = :id');
        $statement->bindParam(':id', $id);
        $statement->execute();
        return $statement->rowCount();
    }
}