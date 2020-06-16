<?php

Broadcast::channel('user.*', function ($user) {
   return $user->id === auth()->user()->id;
});