<?php


namespace BrosSquad\Linker\Api\Controllers;


use BrosSquad\Linker\Api\Services\LinkService;

class LinksController extends ApiController
{
    /**
     * @var \BrosSquad\Linker\Api\Services\LinkService
     */
    protected LinkService $linkService;

    public function __construct(LinkService $linkService)
    {
        $this->linkService = $linkService;
    }


}
