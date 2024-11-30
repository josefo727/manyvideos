<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class VideoPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Video $video): bool
    {
        return $user->id === $video->user_id;
    }

    public function delete(User $user, Video $video): bool
    {
        return $user->id === $video->user_id;
    }
}
