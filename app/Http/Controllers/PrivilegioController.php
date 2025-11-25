<?php

namespace App\Http\Controllers;

use App\Models\Privilegio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Contador;
use App\Models\Role;
use Inertia\Inertia;

class PrivilegioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contar = new Contador();
        $num = $contar->contarModel(6);

        $privilegios = Privilegio::with('rol')->where('state', 'a')->get();
        $funcionalidades = Privilegio::select('funcionalidad')->distinct()->pluck('funcionalidad');

        $roles = Role::with('privilegios')->where('state', 'a')->get();

        foreach ($roles as $rol) {
            $rol->privilegiosAgrupados = $rol->privilegios
                ->groupBy('funcionalidad')
                ->map(function ($items) {
                    // Fusiona permisos por funcionalidad
                    return (object) [
                        'agregar' => $items->pluck('agregar')->contains(true) || false,
                        'borrar' => $items->pluck('borrar')->contains(true) || false,
                        'modificar' => $items->pluck('modificar')->contains(true) || false,
                        'leer' => $items->pluck('leer')->contains(true) || false,
                        'id' => $items->first()->id,
                        'funcionalidad' => $items->first()->funcionalidad,
                    ];
                });
        }

        return Inertia::render('Privilegio/Index', [
            'privilegios' => $privilegios,
            'roles' => $roles,
            'num' => $num,
            'funcionalidades' => $funcionalidades,
        ]);
    }

    public function asignar(Request $request, $idRol)
    {
        $rol = Role::findOrFail($idRol);

        $data = $request->input('privilegios', []);

        // Borramos los privilegios anteriores del rol
        Privilegio::where('id_rol', $idRol)->delete();

        foreach ($data as $funcionalidad => $permisos) {

            Privilegio::create([
                'id_rol' => $idRol,
                'funcionalidad' => $funcionalidad,
                'agregar' => isset($permisos['agregar']),
                'borrar' => isset($permisos['borrar']),
                'modificar' => isset($permisos['modificar']),
                'leer' => isset($permisos['leer']),
                'state' => 'a'
            ]);
        }

        return to_route('privilegio.index')->with('success', 'Privilegios actualizados correctamente');
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
            'rol_id' => 'required|exists:roles,id', // Validamos que el rol exista
            'funcion' => 'required',
        ]);

        $privilegio = new Privilegio();
        $privilegio->id_rol = $request->rol_id; // Guardamos el ID del rol
        $privilegio->funcionalidad = $request->funcion;
        $privilegio->agregar = $request->has('agregar') && $request->agregar !== null;
        $privilegio->borrar = $request->has('borrar') && $request->borrar !== null;
        $privilegio->modificar = $request->has('modificar') && $request->modificar !== null;
        $privilegio->leer = $request->has('leer') && $request->leer !== null;
        $privilegio->state = 'a';
        // dd($privilegio);
        $privilegio->save();

        return to_route('privilegio.index')->with('success', 'Privilegio creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Privilegio $privilegio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Privilegio $privilegio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Privilegio $privilegio)
    {
        $request->validate([
            'rol_id' => 'required|exists:roles,id', // Validamos que el rol exista
            'funcion' => 'required',
            'estado' => 'required|in:a,i',
        ]);

        $privilegio->id_rol = $request->rol_id; // Actualizamos el ID del rol
        $privilegio->funcionalidad = $request->funcion;
        $privilegio->agregar = $request->has('agregar');
        $privilegio->borrar = $request->has('borrar');
        $privilegio->modificar = $request->has('modificar');
        $privilegio->leer = $request->has('leer');
        $privilegio->state = $request->estado;
        $privilegio->save();

        return to_route('privilegio.index')->with('success', 'Privilegio actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Privilegio $privilegio)
    {
        $privilegio->state = 'i';
        $privilegio->save();
        return to_route('privilegio.index')->with('success', 'Privilegio desactivado exitosamente.');
    }
}
