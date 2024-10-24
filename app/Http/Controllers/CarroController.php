<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarroRequest;
use App\Http\Requests\UpdateCarroRequest;
use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;

class CarroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $carro = new Carro();
        $carroRepository = new CarroRepository($carro);

        if ($request->has('atributos_modelos')) {
            $atributos_modelo = 'modelo:id,' . $request->atributos_modelo;
            $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelo);
        } else {
            $carroRepository->selectAtributosRegistrosRelacionados('modelo');
        }

        if ($request->has('filtro')) {
            $carroRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $carroRepository->selectAtributos($request->atributos);
        }

        return response()->json($carroRepository->getResultado(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $carro = new Carro();

        $request->validate($carro->regras());
        $carro->modelo_id = $request->modelo_id;
        $carro->placa = $request->placa;
        $carro->disponivel = $request->disponivel;
        $carro->km = $request->km;
        $carro->save();

        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carro = Carro::with('modelo')->find($id);
        if (! $carro) {
            return response()->json(['nao existe carro com esse id'], 404);
        }

        return response()->json($carro, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function edit(Carro $carro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarroRequest  $request
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Carro $carro)
    {
        if ($request->isMethod('patch')) {
            $regrasDinamicas = [];

            // Gerar regras dinâmicas apenas para os campos enviados
            foreach ($carro->regras() as $input => $regra) {
                if ($request->has($input)) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas);
        } else {
            $request->validate($carro->regras());
        }


        $carro->fill($request->all());
        $carro->save();

        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $carro = Carro::find($id);
        if (! $carro) {
            return response()->json(['erro' => 'carro nao existe'], 404);
        }
        $carro->delete();

        return response()->json(['suscess' => 'carro deletado com sucesso'], 201);
    }
}
