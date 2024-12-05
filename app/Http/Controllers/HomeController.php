<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $filters = $this->getFilters($request);
        $videos = $this->getVideos($filters);
        $tags = $this->getTags();

        return Inertia::render('Home', [
            'videos' => $videos,
            'tags' => $tags,
            'filters' => $filters,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getFilters(Request $request): array
    {
        $defaultFilters = [
            'search' => '',
            'tags' => [],
            'min_duration' => 0,
            'max_duration' => 10,
            'min_size' => 0,
            'max_size' => 500,
        ];

        return array_merge($defaultFilters, $request->only([
            'search',
            'tags',
            'min_duration',
            'max_duration',
            'min_size',
            'max_size',
        ]));
    }

    /**
     * @param array $filters
     * @return mixed
     */
    protected function getVideos(array $filters): mixed
    {
        return Video::query()
            ->with(['tags', 'user'])
            ->orderBy('id', 'desc')
            ->search($filters['search'])
            ->withTags($filters['tags'])
            ->durationBetween($filters['min_duration'], $filters['max_duration'])
            ->sizeBetween($filters['min_size'], $filters['max_size'])
            ->paginate(12)
            ->withQueryString();
    }

    /**
     * @return Collection
     */
    protected function getTags(): Collection
    {
        return Tag::query()->select('id', 'name')->get();
    }
}
