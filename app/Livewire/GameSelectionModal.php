<?php

namespace App\Livewire;

use Livewire\Component;

class GameSelectionModal extends Component
{
    public $showModal = false;

    public function render()
    {
        return view('livewire.game-selection-modal');
    }

    public function open()
    {
        $this->showModal = true;
    }

    public function close()
    {
        $this->showModal = false;
    }

    public function save()
    {
        $this->showModal = false;
    }
}
