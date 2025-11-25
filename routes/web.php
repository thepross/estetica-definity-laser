<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\DetalleMenuController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PlanPagoController;
use App\Http\Controllers\PrivilegioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\CitasController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\PagoFacilWebHookController;
use Illuminate\Support\Facades\Route;
use App\Models\Producto;
use Inertia\Inertia;
use Illuminate\Foundation\Application;

Route::get('/', function () {
    // return view('landing');
    return Inertia::render('Landing');
});

Route::get('/dashboard', [ReporteController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::resource('/cliente', ClienteController::class)->names([
        'index' => 'cliente.index',
        'store' => 'cliente.store',
        'update' => 'cliente.update',
        'destroy' => 'cliente.destroy',
    ]);

    Route::resource('/empresa', EmpresaController::class)->names([
        'index' => 'empresa.index',
        'store' => 'empresa.store',
        'update' => 'empresa.update',
        'destroy' => 'empresa.destroy',
    ]);

    Route::put('/empresa/{empresa}/nombre', [EmpresaController::class, 'nombre'])->name('empresa.nombre');
    Route::put('/empresa/{empresa}/direccion', [EmpresaController::class, 'direccion'])->name('empresa.direccion');
    Route::put('/empresa/{empresa}/correo', [EmpresaController::class, 'correo'])->name('empresa.correo');
    Route::put('/empresa/{empresa}/telefono', [EmpresaController::class, 'telefono'])->name('empresa.telefono');

    Route::resource('/pago', PagoController::class)->names([
        'index' => 'pago.index',
        'store' => 'pago.store',
        'update' => 'pago.update',
        'destroy' => 'pago.destroy',
    ]);

    Route::resource('/privilegio', PrivilegioController::class)->names([
        'index' => 'privilegio.index',
        'store' => 'privilegio.store',
        'update' => 'privilegio.update',
        'destroy' => 'privilegio.destroy',
    ]);

    Route::resource('/producto', ProductoController::class)->names([
        'index' => 'producto.index',
        'store' => 'producto.store',
        'update' => 'producto.update',
        'destroy' => 'producto.destroy',
    ]);

    Route::resource('/promocion', PromocionController::class)->names([
        'index' => 'promocion.index',
        'store' => 'promocion.store',
        'update' => 'promocion.update',
        'destroy' => 'promocion.destroy',
    ]);

    Route::resource('/rol', RoleController::class)->names([
        'index' => 'rol.index',
        'store' => 'rol.store',
        'update' => 'rol.update',
        'destroy' => 'rol.destroy',
    ]);

    Route::resource('/servicio', ServicioController::class)->names([
        'index' => 'servicio.index',
        'store' => 'servicio.store',
        'update' => 'servicio.update',
        'destroy' => 'servicio.destroy',
    ]);

    Route::resource('/venta', VentaController::class)->names([
        'index' => 'venta.index',
        'store' => 'venta.store',
        'update' => 'venta.update',
        'destroy' => 'venta.destroy',
    ]);

    Route::resource('/categoria', CategoriaController::class)->names([
        'index' => 'categoria.index',
        'store' => 'categoria.store',
        'update' => 'categoria.update',
        'destroy' => 'categoria.destroy',
    ]);

    Route::resource('/usuario', UsuarioController::class)->names([
        'index' => 'usuario.index',
        'store' => 'usuario.store',
        'update' => 'usuario.update',
        'destroy' => 'usuario.destroy',
    ]);

    Route::post('detalle_ventas/calcular', [DetalleVentaController::class, 'calcularTotal'])->name('detalle_ventas.calcular');
    Route::get('ventas/{venta}/detalles', [VentaController::class, 'detalles'])->name('ventas.detalles');

    Route::resource('/reportes', ReporteController::class)->names(['index' => 'reportes.index',]);
    Route::get('/reportes-buscar', [ReporteController::class, 'buscador'])->name('reportes.buscar');
    Route::get('/estadisticas', [ReporteController::class, 'estadisticas'])->name('estadisticas.index');

    Route::resource('cita', CitasController::class);
    Route::resource('inventario', InventarioController::class);
    Route::resource('planes', PlanPagoController::class);
    Route::post('/ventas/{venta}/pagos', [PagoController::class, 'store'])->name('pagos.store');
    Route::post('/ventas/{venta}/plan', [PlanPagoController::class, 'guardarPlan'])->name('planes.store');
    Route::post('/ventas/{venta}/pagar-cuotas', [PlanPagoController::class, 'pagarCuota'])->name('planes.pagarCuota');
    Route::get('/ventas/{cuota}/pagar-cuotas', [PlanPagoController::class, 'pagarCuota2'])->name('planes.pagarCuota2');
    Route::get('/ventas/{venta}/pagar-qr', [PlanPagoController::class, 'pagarQR'])->name('planes.pagarQR');
    Route::post('/privilegios/asignar/{rol}', [PrivilegioController::class, 'asignar'])->name('privilegios.asignar');
});

Route::get('/serviciovue', function () {
    return Inertia::render('Servicios'); // Exactamente el nombre del archivo Vue, sin .vue
});

Route::get('/dashboardvue', [ReporteController::class, 'indexVue'])->middleware('auth')->name('dashboardvue');


//vista para intrusos
Route::get('/unauthorized', [EmpresaController::class, 'intruso'])->name('intruso');
Route::get('/cargar-estilo/{estilo}', [ReporteController::class, 'cargarEstilo'])->name('cargarEstilo');
Route::post('/confirmar-pago', [PagoController::class, 'confirmarPago'])->name('pago.confirmar');



Route::post('/pagofacil/callback', [PagoFacilWebHookController::class, 'callback'])
    ->name('pagofacil.callback');


require __DIR__ . '/auth.php';
