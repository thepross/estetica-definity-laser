<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Contador;
use App\Models\Cliente;
use App\Models\Cuota;
use App\Models\Venta;
use App\Services\PagoFacilService;
use GuzzleHttp\Client;
use Inertia\Inertia;

class PagoController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $usuario = auth()->user();

        $contar = new Contador();
        $num = $contar->contarModel(5);

        $rol = $usuario->rol->nombre;
        $pagos = Pago::with('venta.cliente')->where('state', 'a')->get();

        return Inertia::render('Pago/Index', [
            'pagos' => $pagos,
            'num' => $num,
            // 'rol' => $rol,
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
    // public function store(Request $request)
    // {
    //     /*$request->validate([
    //         'rol_id' => 'required|exists:roles,id', // Validamos que el rol exista
    //         'funcion' => 'required',
    //     ]);*/
    //     $cliente_id = Auth::user()->cliente->id;

    //     if($request->metodo == "Pago-Facil"){
    //         $loClient = new Client(); 
    //         $loUserAuth = $loClient->post( 
    //         'https://serviciostigomoney.pagofacil.com.bo/api/servicio/login', [ 
    //         'headers' =>    
    //         ['Accept' => 'application/json'], 
    //         'json' =>       
    //         array( 
    //         'TokenService' =>      
    //         "51247fae280c20410824977b0781453df59fad5b23bf2a0d14e884482f91e09078dbe5966e0b970ba696ec4caf9aa5661802935f86717c481f1670e63f35d5041c31d7cc6124be82afedc4fe926b806755efe678917468e31593a5f427c79cdf016b686fca0cb58eb145cf524f62088b57c6987b3bb3f30c2082b640d7c52907", 
    //         'TokenSecret' =>      
    //         "9E7BC239DDC04F83B49FFDA5" 
    //         ) 
    //         ]); 
    //         $laTokenAuth = json_decode($loUserAuth->getBody()->getContents());
    //         //dd($laTokenAuth);
    //         if($laTokenAuth->error == 0){
    //             $loClient = new Client(); 
    //             $laPrepararPago = $loClient->post( 
    //             'https://serviciostigomoney.pagofacil.com.bo/api/servicio/pagotigomoney', [ 
    //                 'headers' => [ 
    //                 'Accept' => 'application/json', 
    //                 'Authorization' => 'Bearer '. $laTokenAuth->values, 
    //                 ], 
    //                 'json' => array( 
    //                     "tcCommerceID"=> "d029fa3a95e174a19934857f535eb9427d967218a36ea014b70ad704bc6c8d1c", 
    //                     "tcNroPago"=> "20001", 
    //                     "tcNombreUsuario"=> "Jhon Doe", 
    //                     "tnCiNit"=> 7777777, 
    //                     "tnTelefono"=> 60000000, 
    //                     "tcCorreo"=> "micorreo@mail.com", 
    //                     "tcCodigoClienteEmpresa"=> "9", 
    //                     "tnMontoClienteEmpresa"=> "1", 
    //                     "tnMoneda"=> 2, 
    //                     "tcUrlCallBack"=> "http://mail.tecnoweb.org.bo/inf513/grupo12sc/Restaurant/public/confirmar-pago", 
    //                     "tcUrlReturn"=> "http://mail.tecnoweb.org.bo/inf513/grupo12sc/Restaurant/public/pago", 
    //                     "taPedidoDetalle"=> [ 
    //                         array(
    //                             "Serial"=> 1, 
    //                             "Producto"=> "Borrador", 
    //                             "Cantidad"=> 1, 
    //                             "Precio"=> "1", 
    //                             "Descuento"=> 0, 
    //                             "Total"=> "1" 
    //                         )
    //                     ] 
    //                 ) 
    //             ]); 
    //             $laRespuestaPrepararPago= json_decode($laPrepararPago->getBody()->getContents());
    //             dd($laRespuestaPrepararPago);
    //         }else{

    //         }
    //     }


    //     $pago = new Pago();
    //     $pago->id_cliente = $request->id_cliente; 
    //     $pago->id_venta = $request->id_venta;
    //     $pago->metodo_pago = $request->metodo;
    //     //$pago->transaccion = $request->metodo == "Pago-Facil" ? $laRespuestaPrepararPago->values : 0;
    //     $pago->estado = $request->metodo == "Pago-Facil" ? "p" : "a";
    //     $pago->save();

    //     return redirect()->route('pago.index')->with('success', 'Pago creado exitosamente.');
    // }

    public function store(Request $request, $ventaId)
    {
        // dd($request->all());
        $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'tipo_pago' => 'required',
            'id_cuota' => 'nullable|exists:cuotas,id',
        ]);

        $pago = Pago::create([
            'id_venta' => $ventaId,
            'id_cuota' => $request->id_cuota,
            'monto' => $request->monto,
            'tipo_pago' => $request->tipo_pago,
            'estado_pago' => 'pagado',
            'fecha_pago' => now()
        ]);
        if ($pago->save()) {
            Venta::find($ventaId)->update(['estado' => 'pagado']);
        }

        // Actualizar estado de la cuota si se pagó totalmente
        if ($request->id_cuota) {
            $cuota = Cuota::find($request->id_cuota);
            if ($cuota->saldo() <= 0) {
                $cuota->update(['estado' => 'pagado']);
            }
        }

        return to_route('venta.index')->with('success', 'Pago registrado correctamente.');
    }


    public function confirmarPago(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(Pago $pago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pago $pago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago)
    {
        /*$request->validate([
            'rol_id' => 'required|exists:roles,id', // Validamos que el rol exista
            'funcion' => 'required',
            'estado' => 'required|in:a,i',
        ]);*/

        // $pago->id_cliente = $request->id_cliente; // No se debe cambiar el cliente del pago
        // $pago->id_venta = $request->id_venta; // No se debe cambiar la venta
        $pago->tipo_pago = $request->tipo_pago;
        $pago->estado_pago = $request->estado_pago;
        $pago->save();

        return to_route('pago.index')->with('success', 'Pago actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago)
    {
        $pago->state = 'i';
        $pago->save();

        return to_route('pago.index')->with('success', 'Pago desactivado exitosamente.');
    }
}
