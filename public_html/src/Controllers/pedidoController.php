<?php

namespace App\Controllers;

use App\Modelos\Menu;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Modelos\Pedido;
use App\Modelos\Mesa;
use App\Modelos\Mesas_Pedidos;
use App\Controllers\UsuarioController;
use App\Controllers\SectorController;
use App\Modelos\Sector;


class PedidoController
{
    public function agregarPedido(Request $request, Response $response) 
    {
        
        $nuevaMesa = new Mesa;
        $nuevaMesa->id_mesa = self::generarId();
        $datos = $request->getParsedBody();
        $cargo = false;
        $respuesta = "No se pudo guardar";
        $array = [];
        $cadena = "";
        //$mostrarSector = "";
        foreach ($datos as $key => $value) 
        {
            for ($i=0; $i < count($value); $i++) 
            { 
                $nuevoPedido = new Pedido;                
                $nuevoPedido->menu_pedido = $value[$i]['pedido_cliente'];                
                $nuevoPedido->nombre_cliente = $value[$i]['nombre_cliente'];
                $nuevoPedido->estado = 'pendiente';
                $nuevoPedido->id_mesa = $nuevaMesa->id_mesa;
                $nuevoPedido->id_pedido = self::generarId();
                $cadena = $cadena.$nuevoPedido->id_pedido." ";
                if(self::verificarMenu($nuevoPedido->menu_pedido))
                {
                    //echo 'piola';
                    //$nuevoPedido->save();
                    array_push($array,$nuevoPedido);
                    
                }
                else
                {
                    
                    $array = [];
                    $respuesta = $respuesta." ,"."No contamos con algun item en nuestro menu";
                    //$cargo = false;
                    break;
                }
                
            }
            foreach ($array as $pedidos) 
            {
                
                $mesa_pedido = new Mesas_Pedidos;
                $mesa_pedido->id_mesa = $nuevaMesa->id_mesa;
                $mesa_pedido->id_pedido = $pedidos->id_pedido;
                $mesa_pedido->estado = $pedidos->estado;
                
                SectorController::obtenerSector($pedidos);
                //$mostrarSector = $mostrarSector.$sector." ";
                //var_dump($mostrarSector);
                //echo 'flashaste';
                $pedidos->save();
                $mesa_pedido->save();
                $cargo = true;
            }

            //var_dump($array);
            
        }
        if($cargo)
        { 
            $nuevaMesa->estado = "Clientes esperando el pedido";
            $nuevaMesa->encuesta = " ";
            $nuevaMesa->save();            
            $respuesta = "Pedidos guardados correctamente y mesa abierta".PHP_EOL."El codigo del pedido es:"." ".$cadena." "."El codigo de la mesa es:"." ".$nuevaMesa->id_mesa;
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }



    static function generarId()
    {
        $codigo = "0123456789ABCDEFGHIJKLMNOPQRSTUVXYZ";
        $idGenerado = substr(str_shuffle($codigo),0,5);
        return $idGenerado;
    }

    private static function verificarMenu($pedido)
    {
        //var_dump($pedido);
        
        $elementos = explode(',',$pedido);
        //var_dump($elementos);
        $enMenu = false;
        foreach ($elementos as $menuItem) 
        {
            //var_dump($menuItem);
            $itemMenu = Menu::where("descripcion",$menuItem)->first();
            
            //$itemMenu = Menu::where("descripcion",$pedido)->first();
            if($itemMenu == null)
            {
                //var_dump(json_encode($itemMenu));
                //$enMenu = true;
                return $enMenu = false;
                //break;
            }
            else
            {
                //var_dump(json_encode($itemMenu));
                $enMenu = true;
            }
            
        }
        return $enMenu;

    }

    public function prepararPedidos(Request $request, Response $response)
    {
        //$datos = $request->getParsedBody();
        $token = $request->getHeaderLine('token');
        $respuesta = "No se pudo preparar";
        $tipoUsuario = UsuarioController::obtenerTipoToken($token);
        $empleado_encargado = UsuarioController::ObtenerLegajoToken($token);
        //var_dump($empleado_encargado);
        switch ($tipoUsuario) 
        {
            case 'cocinero':
                
                if(self::pedidosListos($empleado_encargado))
                {
                    $respuesta = "El empleado está preparando el pedido";                    
                }                   
                
                else
                {
                    $respuesta = "No hay nada que preparar";
                }
                                
                break;
            case 'bartender':
                //$pedido_empleado = Sector::where('id_encargado',$empleado_encargado)->first();
                if(self::pedidosListos($empleado_encargado))
                {
                    $respuesta = "El empleado está preparando el pedido";
                }                   
                
                else
                {
                    $respuesta = "No hay nada que preparar";
                }
                break;
            case 'cervecero':
                //$pedido_empleado = Sector::where('id_encargado',$empleado_encargado)->first();
                if(self::pedidosListos($empleado_encargado))
                {
                    $respuesta = "El empleado está preparando el pedido";
                }                   
                
                else
                {
                    $respuesta = "No hay nada que preparar";
                }
                break;
            default:
                # code...
                break;
        }
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }

    //PASA TODOS LOS PEDIDOS DEL EMPLEADO A PREPARACION O LISTOS PARA SERVIR
    private static function pedidosListos($id_empleado)
    {
        $todosListos = false;
        //var_dump($id_empleado);
        //var_dump($id_pedido);
        $pedidosDelEmpleado = Sector::where('id_encargado',$id_empleado)->get();
        
        //var_dump(json_encode($pedidosDelEmpleado));
        foreach ($pedidosDelEmpleado as $pedidoDelEmpleado) 
        {
            switch ($pedidoDelEmpleado->estado) 
            {
                case 'pendiente':
                    $pedidoDelEmpleado->estado ='en preparacion';
                    //var_dump(json_encode($pedidoDelEmpleado));
                    $pedidoDelEmpleado->save();
                    $todosListos = true;                
                    break;
                case 'en preparacion':
                    $pedidoDelEmpleado->estado ='listo para servir';
                    //var_dump(json_encode($pedidoDelEmpleado));
                    $pedidoDelEmpleado->save();
                    $todosListos = true;                
                    break;
                
                default:
                    
                    break;
            }
            //var_dump(json_encode($pedidoDelEmpleado->id_pedido));
            
            
        }        
        return $todosListos;
    }

    //EL MOZO SIRVE EN LAS MESAS Y CAMBIA LOS ESTADOS SI TODOS LOS PEDIDOS YA ESTAN LISTOS PARA SERVIR
    public function servirMesas(Request $request,Response $response,$args)
    {
        $id_mesa = $args['idMesa'];
        $mesa = Mesa::where('id_mesa',$id_mesa)->first();
        $pedidosDeMesa = Mesas_Pedidos::where('id_mesa',$id_mesa)->select('id_pedido')->get();
        $listoParaServir = true;
        $array = [];
        //var_dump(json_encode($pedidosDeMesa));
        
        for ($i=0; $i < count($pedidosDeMesa) ; $i++) 
        { 
            $pedidosDeSectores = Sector::where('id_pedido',$pedidosDeMesa[$i]->id_pedido)->select('sectores.estado')->get();
            //var_dump(json_encode($pedidosDeSectores));
            if($pedidosDeSectores[$i]->estado != 'listo para servir')
            {
                //var_dump(json_encode($pedidosDeSectores[$i]));
                $listoParaServir = false;
                break;
            }
        } 
          
        if($listoParaServir)
        {
            for ($i=0; $i <count($pedidosDeMesa) ; $i++) 
            { 
                $pedidosDeLaMesa = Mesas_Pedidos::where('id_mesa',$id_mesa)->where('id_pedido',$pedidosDeMesa[$i]->id_pedido)->select('id')->first();
                $pedidosListos = Pedido::where('id_pedido',$pedidosDeMesa[$i]->id_pedido)->first();
                //var_dump(json_encode($pedidosListos));
                $pedidosListos->estado = 'listo para servir';
                $pedidosDeLaMesa->estado = 'listo para servir';
                //var_dump(json_encode($pedidosDeLaMesa));
                $pedidosDeLaMesa->save();
                $pedidosListos->save();
            }
                 
            $respuesta = "El mozo ya sirvió los pedidos";
            $mesa->estado = "con clientes comiendo";
            $mesa->save();
        }
        else
        {
            $respuesta = "Todavia falta preparar algun pedido";
        }
               
        
        $response->getBody()->write(json_encode($respuesta));
        return $response;        
        
    }

    public static function estadoMesa(Request $request,Response $response,$args)
    {
        $id_mesa = $args['idMesa'];

        $mesa = Mesa::where('id_mesa',$id_mesa)->first();

        if($mesa->estado == 'con clientes comiendo') 
        {
            
            $mesa->estado = "con clientes pagando";
            $mesa->save();
            $respuesta = "Los clientes estan pagando";           
            
        }
        else
        {
            $respuesta = "Verifique si la mesa esta abierta o si los clientes recibieron su pedido";
        }        
        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }

    public static function cerrarMesa(Request $request,Response $response)
    {
        $id_mesa = $request->getParsedBody()['idMesa'];                 

        $mesa = Mesa::where('id_mesa',$id_mesa)->first();
        if($mesa->estado == 'con clientes pagando')
        {
            $mesa->estado = 'cerrada';
            $mesa->encuesta = self::hacerEncuesta();
            $mesa->save();
            $respuesta = "Mesa cerrada correctamente, recuerde mirar la encuesta";
        }
        else if($mesa->estado == 'con clientes comiendo')
        {
            $respuesta = "Primero debe cobrar";
        }
        else
        {
            $respuesta = "La mesa ya fue cerrada, abra una nueva";
        } 

        $response->getBody()->write(json_encode($respuesta));
        return $response;
    }

    private static function hacerEncuesta()
    {
        $puntajeMesa = rand(1,10);
        $puntajeRest = rand(1,10);
        $puntajeMozo = rand(1,10);
        $puntajeCoc = rand(1,10);

        $encuesta = "Puntaje Mesa:"." ".$puntajeMesa." "."Puntaje Restaurant:"." ".$puntajeRest." "."Puntaje Mozo:"." ".$puntajeMozo." "."Puntaje Cocina:"." ".$puntajeCoc;

        return $encuesta;
    }

    public static function mostrarPedidosCliente(Request $request, Response $response)
    {
        $datos= $request->getQueryParams();
        $id_mesa =  $datos["idMesa"];
        $id_pedido = $datos["idPedido"]; 
        //var_dump($id_mesa);    

        $pedido=Mesas_Pedidos:: join('pedidos', 'pedidos.id_pedido', '=', 'mesas_pedidos.id_pedido')
                ->join('mesas', 'mesas.id_mesa', '=', 'mesas_pedidos.id_mesa')
                ->select('pedidos.nombre_cliente as Cliente','pedidos.menu_pedido as Menu solicitado','pedidos.estado as Estado Pedido')
                ->where('pedidos.id_pedido',$id_pedido)
                ->where('mesas.id_mesa',$id_mesa)
                ->get();
                 
        if(count($pedido)>0)
        {
            //var_dump(json_encode($pedido));  
            $response->getBody()->write(json_encode($pedido));
        }
        else
        {
            $response->getBody()->write(json_encode("No hay  pedidos que coincidan con los datos ingresados"));
        }
        return $response;
    }
}