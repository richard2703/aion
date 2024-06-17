<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class userController extends Controller
{
    //

    public function index()
    {
        return view('user/index');
    }

    function create() {

    }

    function store(Request $request) {
        dd($request);
    }

    function show($id) {
        dd($id);
    }

    function edit($id) {
        dd($id);
    }

    function update(Request $request, $id) {
        dd($request);
    }

    function destroy($id) {
        dd($id);
    }
}
