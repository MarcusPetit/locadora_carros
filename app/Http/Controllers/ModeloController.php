<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as FacadesStorage;

class ModeloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modelos = [];

        if ($request->has('atr')) {
            $atr = $request->atr;
            $modelos = Modelo::selectRaw($atr)->with('marca')->get();
        } else {
            $modelos = Modelo::with('marca')->get();
        }

        if ($request->has('atr_marca')) {
            $atr_marca = $request->atr_marca;
            $modelos = Modelo::with('marca:id, '.$atr_marca);
        }else {
            $modelos = Modelo::with('marca');
        }

        if ($request->has('filtros')) {
            $filtros = explode(';', $request->filtros);
            foreach ($filtros as $key => $condicao) {
                $valorCondicao = explode(':', $condicao);
                $modelos = Modelo::where($valorCondicao[0],$valorCondicao[1],$valorCondicao[3]);
            }
        }


        return response()->json($modelos, 200);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $modelo = new Modelo();

        $request->validate($modelo->regras());
        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens/modelos', 'public');
        $modelo->marca_id = $request->marca_id;
        $modelo->nome = $request->nome;
        $modelo->imagem = $imagem_urn;
        $modelo->numero_portas = $request->numero_portas;
        $modelo->lugares = $request->lugares;
        $modelo->air_bag = $request->air_bag;
        $modelo->abs = $request->abs;
        $modelo->save();

        return response()->json($modelo, 201);
    }


    /* Display the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = Modelo::with('marca')->find($id);

        if (! $modelo) {
            return response()->json(['nao existe modelo com esse id'], 404);
        }

        return response()->json($modelo, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function edit(Modelo $modelo) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Modelo $modelo)
    {
        if ($request->isMethod('patch')) {
            $regrasDinamicas = [];

            // Gerar regras dinâmicas apenas para os campos enviados
            foreach ($modelo->regras() as $input => $regra) {
                if ($request->has($input)) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $modelo->feedback());
        } else {
            $request->validate($modelo->regras(), $modelo->feedback());
        }

        // Tratamento do upload da imagem
        if ($request->hasFile('imagem')) {
            // Apagar a imagem anterior se existir
            if ($modelo->imagem && \Store::disk('public')->exists($modelo->imagem)) {
                \Storage::disk('public')->delete($modelo->imagem);
            }

            // Armazenar a nova imagem
            $imagem = $request->file('imagem');
            $caminho = $imagem->store('imagens/modelos', 'public');
            $modelo->imagem = $caminho;
        }

        $modelo->fill($request->except('imagem'));
        $modelo->save();

        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = Modelo::find($id);
        if (! $modelo) {
            return response()->json(['erro' => 'modelo nao existe'], 404);
        }


        FacadesStorage::disk('public')->delete($modelo->imagem);
        $modelo->delete();

        return response()->json(['suscess' => 'campo deletado com sucesso'], 201);
    }
}
