<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use App\Http\Requests\StorePessoaRequest;
use App\Http\Requests\UpdatePessoaRequest;
use App\Repositories\PessoaRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Unique;

class PessoaController extends Controller
{
    public function __construct(Pessoa $pessoa){
    $this->pessoa =$pessoa;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $pessoaRepository = new PessoaRepository($this->pessoa);

        if($request->has('atributos_enderecos')) {
            $atributos_enderecos = 'enderecos:id,'.$request->atributos_enderecos;
            $pessoaRepository->selectAtributosRegistrosRelacionados($atributos_enderecos);
        } else {
            $pessoaRepository->selectAtributosRegistrosRelacionados('enderecos');
        }

        if($request->has('filtro')) {
            $pessoaRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $pessoaRepository->selectAtributos($request->atributos);
        } 

        return response()->json($pessoaRepository->getResultadoPaginado(3), 200);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePessoaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePessoaRequest $request)
    {
        //$pessoa = Pessoa::create($request->all());

        $request->validate($this->pessoa->rules(), $this->pessoa->feedback());

        $pessoa = $this->pessoa->create($request->all());
        return response()->json($pessoa, 201);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pessoa = $this->pessoa->with('enderecos')->find($id);
        if ($pessoa === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 401);
        }
        return response()->json($pessoa, 200);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePessoaRequest  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePessoaRequest $request, $id)
    {
        //$pessoa->update($request->all());
        $pessoa = $this->pessoa->find($id);

        if ($pessoa === null) {
            return response()->json(['erro' => 'Impossivel realizar a atualização. O recurso solicitado não existe'], 200);
        }
        
        if ($request->method() === 'PATCH') {
            
            $regrasDinamicas = array();

            //Percorrendo todas as regras definas no Model
            foreach ($pessoa->rules() as $input => $regra) {
                
                //Coleta apenas as regras aplicaveis aos parâmetros parcias da requisão
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas, $pessoa->feedback());

         } else {
            $request->validate($pessoa->rules(), $pessoa->feedback());
         }   
        

        $pessoa->update($request->all());
        return response()->json($pessoa, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pessoa = $this->pessoa->find($id);
        if ($pessoa === null) {
            return response()->json(['erro' => 'Impossivel realizar a exclusão. O recurso solicitado não existe'], 404);
        }
        $pessoa->delete();
        return response()->json(['msg' => 'A pessoa foi removida com sucesso!'], 200);
    }
}
