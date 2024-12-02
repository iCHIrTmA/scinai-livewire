<?php

namespace App\Livewire;

use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;

class ChatResponse extends Component
{
    public ?string $threadId = null;
    public array $prompt = [];
    public array $messages = [];
    public ?string $response = null;

    public function mount()
    {
        // $this->enforceOutputSchema();
        $this->js('$wire.getResponse()');
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
        OpenAI::chat()
            ->createStreamed([
                'model' => 'gpt-3.5-turbo', // here you can change the model to your preference
                'messages' => $this->messages,
            ]);

        $stream = OpenAI::threads()
                ->runs()
                ->createStreamed($this->threadId, ['assistant_id' => $assistant->id]);
        
        foreach ($stream as $run) {
            if ($run->event !== 'thread.message.delta') {
                continue;
            }

            $partial = $run->response->delta->content[0]->text->value;

            $this->response .= $partial;
            $this->stream('stream-'.$this->getId(), $partial);
        }
    }

    public function render()
    {
        return view('livewire.chat-response');
    }
}
