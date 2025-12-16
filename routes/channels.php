<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('usuario.{userId}.pagos', function ($user, $userId) {
    return $user->id === $userId;
});
