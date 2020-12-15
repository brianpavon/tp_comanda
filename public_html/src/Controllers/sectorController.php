<?php

namespace App\Controllers;

use App\Modelos\Menu;
//use Psr\Http\Message\ResponseInterface as Response;
//use Psr\Http\Message\ServerRequestInterface as Request;
//use App\Modelos\Pedido;
//use App\Modelos\Mesa;
//use App\Modelos\Mesas_Pedidos;
use App\Modelos\Usuario;
use App\Modelos\Sector;

class SectorController
{


    public static function obtenerSector($pedido)
    {
        $elementos = $elementos = explode(',',$pedido->menu_pedido);
        foreach ($elementos as $itemMenu) 
        {
            //var_dump($itemMenu);
            $itemSector = Menu::where('descripcion',$itemMenu)->first();
            //var_dump(json_encode($itemSector));
            $sector = $itemSector->categoria;
            //var_dump(json_encode($sector));
            //return $sector;
            $id_preparador = self::asignarEmpleadoSector($sector);
            
            $sectorPreparacion = new Sector;
            $sectorPreparacion->id_pedido = $pedido->id_pedido;
            $sectorPreparacion->estado = $pedido->estado;
            $sectorPreparacion->item_sector = $sector;
            $sectorPreparacion->id_encargado = $id_preparador;
            $sectorPreparacion->save();
           
            
        }
    }

    private static function asignarEmpleadoSector($sector)
    {
        $id_empleado = " ";
        //$listaEmpleados = Usuario::get();
        //var_dump(json_encode($listaEmpleados));
        switch ($sector) 
        {
            case 'cocina':
                //var_dump(json_encode($listaEmpleados));
                $empleado = Usuario::where('tipo_usuario','cocinero')->select('usuarios.id_usuario')->first();
                $id_empleado = $empleado->id_usuario;
                //var_dump(json_encode($id_empleado));
                break;
            case 'postres':
                $empleado = Usuario::where('tipo_usuario','cocinero')->select('usuarios.id_usuario')->first();
                $id_empleado = $empleado->id_usuario;
                break;
            case 'tragos':
                $empleado = Usuario::where('tipo_usuario','bartender')->select('usuarios.id_usuario')->first();
                $id_empleado = $empleado->id_usuario;
                break;
            case 'cerveza':
                $empleado = Usuario::where('tipo_usuario','cervecero')->select('usuarios.id_usuario')->first();
                $id_empleado = $empleado->id_usuario;
                
                //return $id_empleado;
                break;
            
            default:
                
                break;
        }
        return $id_empleado;
    }
}