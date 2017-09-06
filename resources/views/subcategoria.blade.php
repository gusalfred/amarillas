@extends('layouts.interna')

@section('content')

        <div class="container" style="margin-top: 50px;">

            <div class="row" style="margin-top: 20px;">
                <div class="col-md-8">
                    <ol class="breadcrumb">
                        <li><a href="{{ url('/')  }}">amarillas365.com</a></li>
                        <li><a href="{{ url('/categoria/'.$cat1->slug)  }}">{{ substr($cat1->categoria, 0, 30) }} ...</a></li>
                        <li class="active">{{ substr($cat2->categoria, 0, 30) }}...</li>
                        <li>Lima</li>
                        <li class="active">Miraflores</li>
                    </ol>
                </div>
            </div>

            <div class="row" style="margin-top: 10px;">
                <div class="col-md-8">

                    @foreach ($empresas as $empresa)
                        <?php $url = 'empresa/'.$empresa->id_empresa_direccion.'/slug'.$empresa->slug ?>
                        <div style="margin-bottom: 15px;">
                            @if( 1 == 2 )
                            <a href="{{ URL::to($url) }}">
                            <img src="{{ url('uploads/destacado1.jpg') }}" style="display: block;" class="img-responsive" alt="">
                            </a>
                            @endif
                            <div class="box">
                                <div class="row row-sep" style="margin-bottom: 5px;">
                                    <div class="col-xs-9 col-md-10">
                                        <span class="box-title"><a href="{{ URL::to($url) }}">{{ $empresa->nombre }}</a></span>
                                    </div>
                                    <div class="col-xs-3 col-md-2" style="text-align: right;">
                                        <div class="rateit" data-rateit-value="{{ $empresa->estrellas }}" data-rateit-ispreset="true" data-rateit-readonly="true"></div>
                                    </div>
                                </div>
                                <div class="row row-sep">
                                    <div class="col-md-12"><span class="box-text"><i class="fa fa-map-marker"></i> {{ $empresa->direccion }}</span></div>
                                    <div class="col-md-12"><span class="box-text"><i class="fa fa-phone"></i> {{ $empresa->telefonos }}</span></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-9 col-md-10"><span class="box-comments">
                                      <i class="fa fa-comment-o" style="font-size: 14px;" aria-hidden="true"></i> {{ $empresa->comentarios }} comentarios</span>
                                    </div>
                                    <div class="col-xs-3 col-md-2" style="text-align: right;"><share-button></share-button></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{ $empresas->appends(['q' => 'a'])->links() }}
                    <span style="margin-left: 10px;">Resultados {{ $empresas->firstItem() }}-{{ $empresas->lastItem() }} de  {{ $empresas->total() }}</span>
                </div>
                <div class="col-md-4">
                    <div style="font-size: 18px; margin-bottom: 15px;">Relacionados</div>

                    @foreach ($empresas as $empresa)
                        <div style="margin-bottom: 30px;">
                            <div class="box">
                                <div class="row row-sep">
                                    <div class="col-xs-12"><span class="box-title">{{ $empresa->nombre }}</span></div>
                                </div>
                                <div class="row row-sep">
                                    <div class="col-md-12"><span class="box-text">{{ $empresa->nombre }}</span></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="rateit" data-rateit-value="2.5" data-rateit-ispreset="true" data-rateit-readonly="true"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

@endsection
