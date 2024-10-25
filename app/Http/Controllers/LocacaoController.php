<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocacaoRequest;
use App\Repositories\LocacaoRepository;
use App\Models\Locacao;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locacao = new Locacao();
        $locacaoRepository = new LocacaoRepository($locacao);

        if ($request->has('filtro')) {
            $locacaoRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $locacaoRepository->selectAtributos($request->atributos);
        }

        return response()->json($locacaoRepository->getResultado(), 200);
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
     * @param  \App\Http\Requests\StoreLocacaoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLocacaoRequest $request)
    {
        $locacao = new Locacao();
        $request->validate($locacao->regras());
        $locacao->client_id = $request->cliente_id;
        $locacao->carro_id = $request->carro_id;
        $locacao->data_inicio_periodo = $request->data_inicio_periodo;
        $locacao->data_final_previsto_periodo = $request->data_final_previsto_periodo;
        $locacao->data_final_realizado_periodo = $request->data_final_realizado_periodoo;
        $locacao->valor_diaria = $request->valor_diaria;
        $locacao->km_inicial = $request->km_inicial;
        $locacao->km_final = $request->km_final;
        $locacao->save();

        return response()->json($locacao, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locacao = Locacao::with('modelo')->find($id);
        if (! $locacao) {
            return response()->json(['nao existe locacao com esse id'], 404);
        }

        return response()->json($locacao, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function edit(Locacao $locacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLocacaoRequest  $request
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Locacao $locacao)
    {
        if ($request->isMethod('patch')) {
            $regrasDinamicas = [];

            // Gerar regras dinâmicas apenas para os campos enviados
            foreach ($locacao->regras() as $input => $regra) {
                if ($request->has($input)) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas);
        } else {
            $request->validate($locacao->regras());
        }


        $locacao->fill($request->all());
        $locacao->save();

        return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locacao = Locacao::find($id);
        if (! $locacao) {
            return response()->json(['erro' => 'locação nao existe'], 404);
        }
        $locacao->delete();

        return response()->json(['success' => 'locação deletado com sucesso'], 201);
        //
    }
}
