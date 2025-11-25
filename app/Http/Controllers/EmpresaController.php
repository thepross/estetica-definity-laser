<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Contador;
use Inertia\Inertia;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $datos = Empresa::find(1);

        // return view('Configuracion.index', compact('datos'));
        return Inertia::render('Configuracion/Index', [
            'datos' => $datos,
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Empresa $empresa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empresa $empresa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empresa $empresa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
        //
    }

    public function intruso()
    {
        return view('intruso');
    }

    public function nombre(Request $request, Empresa $empresa)
    {
        $empresa->nombre = $request->nombre;
        $empresa->save();

        return to_route('empresa.index')->with('success', 'Nombre actualizado exitosamente.');
    }

    public function direccion(Request $request, Empresa $empresa)
    {
        $empresa->direccion = $request->direccion;
        $empresa->save();

        return to_route('empresa.index')->with('success', 'Direccion actualizado exitosamente.');
    }

    public function correo(Request $request, Empresa $empresa)
    {
        $empresa->correo = $request->correo;
        $empresa->save();

        return to_route('empresa.index')->with('success', 'Correo electronico actualizado exitosamente.');
    }

    public function telefono(Request $request, Empresa $empresa)
    {
        $empresa->telefono = $request->telefono;
        $empresa->save();

        return to_route('empresa.index')->with('success', 'Telefono actualizado exitosamente.');
    }
}
