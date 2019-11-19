<?php

namespace App\Http\Controllers;

use App\Item;
use App\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{

    /* Etapa 1 - Sugestão */
    public function index()
    {
        // select * from item where status="Sugestão"
        $this->authorize('logado');
        $itens = item::where('status',"Sugestão")->get();
        return view('itens/index',compact('itens'));
    }

    public function create()
    {
        $this->authorize('logado');
        return view('itens/create');
    }

    public function store(Request $request)
    {
        $this->authorize('logado');
        $request->validate([
            'titulo'  => 'required',
            /* 'autor'   => 'required', */
            /* 'editora' => 'required', */
        ]);

        /* pegar itens que estão chegando e salvar no banco de dados */
        $item = new Item;
        $item->titulo = $request->titulo;
        $item->autor = $request->autor;
        $item->editora = $request->editora;
        $item->ano = $request->ano;
        $item->sugerido_por_id = Auth::id();

        $item->status = "Sugestão";
        $item->save();

        $request->session()->flash('alert-info', 'Sugestão enviada com sucesso');

        return redirect('/');
    }


    /* Etapa 2 - Processar Sugestão */
    public function processar_sugestao(Item $item)
    {
        $this->authorize('stl');
        return view('itens/processar_sugestao',compact('item'));
    }

    public function store_processar_sugestao(Request $request, Item $item)
    {
        $this->authorize('stl');
        if($request->status == 'Negado') {
            $request->validate([
                'motivo'  => 'required',
            ]);
            $item->motivo = $request->motivo;
        }
        /* Alterar status */
        $item->status = $request->status;
        $item->save();

        $request->session()->flash('alert-info',"Sugestão processada, novo status: {$item->status}");

        return redirect('/itens');
    }

    /* Etapa 3 - Processar aquisição */
    public function processar_aquisicao(item $acquisition)
    {
        $this->authorize('sai');
        $areas = Area::all();
        return view('itens/processar_aquisicao',compact('acquisition','areas'));
    }

    public function store_processar_aquisicao(Request $request, item $acquisition)
    {
        /* Validação de campos */
        $this->authorize('sai');
        $request->validate([
            'titulo'           => 'required',
            'autor'            => 'required',
            'editora'          => 'required',
            'tombo'            => 'required',
            /* 'tombo_antigo'     => 'required', */
            'cod_impressao'    => 'required',
            'ordem_relatorio'  => 'required',
            'tipo_aquisicao'   => 'required',
            'tipo_material'    => 'required',
            'subcategoria'     => 'required',
            'capes'            => 'required',
            /* 'id_material'      => 'required', */
            /* 'id_sugestao'      => 'required', */
            /* 'UsuarioS'         => 'required', */
            /* 'UsuarioA'         => 'required', */
            /* 'link'             => 'required', */
            'edicao'           => 'required',
            'volume'           => 'required',
            /* 'parte'            => 'required', */
            /* 'fasciculo'        => 'required', */
            'local'            => 'required',
            'ano'              => 'required',
            /* 'colecao'          => 'required', */
            'isbn'             => 'required',
            'escala'           => 'required',
            'dpto'             => 'required',
            'pedido_por'       => 'required',
            'finalidade'       => 'required',
            'data_pedido'      => 'required',
            'prioridade'       => 'required',
            'status'           => 'required',
            'moeda'            => 'required',
            'preco'            => 'required',
            'procedencia'      => 'required',
            'observacao'       => 'required',
            'verba'            => 'required',
            'processo'         => 'required',
            'fornecedor'       => 'required',
            'nota_fiscal'      => 'required',
            /* 'pasta'            => 'required', */
            /* 'moeda_nf'         => 'required', */
            /* 'preco_nf'         => 'required', */
            'data_nf'          => 'required',
        ]);

        /* Alterar status */
        $acquisition->status = "Em tombamento";

        /*Salvar*/
        $acquisition->motivo = $request->motivo;
        $acquisition->tombo = $request->tombo;
        $acquisition->tombo_antigo = $request->tombo_antigo;
        $acquisition->cod_impressao = $request->cod_impressao;
        $acquisition->ordem_relatorio = $request->ordem_relatorio;
        $acquisition->tipo_aquisicao = $request->tipo_aquisicao;
        $acquisition->tipo_material = $request->tipo_material;
        $acquisition->subcategoria = $request->subcategoria;
        $acquisition->capes = $request->capes;
        /* $acquisition->id_material = $request->id_material; */
        /* $acquisition->id_sugestao = $request->id_sugestao; */
        $acquisition->UsuarioS = $request->UsuarioS;
        $acquisition->UsuarioA = $request->UsuarioA;
        $acquisition->titulo = $request->titulo;
        $acquisition->autor = $request->autor;
        $acquisition->link = $request->link;
        $acquisition->edicao = $request->edicao;
        $acquisition->volume = $request->volume;
        $acquisition->parte = $request->parte;
        $acquisition->fasciculo = $request->fasciculo;
        $acquisition->local = $request->local;
        $acquisition->editora = $request->editora;
        $acquisition->ano = $request->ano;
        $acquisition->colecao = $request->colecao;
        $acquisition->isbn = $request->isbn;
        $acquisition->escala = $request->escala;
        $acquisition->dpto = $request->dpto;
        $acquisition->pedido_por = $request->pedido_por;
        $acquisition->finalidade = $request->finalidade;

        $acquisition->data_pedido = $request->$data_pedido;        
        $acquisition->prioridade = $request->prioridade;
        $acquisition->status = $request->status;
        $acquisition->moeda = $request->moeda;
        $acquisition->preco = $request->preco;
        $acquisition->procedencia = $request->procedencia;
        $acquisition->observacao = $request->observacao;
        $acquisition->verba = $request->verba;
        $acquisition->processo = $request->processo;
        $acquisition->fornecedor = $request->fornecedor;
        $acquisition->nota_fiscal = $request->nota_fiscal;
        /* $acquisition->pasta = $request->pasta; */
        /* $acquisition->moeda_nf = $request->moeda_nf; */
        /* $acquisition->preco_nf = $request->preco_nf; */

        $acquisition->data_nf = $request->$data_nf;
        
        $acquisition->save();

        $request->session()->flash('alert-info',"Aquisição processada, novo status: {$acquisition->status}");
        return redirect('/');
    }


    //Processar Aquisição
    public function lista_aquisicao()
    {
        $this->authorize('sai');
        $itens = item::where('status',"Em processo de aquisição")->get();
        return view('itens/lista_aquisicao',compact('itens'));
    }

    public function consulta()
    {
        $itens = item::all();
        return view('itens/consulta',compact('itens'));
    }
}