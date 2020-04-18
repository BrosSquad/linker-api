<?php

namespace BrosSquad\Linker\Api\Services;

use BrosSquad\Linker\Api\Models\Link;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LinkService
{
    /**
     * @return LengthAwarePaginator
     */
    public function get(int $page = 1, int $perPage = 15): LengthAwarePaginator
    {
        return Link::query()
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page)
        ;
    }

    /**
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException;
     *
     * @return Link
     */
    public function find(int $id): Link
    {
        return Link::query()->findOrFail($id);
    }

    /**
     * @return Link
     */
    public function create(string $url): Link
    {
        $link = new Link([
            'url' => $url,
        ]);

        $link->saveOrFail();

        return $link;
    }

    /**
     * @return bool
     */
    public function delete(int $id): bool
    {
        $link = Link::query()->findOrFail($id);

        return $link->delete();
    }
}
