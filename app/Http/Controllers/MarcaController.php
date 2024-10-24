<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $marca = new Marca();
        $marcaRepository = new MarcaRepository($marca);

        if ($request->has('atributos_modelos')) {
            $atributos_modelos = 'modelos:id,' . $request->atributos_modelos;
            $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        } else {
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        if ($request->has('filtro')) {
            $marcaRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $marcaRepository->selectAtributos($request->atributos);
        }

        return response()->json($marcaRepository->getResultado(), 200);
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $marca = new Marca;

        $request->validate($marca->regras(), $marca->feedback());
        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens', 'public');
        $marca->nome = $request->nome;
        $marca->imagem = $imagem_urn;
        $marca->save();

        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = Marca::with('modelos')->find($id);
        if (! $marca) {
            return response()->json(['nao existe marca com esse id'], 404);
        }

        return response()->json($marca, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Marca $marca) {}

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Marca $marca)
    {

        if ($request->isMethod('patch')) {
            $regrasDinamicas = [];

            // Gerar regras dinâmicas apenas para os campos enviados
            foreach ($marca->regras() as $input => $regra) {
                if ($request->has($input)) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());
        } else {
            $request->validate($marca->regras(), $marca->feedback());
        }

        // Tratamento do upload da imagem
        if ($request->hasFile('imagem')) {
            // Apagar a imagem anterior se existir
            if ($marca->imagem && \Storege::disk('public')->exists($marca->imagem)) {
                \Storage::disk('public')->delete($marca->imagem);
            }

            // Armazenar a nova imagem
            $imagem = $request->file('imagem');
            $caminho = $imagem->store('imagens', 'public');
            $marca->imagem = $caminho;
        }

        $marca->fill($request->except('imagem'));
        $marca->save();

        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = Marca::find($id);
        if (! $marca) {
            return response()->json(['erro' => 'marca nao existe'], 404);
        }

        Storage::disk('public')->delete($marca->imagem);
        $marca->delete();

        return response()->json(['suscess' => 'campo deletado com sucesso'], 201);
    }
}
