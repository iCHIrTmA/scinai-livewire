<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;

class Chat extends Component
{
    #[Validate('required|max:1000')]

    public string $body = '';
    public ?string $threadId = null;

    public array $messages = [];

    public function mount()
    {
        $this->messages[] = ['role' => 'system', 'content' => "You are a Justice of the Supreme Court of the Philippines who specializes in Environmental Law."];

        // initialize thread
        if (! $this->threadId) {
            $thread = OpenAI::threads()->create([]);
            $this->threadId = $thread->id;
        }
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
}
