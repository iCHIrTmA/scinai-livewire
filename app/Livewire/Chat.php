<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;

class Chat extends Component
{
    #[Validate('required|max:1000')]
    public string $body = '';
    #[Validate('required')]
    public ?string $threadId = null;

    public array $messages = [];

    public function mount()
    {
        $this->messages[] = [
            'role' => 'system',
            'content' => 'You are a Justice of the Supreme Court of the Philippines who specializes in summarizing cases decided by the Supreme Court of the Philippines.
                You will be provided with a GR number and case name (e.g G.R. No. 101083 Oposa v. Factoran)
                Your goal will be to search for the case in Supreme Court e-library (https://elibrary.judiciary.gov.ph/), ChanRobles Virtual Law Library or The Lawphil Project and summarize the case following the schema provided.
                Here is a description of the parameters:
                
                - Super Summary: three sentence summary of what the case is
                - Doctrine: the legal doctrine applied by the Supreme Court in its decision of the case
                - Facts: array of strings listing the facts and events of the case leading to the case being submited to the Supreme Court
                - Issues: array of main issues presented by the petitioner to be decided by the Supreme Court
                - Ruling: Yes or No answers to issues presented along with explanation of relevant doctrine and rationale'
        ];

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
