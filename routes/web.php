<?php

use Illuminate\Support\Facades\Route;
use OpenAI\Laravel\Facades\OpenAI;

Route::get('/', function () {
    $response = OpenAI::chat()->create([
        'model' => 'gpt-4',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a friendly bot to help with web development'],
            ['role' => 'user', 'content' => 'Link me to laravel docs']
        ]
    ]);

    dd($response);

});
