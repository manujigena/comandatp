<?php
require_once 'empleado.php';
require_once 'IComun.php';
require_once 'authJWT.php';

class bussinessEmpleado extends empleado implements IComun
{
    public function CrearUno($request, $response, $args)
    {
        $operaciones=0;
        $estado=10;
        $datosRecibidos = $request->getParsedBody();

        // //var_dump($datosRecibidos);
        // foreach ($datosRecibidos['nombre'] as $key) {
        //     $nombre1=$key;     var_dump ($nombre1);
        //     //$producto=array("")       
        //     //echo $val."="."$key";
        // }
        
        // // echo $nombre2;
        // // echo $nombre3;
        // die();
        
        $empleado = new empleado();
        $empleado->legajo = $datosRecibidos['legajo'];
        $empleado->nombre = $datosRecibidos['nombre'];
        $empleado->apellido = $datosRecibidos['apellido'];
        $empleado->password = $datosRecibidos['password'];
        $empleado->tipo = $datosRecibidos['tipo'];

        $empleado->operaciones = $operaciones;
        $empleado->estado = $estado;
        /* var_dump($empleado);
        die(); */

        $idEmpleado = $empleado->InsertEmpleado();
        return $response->write(json_encode($idEmpleado));        
    }


    public function TraerTodos($request, $response, $args)
    {
        /* $listado = empleado::SelectLosEmpleados();
        $response->write(json_encode($listado));			
    
        return $response; */
        $listado=empleado::SelectLosEmpleados();
        $newResponse = $response->withJson($listado, 200);  
       return $newResponse;
    }

    public function TraerUno($request, $response, $args) 
    {
        $legajo=$args['legajo'];
        $unEmpleado = empleado::SelectUnEmpleado($legajo);
        $newResponse = $response->withJson($unEmpleado, 200);  
        return $newResponse;
    }


    public static function Login($request,$response)
    {
        $datosRecibidos = $request->getParsedBody();
        $legajo = $datosRecibidos['legajo'];
        $pass = $datosRecibidos['pass'];
      
        $empleadoLogeado = empleado::Login($legajo,$pass);
        if($empleadoLogeado!=null)
        {
            
            $datos=new stdClass;
            $datos->legajo=$empleadoLogeado->legajo;
            $datos->nombre=$empleadoLogeado->nombre; 
            $datos->apellido=$empleadoLogeado->apellido; 
            $datos->idTipo=$empleadoLogeado->idTipo; 
            $datos->idEstado=$empleadoLogeado->idEstado;
            /* var_dump($datos);
            die(); */
            $token=authJWT::CrearToken($datos);
        }


        $newResponse = $response->withJson($token, 200);  
        return $newResponse;
    }

}