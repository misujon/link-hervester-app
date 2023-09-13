<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveLinksRequest;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function add() 
    {
        return view('add');
    }

    public function saveLinks(SaveLinksRequest $request)
    {
        dd($request->all());
    }
}
