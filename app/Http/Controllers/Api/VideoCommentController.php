<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Video;
use Illuminate\Http\JsonResponse;

class VideoCommentController extends Controller
{
    /**
     * @param Video $video
     * @return JsonResponse
     */
    public function index(Video $video): JsonResponse
    {
        $comments = $video->comments()->latest()->paginate(10);

        return response()->json($comments, 200);
    }

    /**
     * @param CommentRequest $request
     * @param Video $video
     * @return JsonResponse
     */
    public function store(CommentRequest $request, Video $video): JsonResponse
    {
        $comment = $video->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->input('content'),
        ]);

        return response()->json($comment, 201);
    }
}
