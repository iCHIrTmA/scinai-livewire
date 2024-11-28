<?php

use App\Livewire\Chat;
use Illuminate\Support\Facades\Route;
use OpenAI\Laravel\Facades\OpenAI;

Route::get('/', Chat::class);
