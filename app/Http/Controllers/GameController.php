<?php

namespace App\Http\Controllers;

use App\Repositories\GameRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function __construct(
        public GameRepository $gameRepository
    ) {
        //
    }

    public function create(): RedirectResponse
    {
        return redirect('/admin/games/create');
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function searchByCategory(Request $request): Collection
    {
        return $this->gameRepository->findByCategory($request->category);
    }
}
