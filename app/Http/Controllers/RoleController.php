<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Contador;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contar = new Contador();
        $num = $contar->contarModel(9);

        $roles = DB::table('roles')
            ->where('state', 'a')
            ->get();

        return Inertia::render('Rol/Index', [
            'roles' => $roles,
            'num' => $num,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        try {

            $rol = Role::create([
                'nombre' => $request->input('nombre'),
                'state' => 'a',
            ]);

            $rol->save();

            Session::flash('success', 'Rol agregado exitosamente.');
        } catch (\Exception $e) {
            Session::flash('error', 'Ocurrió un error al guardar rol.');
        }

        return to_route('rol.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        /*$request->validate([
            'name' => 'required|string|max:255',
            'telefono' => 'required|integer',
        ]);*/

        $rol = Role::findOrFail($id);
        $rol->update($request->all());

        return to_route('rol.index')->with('success', 'Rol actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rol = Role::findOrFail($id);
        $rol->update(['state' => 'i']);

        return to_route('rol.index')->with('success', 'Rol eliminado exitosamente.');
    }
}
