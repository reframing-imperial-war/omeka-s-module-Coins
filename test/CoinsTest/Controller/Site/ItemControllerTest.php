<?php

namespace CoinsTest\Controller\Site;

use CoinsTest\Controller\CoinsControllerTestCase;

class ItemControllerTest extends CoinsControllerTestCase
{
    public function testShowAction()
    {
        $this->dispatch($this->items[0]->siteUrl($this->site->slug()));
        $this->assertResponseStatusCode(200);
        $this->assertQueryCount('span.Z3988', 1);
        $this->assertXpathQueryContentRegex('//span[@class="Z3988"]/@title', '/Test\+item\+0/');
    }

    public function testBrowseAction()
    {
        $this->dispatch(sprintf('/s/%s/item', $this->site->slug()));
        $this->assertResponseStatusCode(200);
        $this->assertQueryCount('span.Z3988', count($this->items));
    }
}
