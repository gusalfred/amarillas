<?php

namespace App\Http\Controllers;

use DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
    public function index()
    {
        $categorias = DB::select("SELECT * FROM categorias_nivel1 ORDER BY RAND() LIMIT 6");

        $main = DB::select("SELECT * FROM categorias_nivel1 ORDER BY categoria");

        return view('home', compact('categorias', 'main') );
    }

    public function search()
    {
        $q = $_GET['q'];

        $categorias = DB::table('categorias_nivel2')->where('categoria', 'like', '%'.$q.'%')->paginate(10);

        //$empresas = DB::table('empresas')->where('nombre', 'like', '%'.$q.'%')->paginate(10);

        $empresas = DB::table('empresas_direcciones')
            ->join('empresas', 'empresas_direcciones.id_empresa', '=', 'empresas.id_empresa')
            ->where('empresas.nombre', 'like', '%'.$q.'%')->paginate(10);

        $descripcion = DB::table('empresas')->where('descripcion', 'like', '%'.$q.'%')->paginate(10);

        return view('search', ['categorias' => $categorias, 'empresas' => $empresas, 'descripcion' => $descripcion] );
    }

    public function categoria($slug)
    {
        $cat1 = DB::table('categorias_nivel1')->where('slug', $slug)->first();

        $cat2 = DB::table('categorias_nivel2')->where('id_categoria_nivel1', $cat1->id_categoria_nivel1)->get();

        return view('categoria', compact('cat1','cat2') );
    }

    public function subcategoria($slug)
    {
        $cat2 = DB::table('categorias_nivel2')->where('slug', $slug)->first();

        $cat1 = DB::table('categorias_nivel1')->where('id_categoria_nivel1', $cat2->id_categoria_nivel1)->first();

        $empresas = DB::table('empresas_direcciones')
            ->join('empresas', 'empresas_direcciones.id_empresa', '=', 'empresas.id_empresa')
            ->join('empresas_categorias', 'empresas_direcciones.id_empresa', '=', 'empresas_categorias.id_empresa')
            ->where('id_categoria_nivel2', $cat2->id_categoria_nivel2)->paginate(10);
        return view('subcategoria', compact('cat1', 'cat2', 'empresas'));
    }

    public function empresa($id, $slug)
    {
        $empresa = DB::table('empresas_direcciones')
            ->join('empresas', 'empresas_direcciones.id_empresa', '=', 'empresas.id_empresa')
            ->where('id_empresa_direccion', $id)->first();
        $id_empresa = $empresa->id_empresa;

        $direcciones = DB::table('empresas_direcciones')->where('id_empresa', $id_empresa)->get();

        $imagen = DB::table('empresas_media')->where([
            ['id_empresa', $id_empresa],
            ['nombre', 'principal']
            ]
        )->first();

        $imagenes = DB::table('empresas_media')->where([
                ['id_empresa', $id_empresa]
            ]
        )->get();


        $redes = DB::table('empresas_redes')
            ->join('redes_sociales', 'empresas_redes.id_red_social', '=', 'redes_sociales.id_red_social')
            ->where('id_empresa', $id_empresa)->get();

        $comentarios = DB::table('empresas_valoraciones')
            ->join('users', 'users.id', '=', 'empresas_valoraciones.id_usuario')
            ->where('id_empresa', $id_empresa)->get();

        return view('empresa', compact('empresa', 'direcciones', 'imagenes', 'imagen', 'redes', 'comentarios') );
    }

    public function registro_empresa()
    {
        $data = "";
        return view('registro_empresa', compact('data'));
    }

    public function comentar(Request $request)
    {
        $id_usuario = Auth::id();
        $id_empresa = $request->input('id_empresa');
        $id_empresa_direccion = $request->input('id_empresa_direccion');
        $comentario = $request->input('comentario');
        $valor = $request->input('valor');

        $datos = [
            'id_empresa' => $id_empresa,
            'id_usuario' => $id_usuario,
            'comentario' => $comentario,
            'valor' => $valor
        ];

        DB::table('empresas_valoraciones')->insert($datos);

        return redirect()->action(
            'SiteController@empresa', ['id' => $id_empresa_direccion, 'slug' => 'slug']
        );
    }

}
