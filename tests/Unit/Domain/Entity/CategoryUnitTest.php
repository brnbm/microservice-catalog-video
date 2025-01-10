<?php

namespace Tests\Unit\Domain\Entity;

use Throwable;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Category;
use PHPUnit\Framework\Attributes\Test;
use Core\Domain\Exception\EntityValidationException;

class CategoryUnitTest extends TestCase
{
    public function testAttributes()
    {
        $category = new Category(
            name: 'Category Name',
            description: 'Category Description',
            isActive: true,
            createdAt: '2021-10-10 10:10:10'
        );

        $this->assertNotEmpty($category->id());
        $this->assertNotEmpty($category->createdAt());
        $this->assertEquals('Category Name', $category->name);
        $this->assertEquals('Category Description', $category->description);
        $this->assertEquals(true, $category->isActive);
    }

    public function testActivated()
    {
        $category = new Category(
            name: 'Category Name',
            isActive: false
        );

        $this->assertFalse($category->isActive);
        $category->activate();
        $this->assertTrue($category->isActive);
    }

    public function testDisabled()
    {
        $category = new Category(
            name: 'Category Name'
        );

        $this->assertTrue($category->isActive);
        $category->disable();
        $this->assertFalse($category->isActive);
    }

    public function testUpdate()
    {
        $uuid = Uuid::uuid4()->toString();
        $stringDate = '2021-10-10 10:10:10';

        $category = new Category(
            id: $uuid,
            name: 'Category Name',
            description: 'Category Description',
            isActive: true,
            createdAt: $stringDate,
        );

        $category->update(
            name: 'Category Name Updated',
            description: 'Category Description Updated'
        );

        $this->assertEquals($uuid, $category->id);
        $this->assertEquals('Category Name Updated', $category->name);
        $this->assertEquals('Category Description Updated', $category->description);
        $this->assertEquals($stringDate, $category->createdAt());
    }

    #[Test]
    public function exceptionName()
    {
        try {
            new Category(
                name: ''
            );

            $this->assertTrue(true);
        } catch (Throwable $th) {
            $this->assertInstanceOf(EntityValidationException::class, $th);
        }
    }
}
