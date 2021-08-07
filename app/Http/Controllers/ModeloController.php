<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class ModeloController extends Controller
{
    /**
     * Injecting the Model instance as contructor method .
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modelos = $this->modelo->all();
        return response()->json(['modelos' => $modelos], 200);
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
        $request->validate($this->modelo->rules(), $this->modelo->feedback());
        $modelo = $this->modelo;
        $modelo->marca_id = $request->get('marca_id');
        $modelo->modelo = $request->get('modelo');
        $imagem = $request->imagem;
        $modelo->imagem =  $imagem->store('images/modelos', 'public');
        $modelo->numero_portas = $request->get('numero_portas');
        $modelo->lugares = $request->get('lugares');
        $modelo->air_bag = $request->get('air_bag');
        $modelo->abs = $request->get('abs');
        $modelo->save();

        return response()->json(['modelo' => $modelo], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = $this->modelo->find($id);
        if ($modelo === null) {
            return response()->json(["error" => "Not Found"], 404);
        }
        return response()->json(['modelo' => $modelo], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function edit(Modelo $modelo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);
        if ($modelo === null) {
            return response()->json(["error" => "Not Found"], 404);
        }
        switch ($request->method()) {
            case 'PUT':
                $request->validate($modelo->rules(), $modelo->feedback());
                break;
            case 'PATCH':
                $dynamic_rules = [];
                foreach ($modelo->rules() as $input => $rule) {
                    if( array_key_exists( $input, $request->all() ) ) {
                        $dynamic_rules[$input] = $rule;
                    }
                }
                $request->validate($dynamic_rules, $modelo->feedback());
                break;
        }
        if ($request->get('marca_id') != null) {
            $modelo->marca_id = $request->get('marca_id');
        }
        if ($request->get('modelo') != null) {
            $modelo->modelo = $request->get('modelo');
        }
        if ($request->imagem != null) {
            Storage::disk('public')->delete($modelo->imagem);
            $imagem = $request->imagem;
            $modelo->imagem =  $imagem->store('images/modelos', 'public');
        }
        if ($request->get('numero_portas') != null) {
            $modelo->numero_portas = $request->get('numero_portas');
        }
        if ($request->get('lugares') != null) {
            $modelo->lugares = $request->get('lugares');
        }
        if ($request->get('air_bag') != null) {
            $modelo->air_bag = $request->get('air_bag');
        }
        if ($request->get('abs') != null) {
            $modelo->abs = $request->get('abs');
        }
        $modelo->update();
        return response()->json(['modelo' => $modelo], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if ($modelo === null) {
            return response()->json(["error" => "Not Found"], 404);
        }
        Storage::disk('public')->move($modelo->imagem, 'deleted_' . $modelo->imagem);
        $modelo->imagem = 'deleted_' . $modelo->imagem;
        $modelo->update();
        $modelo->delete();
        return response()->json(['modelo' => $modelo], 200);
    }
}
