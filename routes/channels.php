<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat',function (){

});


Broadcast::channel('users', function ($user) {
    return ['id' => $user->id, 'username' => $user->username];
});

Broadcast::channel('chat.{recipientId}', function ($user, $recipientId) {
    return (int) $user->id === (int) $recipientId;
});
