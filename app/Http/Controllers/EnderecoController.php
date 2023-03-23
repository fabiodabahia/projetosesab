<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Http\Requests\StoreEnderecoRequest;
use App\Http\Requests\UpdateEnderecoRequest;
use App\Repositories\EnderecoRepository;
use Illuminate\Http\Request;
class EnderecoController extends Controller
{
    public function __construct(Endereco $endereco){
        $this->endereco =$endereco;
        }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $enderecoRepository = new EnderecoRepository($this->endereco);

        if($request->has('atributos_pessoa')) {
            $atributos_pessoa = 'pessoa:id,'.$request->atributos_pessoa;
            $enderecoRepository->selectAtributosRegistrosRelacionados($atributos_pessoa);
        } else {
            $enderecoRepository->selectAtributosRegistrosRelacionados('pessoa');
        }

        if($request->has('filtro')) {
            $enderecoRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $enderecoRepository->selectAtributos($request->atributos);
        } 

        return response()->json($enderecoRepository->getResultado(), 200);
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
     * @param  \App\Http\Requests\StoreEnderecoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEnderecoRequest $request)
    {
                //$endereco = Endereco::create($request->all());

                $request->validate($this->endereco->rules());

                $endereco = $this->endereco->create($request->all());
                return response()->json($endereco, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $endereco = $this->endereco->with('pessoa')->find($id);
        if ($endereco === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 401);
        }
        return response()->json($endereco, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEnderecoRequest  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEnderecoRequest $request, $id)
    {
        //$endereco->update($request->all());
        $endereco = $this->endereco->find($id);

        if ($endereco === null) {
            return response()->json(['erro' => 'Impossivel realizar a atualização. O recurso solicitado não existe'], 200);
        }
        
        if ($request->method() === 'PATCH') {
            
            $regrasDinamicas = array();

            //Percorrendo todas as regras definas no Model
            foreach ($endereco->rules() as $input => $regra) {
                
                //Coleta apenas as regras aplicaveis aos parâmetros parcias da requisão
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas);

         } else {
            $request->validate($endereco->rules());
         }   
        

        $endereco->update($request->all());
        return response()->json($endereco, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Endereco  $endereco
     * @return \Illuminate\Http\Response
     */
    public function destroy(Endereco $endereco)
    {
        //
    }
}
