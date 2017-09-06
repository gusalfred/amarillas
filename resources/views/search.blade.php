@extends('layouts.interna')

@section('content')

        <div class="container" style="margin-top: 50px;">

            <div class="row" style="margin-top: 10px;">
                <div class="col-md-8">
                    <h3>Resultados de la busqueda</h3>

                    <div class="titulo-resulato">Por Categorias</div>
                    @foreach ($categorias as $row)
                        <div style="margin-bottom: 15px;" class="cat-list">
                            <a href="{{ url('/subcategoria/'.$row->slug) }}">{{ $row->categoria }}</a>
                        </div>
                    @endforeach

                    <div class="titulo-resulato">Por nombre de Empresas</div>
                    @foreach ($empresas as $row)
                        <div style="margin-bottom: 15px;" class="cat-list">
                            <a href="{{ URL::to('empresa/'.$row->id_empresa_direccion.'/'.$row->nombre ) }}">{{ $row->nombre }}</a>
                        </div>
                    @endforeach
                    @if ( count($empresas) > 9)
                    <div> <a href=""><i class="fa fa-plus"></i> Ver Mas Resultados por Nombre </a></div>
                    @endif

                    <div class="titulo-resulato">En Descripcion </div>
                    @foreach ($descripcion as $row)
                        <div style="margin-bottom: 15px;" class="cat-list">
                            <a href="{{ url('/search/emp') }}">{{ $row->nombre }}</a>
                        </div>
                    @endforeach

                </div>

                <div class="col-md-4">
                    <div style="font-size: 18px; margin-bottom: 15px;">Relacionados</div>

                </div>
            </div>
        </div>

@endsection
