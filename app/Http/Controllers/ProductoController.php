<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Contador;
use App\Models\Categoria;
use App\Models\Promocion;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contar = new Contador();
        $num = $contar->contarModel(7);

        $productos = Producto::with('categoria', 'promocion')->where('state', 'a')->orderBy('id', 'asc')->get();
        $categorias = Categoria::where('state', 'a')->get();
        $promociones = Promocion::where('state', 'a')->orderBy('id', 'asc')->get();

        return Inertia::render('Producto/Index', [
            'productos' => $productos->map(function ($producto) {
                return array_merge($producto->toArray(), [
                    'precioConDescuento' => $producto->precioConDescuento(),
                ]);
            }),
            'num' => $num,
            'categorias' => $categorias,
            'promociones' => $promociones,
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
            $request->validate([
                'nombre' => 'required|string',
                'descripcion' => 'required|string',
                'fecha_ingreso' => 'required|date',
                'precio' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
            ]);

            Producto::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'fecha_ingreso' => $request->fecha_ingreso,
                'precio' => $request->precio,
                'stock' => $request->stock,
                'id_categoria' => $request->id_categoria,
                'id_promocion' => $request->id_promocion,
            ]);

            return to_route('producto.index')->with('success', 'Producto registrado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al registrar producto: ' . $e->getMessage());

            return to_route('producto.index')->with('error', 'Ocurrió un error al registrar el producto. Intente nuevamente.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        /*$request->validate([
            'rol_id' => 'required|exists:roles,id', // Validamos que el rol exista
            'funcion' => 'required',
            'estado' => 'required|in:a,i',
        ]);*/

        $producto = Producto::findOrFail($id);
        $producto->update($request->all());

        return to_route('producto.index')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->state = 'i';
        $producto->save();

        return to_route('producto.index')->with('success', 'Producto desactivado exitosamente.');
    }
}
