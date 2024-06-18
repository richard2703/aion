<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class userController extends Controller
{
    //

    public function index()
    {
        return Inertia::render('Usuario/UsuarioIndex', [
            'usuarios' => User::with('area', 'departamento')->get(),
        ]);
    }

    function create()
    {
        return Inertia::render('Usuario/UsuarioCreate');
    }

    function store(Request $request)
    {

        $createNewUser = new CreateNewUser();
        $createNewUser->create($request->all());

        return redirect()->route('user.index');
    }

    function edit($id)
    {
        return Inertia::render('Usuario/UsuarioEdit', [
            'usuario' => User::find($id),
        ]);
    }

    function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update($request->only('name', 'email', 'area_id', 'departamento_id'));
        return redirect()->route('user.index');
    }
    function updatePassword(Request $request, $id)
    {
        if ($request->password === $request->password_confirmation) {
            $user = User::find($id);
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('user.index');
        }
    }

    function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['usuarios' => User::with('area', 'departamento')->get(),]);
    }
}
