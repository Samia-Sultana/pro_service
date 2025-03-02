<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('proservice', function () {
    return true;
});
