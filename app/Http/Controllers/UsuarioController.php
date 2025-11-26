<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Contador;
use App\Models\Role;
use Inertia\Inertia;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contar = new Contador();
        $num = $contar->contarModel(11);

        $roles = Role::where('state', 'a')->where('nombre', '!=', 'Cliente')->get();

        $usuarios = Usuario::with(['rol'])
            ->where('state', 'a')
            ->orderBy('id', 'asc')
            ->get();

        return Inertia::render('Usuario/Index', [
            'usuarios' => $usuarios,
            'num' => $num,
            'roles' => $roles,
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
        /*$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'telefono' => 'required|integer',
            'rol'=>'required',
        ]);*/

        try {
            $usuario = Usuario::create([
                'nombre' => $request->input('nombre'),
                'telefono' => $request->input('telefono'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'id_rol' => $request->input('id_rol'),
                'id_empresa' => 1,
                'state' => 'a',
            ]);
            $usuario->save();

            Session::flash('success', 'Usuario agregado exitosamente.');
        } catch (\Exception $e) {
            Session::flash('error', 'Ocurrió un error al guardar usuario.');
        }

        return to_route('usuario.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
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

        $oferta = Usuario::findOrFail($id);
        $oferta->update($request->all());

        return to_route('usuario.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $oferta = Usuario::findOrFail($id);
        $oferta->update(['state' => 'i']);

        return to_route('usuario.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
