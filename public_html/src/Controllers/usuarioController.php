<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Modelos\Usuario;
use \Firebase\JWT\JWT;



class UsuarioController
{
    public function addOne(Request $request, Response $response) 
    {
        $nuevoUsuario = new Usuario;

        $nuevoUsuario->nombre = strtolower($request->getParsedBody()['nombre']);        
        $nuevoUsuario->tipo_usuario = strtolower($request->getParsedBody()['tipo']);
        $nuevoUsuario->clave = strtolower($request->getParsedBody()['clave']);

        if($nuevoUsuario->save())
        {
            $respuesta = "Guardado en la base de datos correcto";
        }
        

        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }

    public function loginUsuario(Request $request,Response $response)
    {
        
        $nombre = strtolower($request->getParsedBody()['nombre']);
        $clave = strtolower($request->getParsedBody()['clave']);
        $loginValido = self::verificarUsuario($nombre,$clave);
        
        if($loginValido != false)
        {
            $response->getBody()->write(json_encode($loginValido));
        }
        else
        {
            $response->getBody()->write(PHP_EOL.'Clave o mail no registrados');
        }
        
        return $response;
    }


    public static function verificarUsuario($nombre,$clave)
    {
        
        $usuario = Usuario::where('nombre', $nombre)->where('clave',$clave)->first();
        $payload = array();
        //var_dump($usuario);
        $encodeCorrecto = false;
        if($usuario != null)
        { 
            $payload = array(
            "nombre"=> $nombre,
            "clave"=> $clave,
            "id"=>$usuario->id_usuario,
            "tipo"=>$usuario->tipo_usuario
            ); 
            $encodeCorrecto = JWT::encode($payload,'practica-comanda');
        }
        else
        {
            echo 'Primero debe cargar usuarios';
        }
        
        return $encodeCorrecto;
    }

    //VERIFICA PERMISOS
    public static function PermitirPermisos($token,$tipo)
    {
        $retorno = false;
        try {
            $payload = JWT::decode($token, "practica-comanda", array('HS256'));
            
            foreach ($payload as $value) {
                if ($value == $tipo) {

                    $retorno = true;
                }
            }
        } catch (\Throwable $th) {
            echo 'Excepcion:' . $th->getMessage();
        }
        return $retorno;
    }

    //OBTENER EL LEGAJO
    public static function ObtenerLegajoToken($token)
    {
        //$retorno = false;
        try {
            $payload = JWT::decode($token, "practica-comanda", array('HS256'));
            //var_dump($payload);
            foreach ($payload as $key => $value) 
            {
                if ($key == 'id') 
                {

                    return $value;
                }
            }
        } catch (\Throwable $th) {
            echo 'Excepcion:' . $th->getMessage();
        }
        //return $retorno;
    }


    //OBTENER TIPO
    public static function ObtenerTipoToken($token)
    {
        //$retorno = false;
        try {
            $payload = JWT::decode($token, "practica-comanda", array('HS256'));
            //var_dump($payload);
            foreach ($payload as $key => $value) 
            {
                if ($key == 'tipo') 
                {

                    return $value;
                }
            }
        } catch (\Throwable $th) {
            echo 'Excepcion:' . $th->getMessage();
        }
        //return $retorno;
    }
}