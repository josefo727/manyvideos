<?php

namespace App\Http\Controllers;
use App\Models\Video;
use Inertia\Inertia;
use Inertia\Response;

class VideoController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Video $video): Response
    {
        return Inertia::render('VideoShow', [
            'video' => $video->load('tags', 'user'),
        ]);
    }
}
