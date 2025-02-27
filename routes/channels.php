<?php
// routes/channels.php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('lottery', function ($user) {
    return $user != null;
});
