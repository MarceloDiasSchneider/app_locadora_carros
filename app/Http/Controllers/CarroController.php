<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Repositories\CarroRepositories;
use Illuminate\Http\Request;

class CarroController extends Controller
{
    /**
     * Injecting the Model instance as contructor method .
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carro = new CarroRepositories($this->carro);
        // implementando filtros
        if($request->has('ids')) {
            $carro->filterIds($request->ids);
        }
        if($request->has('fields')) {
            $carro->filterFields($request->fields);
        }
        if($request->has('fields_modelo')) {
            $carro->filterRelationsFields('modelo', $request->fields_modelo, 'id');
        }

        return response()->json(['carros' => $carro->getResult()], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules(), $this->carro->feedback());
        $carro = $this->carro;
        $carro->fill($request->all());
        $carro->save();

        return response()->json(['marca' => $carro], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carro = $this->carro->with('modelo')->find($id);
        if ($carro === null) {
            return response()->json(["error" => "Not Found"], 404);
        }
        
        return response()->json(['carro' => $carro], 200);
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
        $carro = $this->carro->find($id);
        if ($carro === null) {
            return response()->json(["error" => "Not Found"], 404);
        }
        switch ($request->method()) {
            case 'PUT':
                $request->validate($carro->rules(), $carro->feedback());
                break;
            case 'PATCH':
                $dynamic_rules = [];
                foreach ($carro->rules() as $input => $rule) {
                    if( array_key_exists( $input, $request->all() ) ) {
                        $dynamic_rules[$input] = $rule;
                    }
                }
                $request->validate($dynamic_rules, $carro->feedback());
                break;
        }
        $carro->fill($request->all());
        $carro->save();

        return response()->json(['carro' => $carro], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);
        if ($carro === null) {
            return response()->json(["error" => "Not Found"], 404);
        }
        $carro->delete();

        return response()->json(['carro' => $carro], 200);
    }
}
