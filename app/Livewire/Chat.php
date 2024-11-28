<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;

class Chat extends Component
{
    #[Validate('required|max:1000')]

    public string $body = '';

    public array $messages = [];

    public function mount()
    {
        $this->messages[] = ['role' => 'system', 'content' => "You are a Justice of the Supreme Court of the Philippines who specializes in Environmental Law."];
    }

    public function send()
    {
        $this->validate();
        $this->messages[] = ['role' => 'user', 'content' => $this->body];
        $this->messages[] = ['role' => 'assistant', 'content' => ''];
        $this->body = '';
    }

    public function render()
    {
        return view('livewire.chat');
    }

    // public function mount()
    // {
    //     $response = OpenAI::chat()->create([
    //         'model' => 'gpt-4',
    //         'messages' => [
    //             ['role' => 'system', 'content' => 'You are a friendly bot to help with web development'],
    //             ['role' => 'user', 'content' => 'Link me to laravel docs']
    //         ]
    //     ]);
    
    //     dd($response);
    // }
    // public function render()
    // {
    //     return view('livewire.chat');
    // }
}
