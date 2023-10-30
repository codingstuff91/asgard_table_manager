<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TableCard extends Component
{
    public $table;
    public $showDescription = false;
    public $showPlayers = false;

    public function render()
    {
        return view('livewire.table-card');
    }

    public function toggleDescription()
    {
        $this->showDescription = !$this->showDescription;
    }

    public function togglePlayers()
    {
        $this->showPlayers = !$this->showPlayers;
    }
}
