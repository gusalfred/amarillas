@extends('layouts.interna')

@section('title') {{ $cat1->categoria }} @endsection

@section('content')

        <div class="container" style="margin-top: 50px;">
            <div class="row" style="margin-top: 10px;">
                <div class="col-md-8">
                    <ol class="breadcrumb">
                        <li><a href="{{ url('/')  }}">amarillas365.com</a></li>
                        <li class="active">{{ $cat1->categoria }}</li>
                    </ol>

                    <h3>{{ $cat1->categoria }} </h3>
                    <div class="titulo-resulato">Categorías Específicas </div>
                    <div style="margin-bottom: 10px;">Total de Categorías Específicas: {{ count($cat2) }}</div>

                    @foreach ($cat2 as $row)
                        <div style="margin-bottom: 15px;" class="cat-list">
                            <a href="{{ url('/subcategoria/'.$row->slug) }}">{{ $row->categoria }}</a>
                        </div>
                    @endforeach
                </div>
                <div class="col-md-4">
                    <div style="font-size: 18px; margin-bottom: 15px;">Avisos</div>
                </div>
            </div>
        </div>

@endsection
