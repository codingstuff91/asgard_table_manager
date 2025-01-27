<?php

namespace App\Http\Controllers;

use App\Models\Association;
use Illuminate\View\View;

class AssociationController extends Controller
{
    public function select(Association $association): View
    {
        return view('auth.register', ['association' => $association]);
    }
}
