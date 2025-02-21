<?php

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;


// class CheckRole
// {
//     /**
//      * Vérifie si l'utilisateur a le bon rôle.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \Closure  $next
//      * @param  string  $role
//      * @return mixed
//      */
//     public function handle(Request $request, Closure $next, $role)
//     {
//         if (auth()->check() && auth()->user()->role === $role) {
//             return $next($request);
//         }

//         //abort(403, 'Accès non autorisé');
//        // return redirect()->route('/')->with('error', 'Accès non autorisé');
//         return redirect()->route('home')->with('error', 'Accès non autorisé');


//     }
// }