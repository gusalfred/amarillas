<div id="footer-box">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <img src="{{asset('images/logo.png')}}" style="width: 120px;"/><br>
                <b>Amarillas365.com, S.A.</b><br>
                Direccion av entre calles.<br>
                Telefono: 765.43.21
            </div>
            <div class="col-sm-4">
                <ul>
                    <li><a href="{{ URL::to('nosotros/') }}">Quienes somos</a></li>
                    <li><a href="{{ URL::to('registro_empresa/') }}">Registre su empresa gratis</a></li>
                    <li><a href="{{ URL::to('terminos/') }}">Terminos y condiciones</a></li>
                </ul>
            </div>
            <div class="col-sm-4">
                <ul>
                    <li><a href="{{ URL::to('anuncie/') }}">Anuncie con Nosotros</a></li>
                    <li><a href="{{ URL::to('contacto/') }}">Contáctenos</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>


<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-6 align-left">
                <span class="copyright">Copyright 2016 &copy; Amarillas 365, Todos los derechos reservados.</span>
            </div>
            <div class="col-md-5">
                <ul class="list-inline align-center">
                    <li><a href="{{ url('/politicas_de_privacidad') }}">Politicas de privacidad</a></li>
                    <li><a href="{{ url('/terminos') }}">Terminos y condiciones</a></li>
                </ul>
            </div>
            <div class="col-md-1">
                <ul class="list-inline align-center">
                    <li><a href="#"><i class="fa fa-2x fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fa fa-2x fa-facebook"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>