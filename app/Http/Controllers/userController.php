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

    function findAll(Request $request)
    {
        $query = User::query();
        $pageSize = $request->get('rows', 10);
        $page = $request->get('page', 1);
        $filter = $request->get('filter', '');
        $sortField = $request->get('sortField', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');
        if ($filter) {
            $query->where(function ($q) use ($filter) {
                $q->where('users.id', 'like', '%' . $filter . '%')
                    ->orWhere('users.name', 'like', '%' . $filter . '%')
                    ->orWhere('users.email', 'like', '%' . $filter . '%')
                    ->orWhereHas('area', function ($q) use ($filter) {
                        $q->where('areas.nombre', 'like', '%' . $filter . '%')
                            ->orWhere('areas.descripcion', 'like', '%' . $filter . '%');
                    })
                    ->orWhereHas('departamento', function ($q) use ($filter) {
                        $q->where('departamentos.nombre', 'like', '%' . $filter . '%')
                            ->orWhere('departamentos.descripcion', 'like', '%' . $filter . '%');
                    });
            });
        }

        if (in_array($sortField, ['id', 'name', 'email', 'area.nombre', 'departamento.nombre'])) {
            if (strpos($sortField, 'area.') === 0) {
                $query->join('areas', 'users.area_id', '=', 'areas.id')
                    ->orderBy('areas.' . substr($sortField, 5), $sortOrder);
            } else if (strpos($sortField, 'departamento.') === 0) {
                $query->join('departamentos', 'users.departamento_id', '=', 'departamentos.id')
                    ->orderBy('departamentos.' . substr($sortField, 12), $sortOrder);
            } else {
                $query->orderBy($sortField, $sortOrder);
            }
        }

        $users = $query->with('area', 'departamento')->paginate($pageSize, ['*'], 'page', $page);

        return response()->json($users);
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
