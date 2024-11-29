<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('global-video-notifications', function ($user) {
    return !is_null($user);
});
