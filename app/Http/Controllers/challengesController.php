<?php

namespace App\Http\Controllers;

use App\Models\challenges;
use Illuminate\Http\Request;
use Inertia\Inertia;

class challengesController extends Controller
{

    public function index()
    {
        $challenges = challenges::all();
        dd($challenges);
        return Inertia::render('challenges/challengesIndex', [
            'challenges' => $challenges,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(challenges $challenges)
    {
        //
    }

    public function edit(challenges $challenges)
    {
        //
    }

    public function update(Request $request, challenges $challenges)
    {
        //
    }

    public function destroy(challenges $challenges)
    {
        //
    }
}
