<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Contador;
use Inertia\Inertia;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contar = new Contador();
        $num = $contar->contarModel(4);

        $productos = Producto::where('state', 'a')->orderBy('id', 'asc')->get();
        $inventarios = Inventario::with('producto')->where('state', 'a')->get();

        return Inertia::render('Inventario/Index', [
            'inventarios' => $inventarios,
            'num' => $num,
            'productos' => $productos,
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
        try {
            // Validación
            $request->validate([
                'cantidad' => 'required|integer|min:1',
                'descripcion' => 'required|string|max:255',
                'fecha' => 'required|date',
                'id_producto' => 'required|exists:productos,id',
                'tipo' => 'required|in:entrada,salida,producto terminado',
            ]);

            // Crear registro
            Inventario::create([
                'cantidad' => $request->cantidad,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
                'id_producto' => $request->id_producto,
                'tipo' => $request->tipo,
                'state' => 'a',
            ]);

            return to_route('inventario.index')->with('success', 'Movimiento de inventario registrado correctamente.');

        } catch (\Exception $e) {
            return to_route('inventario.index')->with('error', 'Error al registrar inventario: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventario $inventario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventario $inventario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $update = Inventario::findOrFail($id);
        $update->update($request->all());

        return to_route('inventario.index')->with('success', 'Inventario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $delete = Inventario::findOrFail($id);
        $delete->update(['state' => 'i']);

        return to_route('inventario.index')->with('success', 'Inventario eliminado exitosamente.');
    }
}
