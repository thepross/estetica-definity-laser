<?php

namespace App\Http\Controllers;

use App\Models\PlanPago;
use App\Models\Venta;
use Illuminate\Http\Request;
use App\Models\Contador;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\Cuota;
use App\Services\PagoFacilService;
use Inertia\Inertia;

use function Symfony\Component\String\u;

class PlanPagoController extends Controller
{
    protected $pagoService;

    public function __construct(PagoFacilService $pagoService)
    {
        $this->pagoService = $pagoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contar = new Contador();
        $num = $contar->contarModel(8);

        $planes = PlanPago::where('state', 'a')->get();

        return Inertia::render('Planes/Index', [
            'planes' => $planes,
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
        dd($request->all());
        $request->validate([
            'cantidad_cuota' => 'required|numeric|min:0',
        ]);

        try {

            $plan = PlanPago::create([
                'cantidad_cuotas' => $request->input('cantidad_cuota'),
                'fecha_inicio' => $request->input('fecha_inicio'),
                'monto_cuota' => $request->input('monto_cuota'),
                'total_deuda' => $request->input('total_deuda'),
                'saldo_restante' => $request->input('saldo_restante'),
                'estado' => $request->input('estado'),
                'state' => 'a',
            ]);

            $plan->save();

            Session::flash('success', 'Plan agregado exitosamente.');
        } catch (\Exception $e) {
            Session::flash('error', 'Ocurrió un error al guardar Plan.');
        }

        return to_route('planes.index');
    }

    public function guardarPlan(Request $request, $id)
    {

        // dd($request->all());
        // Validamos los datos recibidos del formulario
        $request->validate([
            'cantidad_cuotas' => 'required|integer|min:1',
            'total_deuda' => 'required|numeric|min:0.01',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|in:pendiente,en_curso,finalizado',
            'cuotas' => 'required|array|min:1',  // Aseguramos que se reciban las cuotas
            'cuotas.*.monto' => 'required|numeric|min:0.01', // Validamos cada monto de cuota
            'cuotas.*.fecha' => 'required|date',  // Validamos cada fecha de cuota
        ]);

        // Creamos el plan de pago
        $plan = PlanPago::create([
            'id_venta' => $id,
            'cantidad_cuotas' => $request->cantidad_cuotas,
            'total_deuda' => $request->total_deuda,
            'monto_cuota' => 0,
            'fecha_inicio' => $request->fecha_inicio,
            'estado' => $request->estado,
            'state' => 'a'
        ]);

        // Crear las cuotas individualmente según lo enviado en el formulario
        foreach ($request->cuotas as $key => $cuota) {
            Cuota::create([
                'id_venta' => $id,
                'id_plan_pago' => $plan->id,
                'monto' => $cuota['monto'], // Usamos el monto enviado por el usuario
                'fecha_vencimiento' => Carbon::parse($request->fecha_inicio)->addMonth($key), // Fecha de vencimiento de cada cuota
                'estado' => 'pendiente',
                'state' => 'a'
            ]);
        }

        return to_route('venta.index')->with('success', 'Plan de pago creado y cuotas generadas.');
    }

    public function pagarCuota(Request $request, Venta $venta)
    {
        if (!$request->has('cuotas')) {
            return back()->with('error', 'Debe seleccionar al menos una cuota.');
        }


        $cuota = Cuota::whereIn('id', $request->cuotas)
            ->where('estado', 'pendiente')
            ->first();

        return to_route('planes.pagarCuota2', ['cuota' => $cuota->id]);
    }

    public function pagarQR(Venta $venta)
    {
        $resultado = ['qrBase64' => null, 'transactionId' => null];
        $venta->load('cliente');
        // dd($venta);
        try {
            $resultado = $this->pagoService->generarQr($venta);
            $venta->update([
                'pagofacil_transaction_id' => $resultado['transactionId'],
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al conectar con PagoFácil: ' . $e->getMessage()]);
        }

        // dd($venta);
        return Inertia::render('Venta/ShowQrVenta', [
            'venta' => $venta,
            'qrImage' => $resultado['qrBase64'],
            'callbackUrl' => route('pagofacil.callback'),
            // 'callbackUrl' => "https://thepross.xyz/pagos/callback",
        ]);
    }

    public function pagarCuota2(Cuota $cuota)
    {
        $resultado = ['qrBase64' => null, 'transactionId' => null];
        $cuota->load('venta.cliente');
        if ($cuota->estado !== 'pagado') {
            try {
                $resultado = $this->pagoService->generarQrParaCuota($cuota);
                $cuota->update([
                    'pagofacil_transaction_id' => $resultado['transactionId'],
                    // 'qr_image' => $resultado['qrBase64'],
                    // 'estado' => 'procesando',
                ]);
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Error al conectar con PagoFácil: ' . $e->getMessage()]);
            }
        } else {
            // dd('Ya tiene QR o está pagado');
        }
        return Inertia::render('Venta/ShowQr', [
            'cuota' => $cuota,
            'qrImage' => $resultado['qrBase64'],
            'callbackUrl' => route('pagofacil.callback'),
            // 'callbackUrl' => "https://thepross.xyz/pagos/callback",
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(PlanPago $planPago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlanPago $planPago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $plan = PlanPago::findOrFail($id);
        $plan->update($request->all());

        return to_route('planes.index')->with('success', 'Plan de Pago actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $plan = PlanPago::findOrFail($id);
        $plan->update(['state' => 'i']);

        return to_route('planes.index')->with('success', 'Plan de pago eliminado exitosamente.');
    }
}
