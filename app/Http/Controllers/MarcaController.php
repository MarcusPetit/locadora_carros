<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marca = Marca::all();

        return response()->json($marca, 200);
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

        $marca = Marca::create($request->all());

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
        $marca = Marca::find($id);
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
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = Marca::find($id);
        if (! $marca) {
            return response()->json(['erro' => 'marca nao existe'], 404);
        }
        $marca->update($request->all());

        return response()->json($marca, 201);
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
        $marca->delete();

        return response()->json(['suscess' => 'campo deletado com sucesso'], 201);
    }
}
