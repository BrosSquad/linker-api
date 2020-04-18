<?php

namespace BrosSquad\Linker\Api\Tests\Services;

use BrosSquad\Linker\Api\Models\Link;
use BrosSquad\Linker\Api\Services\LinkService;
use BrosSquad\Linker\Api\Tests\TestCase;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @internal
 * @coversNothing
 */
class LinksServiceTest extends TestCase
{
    private $linkService;

    public function setUp(): void
    {
        parent::setUp();

        $this->linkService = new LinkService();
    }

    public function testPaginateLinks()
    {
        $urls = [
            ['url' => 'https://github.com/BrosSquad'],
            ['url' => 'https://youtube.com/'],
        ];

        $links = [];

        foreach ($urls as $url) {
            $links[] = Link::create($url);
        }

        $paginated = $this->linkService->get();

        $this->assertNotNull($paginated);

        $this->assertInstanceOf(LengthAwarePaginator::class, $paginated);

        $this->assertEquals(1, $paginated->lastPage());
        $this->assertEquals(2, $paginated->total());

        foreach ($links as $link) {
            $link->delete();
        }

    }

    public function testGetLink()
    {
        $url = 'https://github.com/BrosSquad';

        $link = new Link([
            'url' => $url,
        ]);

        $link->saveOrFail();

        $copy = $this->linkService->find($link->id);

        $this->assertEquals($link->id, $copy->id);
        $this->assertEquals($link->url, $copy->url);
    }

    public function testCreateLink()
    {
        $url = 'https://github.com/BrosSquad';

        $link = $this->linkService->create($url);

        $this->assertNotNull($link);
        $this->assertInstanceOf(Link::class, $link);
    }

    public function testDeleteLink()
    {
        $url = 'https://github.com/BrosSquad';

        $link = new Link([
            'url' => $url,
        ]);

        $link->saveOrFail();

        $this->assertTrue($this->linkService->delete($link->id));
    }
}
