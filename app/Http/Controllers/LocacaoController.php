<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Repositories\LocacaoRepositories;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    /**
     * Injecting the Model instance as contructor method .
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locacao = new LocacaoRepositories($this->locacao);
        // implementando filtros
        if($request->has('ids')) {
            $locacao->filterIds($request->ids);
        }
        if($request->has('fields')) {
            $locacao->filterFields($request->fields);
        }
        if($request->has('fields_cliente')) {
            $locacao->filterRelationsFields('cliente', $request->fields_cliente, 'id');
        }
        if($request->has('fields_carro')) {
            $locacao->filterRelationsFields('carro', $request->fields_carro, 'id');
        }

        return response()->json(['locacaos' => $locacao->getResult()], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->locacao->rules(), $this->locacao->feedback());
        $locacao = $this->locacao;
        $locacao->fill($request->all());
        $locacao->save();

        return response()->json(['locacao' => $locacao], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locacao = $this->locacao->with('cliente')->with('carro')->find($id);
        if ($locacao === null) {
            return response()->json(["error" => "Not Found"], 404);
        }

        return response()->json(['locacao' => $locacao], 200);
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
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
            return response()->json(["error" => "Not Found"], 404);
        }
        switch ($request->method()) {
            case 'PUT':
                $request->validate($locacao->rules(), $locacao->feedback());
                break;
            case 'PATCH':
                $dynamic_rules = [];
                foreach ($locacao->rules() as $input => $rule) {
                    if( array_key_exists( $input, $request->all() ) ) {
                        $dynamic_rules[$input] = $rule;
                    }
                }
                $request->validate($dynamic_rules, $locacao->feedback());
                break;
        }
        $locacao->fill($request->all());
        $locacao->save();

        return response()->json(['locacao' => $locacao], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null) {
            return response()->json(["error" => "Not Found"], 404);
        }
        $locacao->delete();

        return response()->json(['locacao' => $locacao], 200);
    }
}
