<?php

/**
 * This file is part of the contentful/contentful package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Delivery\Unit\Resource;

use Contentful\Core\Api\DateTimeImmutable;
use Contentful\Delivery\SystemProperties;
use Contentful\Tests\Delivery\TestCase;

class ContentTypeTest extends TestCase
{
    public function testGetter()
    {
        $sys = new SystemProperties([
            'id' => 'human',
            'type' => 'ContentType',
            'revision' => 3,
            'createdAt' => new DateTimeImmutable('2013-06-27T22:46:14.133Z'),
            'updatedAt' => new DateTimeImmutable('2013-09-02T15:10:26.818Z'),
        ]);
        $contentType = new MockContentType([
            'sys' => $sys,
            'name' => 'Human',
            'description' => 'Also called homo sapiens',
            'displayField' => 'name',
            'fields' => [
                'name' => new MockField('name', 'Name', 'Text', ['required' => true]),
                'likes' => new MockField('likes', 'Likes', 'Array', ['itemsType' => 'Symbol']),
                'image' => new MockField('image', 'Image', 'Array', ['itemsType' => 'Link', 'itemsLinkType' => 'Asset', 'disabled' => true]),
            ],
        ]);

        $this->assertSame('human', $contentType->getId());
        $this->assertSame('Human', $contentType->getName());
        $this->assertSame('Also called homo sapiens', $contentType->getDescription());
        $this->assertSame('name', $contentType->getDisplayField()->getId());
        $sys = $contentType->getSystemProperties();
        $this->assertSame('2013-06-27T22:46:14.133Z', (string) $sys->getCreatedAt());
        $this->assertSame('2013-09-02T15:10:26.818Z', (string) $sys->getUpdatedAt());
        $this->assertSame(3, $sys->getRevision());
        $this->assertSame('Likes', $contentType->getField('likes')->getName());

        $fields = $contentType->getFields();
        $this->assertInternalType('array', $fields);
        $this->assertCount(3, $fields);
        $this->assertArrayHasKey('name', $fields);
        $this->assertSame('name', $fields['name']->getId());
        $this->assertLink('human', 'ContentType', $contentType->asLink());
    }

    public function testGetterNotExisting()
    {
        $sys = new SystemProperties([
            'id' => 'human',
            'type' => 'ContentType',
            'revision' => 3,
            'createdAt' => new DateTimeImmutable('2013-06-27T22:46:14.133Z'),
            'updatedAt' => new DateTimeImmutable('2013-09-02T15:10:26.818Z'),
        ]);
        $contentType = new MockContentType([
            'sys' => $sys,
            'name' => 'Human',
            'fields' => [
                'likes' => new MockField('likes', 'Likes', 'Array', ['itemsType' => 'Symbol']),
                'image' => new MockField('image', 'Image', 'Array', ['itemsType' => 'Link', 'itemsLinkType' => 'Asset', 'disabled' => true]),
            ],
        ]);

        $this->assertNull($contentType->getDescription());
        $this->assertNull($contentType->getField('notExisting'));
        $this->assertNull($contentType->getDisplayField());
    }

    public function testJsonSerialize()
    {
        $sys = new SystemProperties([
            'id' => 'human',
            'type' => 'ContentType',
            'revision' => 3,
            'createdAt' => new DateTimeImmutable('2013-06-27T22:46:14.133Z'),
            'updatedAt' => new DateTimeImmutable('2013-09-02T15:10:26.818Z'),
        ]);
        $contentType = new MockContentType([
            'sys' => $sys,
            'name' => 'Human',
            'description' => 'Also called homo sapiens',
            'displayField' => 'name',
            'fields' => [
                'name' => new MockField('name', 'Name', 'Text', ['required' => true]),
                'likes' => new MockField('likes', 'Likes', 'Array', ['itemsType' => 'Symbol']),
                'image' => new MockField('image', 'Image', 'Array', ['itemsType' => 'Link', 'itemsLinkType' => 'Asset', 'disabled' => true]),
            ],
        ]);

        $this->assertJsonFixtureEqualsJsonObject('serialize.json', $contentType);
    }
}
