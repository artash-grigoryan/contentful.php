<?php

/**
 * This file is part of the contentful/contentful package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Tests\Delivery\Unit;

use Contentful\Delivery\Query;
use Contentful\Tests\Delivery\TestCase;

class QueryTest extends TestCase
{
    public function testQueryStringInclude()
    {
        $query = new Query();
        $query->setInclude(50);

        $this->assertSame('include=50', $query->getQueryString());
    }

    public function testQueryStringLocale()
    {
        $query = new Query();
        $query->setLocale('de-DE');

        $this->assertSame('locale=de-DE', $query->getQueryString());
    }
}
