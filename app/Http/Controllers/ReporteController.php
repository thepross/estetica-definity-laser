<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Contador;
use App\Models\Usuario;
use App\Models\Venta;
use App\Models\User;
use App\Models\Pago;
use App\Models\Cuota;
use App\Models\Producto;
use App\Models\Cliente;
use DateTime;
use Inertia\Inertia;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ============================
        // 1. Cantidad de ventas
        // ============================
        $cantidadVentas = Venta::where('state', 'a')->count();

        // ============================
        // 2. Total realmente recaudado
        // ============================

        // Pagos individuales
        $recaudoPagos = Pago::where('state', 'a')
            ->sum('monto');

        // Cuotas pagadas
        $recaudoCuotas = Cuota::where('state', 'a')
            ->where('estado', 'pagado')
            ->sum('monto');

        $cantidadVendida = $recaudoPagos + $recaudoCuotas;

        // ============================
        // 3. Cantidad de clientes
        // ============================
        $cantidadClientes = Cliente::where('state', 'a')->count();

        // ============================
        // 4. Cantidad de visitas
        // ============================
        $cantidadVisitas = Contador::sum('visitas');

        // ============================
        // 5. Ventas por mes
        // ============================
        $ventasMes = Venta::selectRaw("EXTRACT(MONTH FROM fecha_venta) AS mes, COUNT(*) AS cantidad")
            ->where('state', 'a')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $mes = [];
        $cantidad = [];

        foreach ($ventasMes as $item) {
            $fecha = DateTime::createFromFormat('!m', $item->mes);
            $mes[] = $fecha->format('F');
            $cantidad[] = $item->cantidad;
        }

        // ============================
        // 6. Ventas por día
        // ============================
        $ventasDia = Venta::selectRaw("TO_CHAR(fecha_venta, 'YYYY-MM-DD') AS dia, COUNT(*) AS cantidad")
            ->where('state', 'a')
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        $dias = [];
        $cantidadDias = [];

        foreach ($ventasDia as $item) {
            $dias[] = $item->dia;
            $cantidadDias[] = $item->cantidad;
        }

        // ============================
        // 7. Productos más vendidos
        // ============================
        $productosTop = DB::select("
            SELECT p.nombre AS producto, SUM(dv.cantidad) AS total_vendido
            FROM productos p
            INNER JOIN detalle_venta dv ON p.id = dv.id_producto
            INNER JOIN ventas v ON dv.id_venta = v.id
            WHERE v.state = 'a'
            GROUP BY p.id, p.nombre
            ORDER BY total_vendido DESC
            LIMIT 5
        ");

        // ============================
        // RESPUESTA FINAL
        // ============================
        return Inertia::render('Reportes/Index', [
            'cantidadVentas' => $cantidadVentas,
            'cantidadVendida' => $cantidadVendida,
            'cantidadClientes' => $cantidadClientes,
            'cantidadVisitas' => $cantidadVisitas,
            'mes' => $mes,
            'cantidad' => $cantidad,
            'dias' => $dias,
            'cantidadDias' => $cantidadDias,
            'productosTop' => $productosTop,
        ]);
    }

    public function indexVue()
    {
        $cantidadVentas = DB::table('ventas')
            ->where('state', 'a')
            ->count();

        $cantidadVendida = DB::table('ventas')
            ->where('state', 'a')
            ->sum('total');

        $cantidadClientes = DB::table('clientes')
            ->where('state', 'a')
            ->count();

        $cantidadVisitas = DB::table('contadors')
            ->sum('visitas');

        $ventasMes = DB::select("select date_part('month', fecha_venta) as mes, count(*) as cantidad 
                                from ventas 
                                where state='a' 
                                group by mes 
                                order by mes asc");

        $mes = [];
        $cantidad = [];
        foreach ($ventasMes as $item) {
            $fecha = DateTime::createFromFormat('!m', $item->mes);
            array_push($mes, $fecha->format('F'));
            array_push($cantidad, $item->cantidad);
        }
        ;
        //dd($mes, $cantidad);

        $ventasDia = DB::select("select TO_CHAR(fecha_venta, 'YYYY-MM-DD') as dia, count(*) as cantidad 
                                from ventas 
                                where state='a' 
                                group by dia 
                                order by dia asc");

        $dias = [];
        $cantidadDias = [];
        foreach ($ventasDia as $item) {
            array_push($dias, $item->dia);
            array_push($cantidadDias, $item->cantidad);
        }
        //dd($dias, $cantidadDias);

        $productosTop = DB::select("
            SELECT p.nombre AS producto, SUM(dv.cantidad) AS total_vendido
            FROM productos p
            INNER JOIN detalle_venta dv ON p.id = dv.id_producto
            INNER JOIN ventas v ON dv.id_venta = v.id
            WHERE v.state = 'a'
            GROUP BY p.id, p.nombre
            ORDER BY total_vendido DESC
            LIMIT 5
        ");

        // return view('Reportes.index', compact('cantidadVentas','cantidadVendida','cantidadClientes','cantidadVisitas','mes','cantidad','dias','cantidadDias','productosTop'));

        return Inertia::render('Reportes/Index', [
            'cantidadVentas' => $cantidadVentas,
            'cantidadVendida' => $cantidadVendida,
            'cantidadClientes' => $cantidadClientes,
            'cantidadVisitas' => $cantidadVisitas,
            'mes' => $mes,
            'cantidad' => $cantidad,
            'dias' => $dias,
            'cantidadDias' => $cantidadDias,
            'productosTop' => $productosTop,
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function buscador(Request $request)
    {
        //$rutaTecnos = 'http://127.0.0.1:8000/';
        $rutaTecnos = 'http://mail.tecnoweb.org.bo/inf513/grupo05sc/estetica-definity-laser/public/';
        $search = strtolower($request->input('buscar'));
        $tablas = [
            ['users', 'email', 'usuario'],
            ['users', 'nombre', 'usuario'],
            ['users', 'telefono', 'usuario'],
            ['roles', 'nombre', 'rol'],
            ['privilegios', 'funcionalidad', 'privilegio'],
            ['clientes', 'nombre', 'cliente'],
            ['clientes', 'direccion', 'cliente'],
            ['clientes', 'telefono', 'cliente'],
            ['citas', 'fecha_hora', 'cita'],
            ['servicios', 'nombre', 'servicio'],
            ['servicios', 'descripcion', 'servicio'],
            ['pagos', 'tipo_pago', 'pago'],
            ['pagos', 'estado_pago', 'pago'],
            ['pagos', 'fecha_pago', 'pago'],
            ['ventas', 'estado', 'venta'],
            ['ventas', 'fecha_venta', 'venta'],
            ['ventas', 'total', 'venta'],
            ['inventario', 'descripcion', 'inventario'],
            ['inventario', 'tipo', 'inventario'],
            ['productos', 'nombre', 'producto'],
            ['productos', 'descripcion', 'producto'],
            ['categorias', 'nombre', 'categoria'],
            ['promociones', 'nombre', 'promocion'],
            ['promociones', 'descripcion', 'promocion'],
            ['promociones', 'descuento', 'promocion'],
            ['promociones', 'fecha_inicio', 'promocion'],
            ['promociones', 'fecha_fin', 'promocion'],
        ];

        $data = [];

        foreach ($tablas as $tabla) {
            // Convertimos la columna a text para evitar errores con números o fechas
            $columnText = "$tabla[1]::text";

            $resultados = DB::table($tabla[0])
                ->select(DB::raw("'$rutaTecnos$tabla[2]' as ruta, $tabla[1] as nombre, '$tabla[0]' as modelo"))
                ->whereRaw("lower($columnText) like ?", ["%$search%"])
                ->get();

            $data = array_merge($data, $resultados->toArray());
        }

        // return view('Estadisticas.index', compact('data'));
        return Inertia::render('Estadisticas/Index', [
            'data' => $data,
        ]);
    }

    public function estadisticas()
    {
        $datos = Contador::select('nombre', 'visitas')->orderBy('id', 'asc')->get();

        // return view('Estadisticas.resultado', compact('datos'));
        return Inertia::render('Estadisticas/Resultado', [
            'datos' => $datos,
        ]);
    }

    public function cargarEstilo($id)
    {
        $usuario = auth()->user()->id;

        $usuario2 = User::find($usuario);
        $usuario2->estilo = $id;
        $usuario2->update();
        return redirect()->back();
    }
}
