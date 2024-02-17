<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventStoreRequest;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function create()
    {
        return view('event.create');
    }

    public function store(EventStoreRequest $request)
    {
//        dd($request->all());

        Event::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    }
}
