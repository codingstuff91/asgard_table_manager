<?php

namespace App\Livewire;

use App\Models\Game;
use Livewire\Component;
use Illuminate\Support\Str;

class SearchGame extends Component
{
    public $searchGameInput = '';
    public $games = [];
    public $gamesCount = 0;

    public function render()
    {
        return view('livewire.search-game');
    }

    public function updated()
    {
        if (Str::length($this->searchGameInput) > 3) {

            $this->games = Game::where('name', 'like', '%' . $this->searchGameInput . '%')->get();

            $this->gamesCount = $this->games->count();
        }
    }
}
