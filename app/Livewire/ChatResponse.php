<?php

namespace App\Livewire;

use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;

class ChatResponse extends Component
{
    public ?string $threadId = null;
    public array $prompt = [];
    public array $messages = [];
    public string $response = '';

    public function mount()
    {
        // $this->enforceOutputSchema();
        $this->getResponse();
    }

    public function getResponse()
    {
        $assistant = OpenAI::assistants()->retrieve('asst_LVVF4qqwCkAnRhrAOprBfEKB');

        // add message to thread
        OpenAI::threads()
            ->messages()
            ->create(
                threadId: $this->threadId,
                parameters: $this->prompt
            );

        $run = OpenAI::threads()
                ->runs()
                ->create($this->threadId, ['assistant_id' => $assistant->id]);
        
        do {
            sleep(1);
    
            $run = OpenAI::threads()->runs()->retrieve(
                threadId: $run->threadId,
                runId: $run->id
            );
        } while ($run->status !== 'completed');

        $messages = OpenAI::threads()->messages()->list($this->threadId);

        $this->response = $messages->data[0]->content[0]->text->value;
    }

    public function render()
    {
        return view('livewire.chat-response');
    }
}
