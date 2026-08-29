<?php

namespace App\Http\Controllers;

use App\Models\Association;
use Illuminate\View\View;

class AssociationController extends Controller
{
    public function choose(): View
    {
        $associations = Association::all();

        return view('auth.choose-association', compact('associations'));
    }

    public function select(Association $association): View
    {
        return view('auth.register', ['association' => $association]);
    }
}
