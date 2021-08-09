<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositories\ModeloRepositories;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $modelos = new ModeloRepositories($this->modelo);
        // implementando filtros
        if($request->has('ids')) {
            $modelos->filterIds($request->ids);
        }
        if($request->has('fields')) {
            $modelos->filterFields($request->fields);
        }
        if($request->has('fields_marca')) {
            $modelos->filterRelationsFields('marca', $request->fields_marca, 'id');
        }
        if($request->has('fields_carros')) {
            $modelos->filterRelationsFields('carros', $request->fields_carros, 'modelo_id');
        }
        return response()->json(['modelos' => $modelos->getResult()], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $modelo = $this->modelo;
        $modelo->fill($request->all());
        $imagem = $request->imagem;
        $modelo->imagem = $imagem->store('images/modelos', 'public');
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
        $modelo = $this->modelo->with('marca')->find($id);
        if ($modelo === null) {
            return response()->json(["error" => "Not Found"], 404);
        }
        return response()->json(['modelo' => $modelo], 200);
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
        if ($request->imagem != null) {
            Storage::disk('public')->delete($modelo->imagem);
        }
        $modelo->fill($request->all());
        if ($request->imagem != null) {
            $imagem = $request->imagem;
            $modelo->imagem =  $imagem->store('images/modelos', 'public');
        }
        $modelo->save();

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
