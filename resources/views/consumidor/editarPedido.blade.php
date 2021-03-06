@extends('layouts.app')

@section('titulo','Editar Pedido')

@section('navbar')
    <a href="{{ route("home") }}">Início</a> >
    <a href="{{ route("consumidor.meusPedidos") }}">Meus Pedidos</a> >
    Editar
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">            
            <div class="panel panel-default">
              <div class="panel-heading">Editar Pedido</div>
              <form class="form-horizontal" method="POST" action="{{ route('consumidor.pedido.atualizar') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                  <div class="panel-body">
                    @if (\Session::has('fail'))
                      <br>
                      <div class="alert alert-danger">
                          <strong>Erro!</strong>
                          {!! \Session::get('fail') !!}
                      </div>
                    @endif
                    <input id="evento_id" type="hidden" class="form-control" name="evento_id" value="{{ $evento->id }}" >
                    <input id="pedido_id" type="hidden" class="form-control" name="pedido_id" value="{{ $pedido->id }}" >

                    <div id="tabela" class="table-responsive">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>Comprar?</th>
                            <th>Produto</th>
                            <th>Descrição</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Unidade</th>
                            <th>Produtor</th>
                          </tr>
                        </thead>
                        <tbody>
                          @php $i=0; @endphp
                          @php
                            $pedidos_ids = [];
                            foreach($itensPedido as $item){
                              array_push($pedidos_ids, $item->produto_id);
                            }
                            
                          @endphp
                          @foreach($produtos as $produto)                              
                              <?php
                                //checando se produto atual está entre os itens do pedido   
                                //se produto->id estiver entre as ids de itensPedidos->produto_id
                                if( ($key_item = array_search($produto->id, $pedidos_ids)) !== false ){                                  
                                  $foiPedido = true;
                                  $itemPedido = $itensPedido[$key_item];
                                }else{                                  
                                  $foiPedido = false;
                                  $itemPedido = null; 
                                }
                                
                              ?>

                              @if($foiPedido)
                                <input id="item_id" type="hidden" class="form-control" name="item_id[{{$i}}]" value="{{ $itemPedido->id }}" >
                              @endif

                              <tr>
                                <td data-title="Comprar?">
                                  <input type="checkbox" {{$foiPedido ? "checked": ""}}  onchange="Enable(this, '{{$produto->ativo}}')" name="checkbox[{{$produto->id}}]" value="old()" id="checkbox_{{$produto->id}}">
                                </td>
                                <td data-title="Produto">{{ $produto->nome }}</td>
                                <td data-title="Descrição">{{ $produto->descricao }}</td>
                                <td data-title="Preço">{{ 'R$ '. number_format($produto->preco,2) }}</td>
                                @if(($produto->unidadeVenda->is_fracionado) == 1)
                                  @if(old('nome',NULL) != NULL)
                                    <td data-title="Quantidade"><input {{$produto->ativo==false||!$foiPedido?'disabled':''}} id="quantidade[{{$produto->id}}]" type="number" min="0" step="0.1" class="form-control" style="width: 6em" name="quantidade[{{$produto->id}}]" value="{{ old('quantidade') }}" autofocus></td>
                                  @else
                                    <td data-title="Quantidade"><input {{$produto->ativo==false||!$foiPedido?'disabled':''}} id="quantidade[{{$produto->id}}]" type="number" min="0" step="0.1" class="form-control" style="width: 6em" name="quantidade[{{$produto->id}}]" value="{{ $itemPedido!=null?$itemPedido->quantidade:'' }}" autofocus></td>
                                  @endif
                                @else
                                  @if(old('nome',NULL) != NULL)
                                    <td data-title="Quantidade"><input {{$produto->ativo==false||!$foiPedido?'disabled':''}} id="quantidade[{{$produto->id}}]" type="number" min="0" step="1" class="form-control" style="width: 6em" name="quantidade[{{$produto->id}}]" value="{{ old('quantidade') }}" autofocus></td>
                                  @else
                                    <td data-title="Quantidade"><input {{$produto->ativo==false||!$foiPedido?'disabled':''}} id="quantidade[{{$produto->id}}]" type="number" min="0" step="1" class="form-control" style="width: 6em" name="quantidade[{{$produto->id}}]" value="{{ $itemPedido!=null?$itemPedido->quantidade:'' }}" autofocus></td>
                                  @endif
                                @endif
                                @if($produto->ativo == false)
                                  <input type="hidden" name="quantidade[{{$produto->id}}]" value="{{ $itemPedido!=null?$itemPedido->quantidade:'' }}"/>
                                @endif
                                <td data-title="Unidade">{{ $produto->unidadeVenda->nome }}</td>
                                @php($produtor = \projetoGCA\Produtor::find($produto->produtor_id))
                                <td data-title="Produtor">{{$produtor->nome}}</td>
                              </tr>
                            @php($i++)
                          @endforeach

                        </tbody>
                      </table>
                    </div>
                  </div>
                
                  <div class="panel-footer">
                      <div class="form-group">
                        <a class="btn btn-danger" href="{{URL::previous()}}">Voltar</a>
                        <button type="submit" class="btn btn-primary">
                            Atualizar Pedido
                        </button>
                      </div>
                  </div>
              </form>
            </div>
          </div>
    </div>
</div>
@endsection

<script type="text/javascript">
Enable = function(checkbox, ativo)
    {
      var element_id = (checkbox.id).replace('checkbox_','');
      var input = document.getElementById(("quantidade[").concat(element_id,"]"));
     
      if(ativo){
        if(checkbox.checked == true){
            input.disabled = false;
        }else{
            input.disabled = true;
            input.value = "";
        }      
      }else{        
        input.disabled = true;        
      }
    }
</script>
