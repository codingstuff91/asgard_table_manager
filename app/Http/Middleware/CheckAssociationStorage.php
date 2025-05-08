<?php

namespace App\Http\Middleware;

use App\Storages\AssociationStorage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAssociationStorage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! AssociationStorage::exists()) {
            $currentAssociation = Auth::user()->associations->first();

            AssociationStorage::put($currentAssociation);
        }

        return $next($request);
    }
}
