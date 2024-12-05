<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\User;
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
        $comments = $video
            ->comments()
            ->with('user:id,name')
            ->latest()
            ->get();

        return response()->json($comments, 200);
    }

    /**
     * @param CommentRequest $request
     * @param Video $video
     * @param User $user
     * @return JsonResponse
     */
    public function store(CommentRequest $request, Video $video, User $user): JsonResponse
    {
        $comment = $video->comments()->create([
            'user_id' => $user->id,
            'content' => $request->input('content'),
        ]);

        return response()->json($comment, 201);
    }
}
