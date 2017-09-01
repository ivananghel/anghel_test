<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Ware;
use Auth;

class WareController extends Controller
{
   	public function Ware()
	{
		 $currentUser = Auth::user();
		 // $query = Ware::all();


 $agents = Ware::all();
		  dd($agents);
		 
		  return view('ware.index', [
            'ware'          => $query,
        ]);
	}


}
