<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Articol;
use Auth;

class ArticolsController extends Controller
{
   	public function articole()
	{
		 $currentUser = Auth::user();
		// dd($currentUser->first_name);
		  $query = Articol::all();
		 
		  return view('articole.index', [
            'articol'          => $query,
        ]);
	}
}
