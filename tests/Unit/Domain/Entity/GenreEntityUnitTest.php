<?php

namespace Tests\Unit\Domain\Entity;

use PHPUnit\Framework\TestCase;
use Core\Domain\ValueObject\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Core\Domain\Entity\GenreEntity;
use PHPUnit\Framework\Attributes\Test;
use Core\Domain\Exception\EntityValidationException;

class GenreEntityUnitTest extends TestCase
{
    public function testAttributes()
    {
        $uuid = (string) RamseyUuid::uuid7();
        $date = date('Y-m-d H:i:s');

        $genre = new GenreEntity(
            id: new Uuid($uuid),
            name: 'New Genre',
            isActive: true,
            createdAt: new \DateTime(date($date)),

        );

        $this->assertEquals($uuid, $genre->id());
        $this->assertEquals('New Genre', $genre->name);
        $this->assertTrue($genre->isActive);
        $this->assertEquals($date, $genre->createdAt());
    }

    public function testAttributeCreate()
    {
        $genre = new GenreEntity(
            name: 'New Genre',
        );

        $this->assertInstanceOf(Uuid::class, new Uuid($genre->id()));
        $this->assertEquals('New Genre', $genre->name);
        $this->assertTrue($genre->isActive);
        $this->assertNotEmpty($genre->createdAt());
    }

    #[Test]
    public function activate()
    {
        $genre = new GenreEntity(
            name: 'New Genre',
            isActive: false,
        );

        $genre->activate();
        $this->assertTrue($genre->isActive);
    }

    #[Test]
    public function deactivate()
    {
        $genre = new GenreEntity(
            name: 'New Genre',
        );

        $genre->deactivate();
        $this->assertFalse($genre->isActive);
    }

    #[Test]
    public function update()
    {
        $genre = new GenreEntity(
            name: 'New Genre',
        );

        $this->assertEquals('New Genre', $genre->name);;

        $genre->update(name: 'Name Genre updated');

        $this->assertEquals('Name Genre updated', $genre->name);
    }

    #[Test]
    public function validationException()
    {
        $this->expectException(EntityValidationException::class);
        (new GenreEntity(name: ''));
    }

    #[Test]
    public function updateValidationException()
    {
        $this->expectException(EntityValidationException::class);
        (new GenreEntity(name: 'New Genre'))->update(name: 'X');
    }

    #[Test]
    public function addCategoryToGenre()
    {
        $genre = new GenreEntity(
            name: 'New Genre',
        );

        $categoryId = (string) RamseyUuid::uuid7();
        $genre->addCategory($categoryId);

        $this->assertContains($categoryId, $genre->categoriesId);
    }

    #[Test]
    public function removeCategoryToGenre()
    {
        $categoriesIds = array_map(fn() => (string) RamseyUuid::uuid7(), range(1, 2));

        $genre = new GenreEntity(
            name: 'New Genre',
            categoriesId: $categoriesIds,
        );

        $genre->removeCategory($categoriesIds[0]);

        $this->assertNotContains($categoriesIds[0], $genre->categoriesId);
        $this->assertCount(1, $genre->categoriesId);
    }
}
