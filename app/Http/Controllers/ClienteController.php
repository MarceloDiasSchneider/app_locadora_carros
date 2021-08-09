<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Repositories\ClienteRepositories;
use Illuminate\Http\Request;
class ClienteController extends Controller
{
    /**
     * Injecting the Model instance as contructor method .
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cliente = new ClienteRepositories($this->cliente);
        // implementando filtros
        if($request->has('ids')) {
            $cliente->filterIds($request->ids);
        }
        if($request->has('fields')) {
            $cliente->filterFields($request->fields);
        }
        if($request->has('fields_locacoes')) {
            $cliente->filterRelationsFields('locacoes', $request->fields_locacoes, 'cliente_id');
        }

        return response()->json(['clientes' => $cliente->getResult()], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->cliente->rules(), $this->cliente->feedback());
        $cliente = $this->cliente;
        $cliente->fill($request->all());
        $cliente->save();

        return response()->json(['cliente' => $cliente], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = $this->cliente->with('locacoes')->find($id);
        if ($cliente === null) {
            return response()->json(["error" => "Not Found"], 404);
        }

        return response()->json(['cliente' => $cliente], 200);
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
        $cliente = $this->cliente->find($id);
        if ($cliente === null) {
            return response()->json(["error" => "Not Found"], 404);
        }
        switch ($request->method()) {
            case 'PUT':
                $request->validate($cliente->rules(), $cliente->feedback());
                break;
            case 'PATCH':
                $dynamic_rules = [];
                foreach ($cliente->rules() as $input => $rule) {
                    if( array_key_exists( $input, $request->all() ) ) {
                        $dynamic_rules[$input] = $rule;
                    }
                }
                $request->validate($dynamic_rules, $cliente->feedback());
                break;
        }
        $cliente->fill($request->all());
        $cliente->save();

        return response()->json(['cliente' => $cliente], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);
        if ($cliente === null) {
            return response()->json(["error" => "Not Found"], 404);
        }
        $cliente->delete();

        return response()->json(['cliente' => $cliente], 200);
    }
}
