<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepositories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class MarcaController extends Controller
{
    /**
     * Injecting the Model instance as contructor method .
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $marca = new MarcaRepositories($this->marca);
        // implementando filtros
        if($request->has('ids')) {
            $marca->filterIds($request->ids);
        }
        if($request->has('marca')) {
            $marca->filterFieldExactly('marca', $request->marca);
        }
        if($request->has('fields')) {
            $marca->filterFields($request->fields);
        }
        if($request->has('fields_modelos')) {
            $marca->filterRelationsFields('modelos', $request->fields_modelos, 'marca_id');
        }
        if($request->has('per_page')) {
            return response()->json(['marcas' => $marca->getWithPagination($request->per_page)], 200);
        }
        return response()->json(['marcas' => $marca->getResult()], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());
        // alternativa 1
        // $imagem = $request->imagem;
        // $imagem_urn =  $imagem->store('images', 'public');
        // $marca = $this->marca->create(['marca' => $request->get('marca'), 'imagem' => $imagem_urn]);

        // alternativa 2
        // $marca = $this->marca;
        // $imagem = $request->imagem;
        // $marca->imagem =  $imagem->store('images/marcas', 'public');
        // $marca->marca = $request->get('marca');
        // $marca->save();

        // alternativa 3
        $marca = $this->marca;
        $marca->fill($request->all());
        $imagem = $request->imagem;
        $marca->imagem =  $imagem->store('images/marcas', 'public');
        $marca->save();

        return response()->json(['marca' => $marca], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);
        if ($marca === null) {
            return response()->json(["error" => "Not Found"], 404);
        }
        return response()->json(['marca' => $marca], 200);
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
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(["error" => "Not Found"], 404);
        }
        switch ($request->method()) {
            case 'PUT':
                $request->validate($marca->rules(), $marca->feedback());
                break;
            case 'PATCH':
                $dynamic_rules = [];
                foreach ($marca->rules() as $input => $rule) {
                    if( array_key_exists( $input, $request->all() ) ) {
                        $dynamic_rules[$input] = $rule;
                    }
                }
                $request->validate($dynamic_rules, $marca->feedback());
                break;
        }
        if ($request->imagem != null) {
            Storage::disk('public')->delete($marca->imagem);
        }
        $marca->fill($request->all());
        if ($request->imagem != null) {
            $imagem = $request->imagem;
            $marca->imagem =  $imagem->store('images/marcas', 'public');
        }
        $marca->save();

        return response()->json(['marca' => $marca], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(["error" => "Not Found"], 404);
        }
        Storage::disk('public')->move($marca->imagem, 'deleted_' . $marca->imagem);
        $marca->imagem = 'deleted_' . $marca->imagem;
        $marca->update();
        $marca->delete();
        return response()->json(['marca' => $marca], 200);
    }
}
