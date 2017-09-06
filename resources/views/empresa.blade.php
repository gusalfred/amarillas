@extends('layouts.interna')

@section('title') {{ $empresa->nombre }} @endsection

@section('content')

        <div style="background-color: #EDEDED; margin-top: 50px;">
        <div class="container">
            <div class="row" >
                <div class="col-md-8" style="margin-bottom: 20px;">
                    <h3 style="margin-bottom: 0;">{{ $empresa->nombre }}</h3>
                    <div>{{ $empresa->direccion }}</div>
                    <div class="rateit" data-rateit-value="{{ $empresa->estrellas }}" data-rateit-ispreset="true" data-rateit-readonly="true"></div>
                </div>
                <div class="col-md-4">

                </div>
            </div>
        </div>
        </div>

        <div class="container">
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-8">


                    @if($imagen )
                    <img src="{{ asset('uploads/media/'.$imagen->archivo) }}" style="display: block;" class="img-responsive" alt="">
                    @endif

                    <div style="padding-top: 15px;">{{ $empresa->descripcion }}</div>

                    <div class="row">
                        <div class="col-md-12">
                            <div style="background-color: #EDEDED; padding: 15px 10px; margin-top: 15px;">
                    <div class="row">
                        <div class="col-md-3" style="padding-right: 0px;">
                            <i class="fa fa-2x fa-phone"></i> {{ $empresa->telefonos or "s/n"}}
                        </div>
                        <div class="col-md-3" style="padding-right: 0px;">
                            <a href="mailto:{{ $empresa->email }}"><i class="fa fa-2x fa-envelope"></i> Contactar</a>
                        </div>
                        <div class="col-md-3" style="padding-right: 0px;">
                            <a target="_blank" href="{{ $empresa->web }}"><i class="fa fa-2x fa-globe "></i> Website </a>
                        </div>
                        <div class="col-md-3" >
                            <a><i class="fa fa-2x fa-plus"></i> Info</a>
                        </div>
                        <div class="col-md-12" style="margin-top: 15px;" >
                            <i class="fa fa-2x fa-map-marker"></i> {{ $empresa->direccion }}
                        </div>
                    </div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <h4>Más fotos</h4>
                        <div class="slider" style="margin: 10px 20px 40px 20px;">
                            @foreach ($imagenes as $img)
                            <div class="slider-item">
                                <img src="{{asset('uploads/media/'.$img->archivo )}}" style="height: 200px;">
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <h4><i class="fa fa-comment-o"></i> {{ $empresa->comentarios  }} Comentarios</h4>

                        @if ( Auth::guest() )
                            Debes <a href="{{ url('/login') }}"> iniciar sesión</a> para poder comentar
                        @else
                        <form class="form-inline" method="post" action="{{ url('/comentar') }}" >
                            {{ csrf_field() }}
                            <input type="hidden" name="id_empresa" value="{{ $empresa->id_empresa }}">
                            <input type="hidden" name="id_empresa_direccion" value="{{ $empresa->id_empresa_direccion }}">
                            <div class="form-group">
                                <textarea cols="50" rows="2" class="form-control" name="comentario" placeholder="Comentario"></textarea>
                            </div>
                            <select id="valor" name="valor">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            <div class="rateit" data-rateit-backingfld="#valor" data-rateit-min="0"></div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                        @endif

                        @foreach ($comentarios as $comentario)
                            <div class="media">
                                <div class="media-left">
                                        <img class="media-object img-circle" src="{{ url('uploads/foto.png') }}" width="60">
                                </div>
                                <div class="media-body">
                                    <h5 class="media-heading">{{ $comentario->name }}</h5>
                                    <div>{{ $comentario->comentario }}</div>
                                    <div class="rateit" data-rateit-value="{{ $comentario->valor }}" data-rateit-ispreset="true" data-rateit-readonly="true"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
                <div class="col-md-4">

                    <div id="map" style="height: 270px;"></div>
                    <script type="text/javascript">

                        var map;
                        function initMap() {
                            var myLatLng = {lat: {{ $empresa->latitud }}, lng: {{ $empresa->longitud }} };

                            var map = new google.maps.Map(document.getElementById('map'), {
                                zoom: 12,
                                center: myLatLng
                            });

                            var marker = new google.maps.Marker({
                                position: myLatLng,
                                map: map,
                                title: '{{ $empresa->nombre }}'
                            });
                        }

                    </script>
                    <script async defer
                            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBOBAJodR1EfwjOhSCUwdyshas1nvuAwuI&callback=initMap">
                    </script>
                    <div class="map-box"><a href="#" data-toggle="modal" data-target="#direc">Ver todas las sucursales</a></div>

                    <!-- Modal -->
                    <div class="modal fade" id="direc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Sucursales</h4>
                                </div>
                                <div class="modal-body">
                                    @foreach($direcciones as $direccion)
                                        {{ $direccion->direccion }}<br>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="font-size: 14px; line-height: 30px; margin: 15px 0;">
                        <span style="font-size: 25px;margin-right: 10px;" class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir direcciones
                    </div>

                    <div>
                        <h4>Redes sociales</h4>
                        @foreach($redes as $red)
                            <a href="{{ $red->url }}" target="_blank"><i class="{{ $red->icon_class }} fa-4x" style="color: {{ $red->color }};"></i></a>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>

        <aside id="sticky-social" class="hidden-xs">
            <ul>
                <li><a href="#" class="entypo-facebook" target="_blank"><i class="fa fa-facebook-square fa-2x"></i> <span>Facebook</span></a></li>
                <li><a href="#" class="entypo-twitter" target="_blank"><i class="fa fa-twitter-square fa-2x"></i> <span>Twitter</span></a></li>
                <li><a href="#" class="entypo-instagrem" target="_blank"><i class="fa fa-instagram fa-2x"></i> <span>Instagram</span></a></li>
            </ul>
        </aside>

@endsection
