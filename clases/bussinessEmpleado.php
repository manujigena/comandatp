<?php
require_once 'empleado.php';
require_once 'IComun.php';
require_once 'MWAuthJWT.php';

class bussinessEmpleado extends empleado implements IComun
{
    public function CrearUno($request, $response, $args)
    {
        $tipoEmp=MWAuthJWT::ValidarEmpleado($request);
        
        if($tipoEmp !=1)
        {
            $responseFalla= array('estado' => "No Autorizado");
            return $response->withJson($responseFalla,401);
        }else
        {
            $operaciones=0;
            $estado=10;
            $datosRecibidos = $request->getParsedBody();        
            $empleado = new empleado();        
            $empleado->nombre = $datosRecibidos['nombre'];
            $empleado->apellido = $datosRecibidos['apellido'];
            $empleado->password = $datosRecibidos['password'];
            $empleado->tipo = $datosRecibidos['tipo'];

            $empleado->operaciones = $operaciones;
            $empleado->estado = $estado;

            $idEmpleado = $empleado->InsertEmpleado();
            return $response->write(json_encode($idEmpleado));
        }
    }


    public function TraerTodos($request, $response, $args)
    {
        $tipoEmp=MWAuthJWT::ValidarEmpleado($request);
        if($tipoEmp !=1)
        {
            $responseFalla= array('estado' => "No Autorizado");
            return $response->withJson($responseFalla,401);
        }else
        {
            $listado=empleado::SelectLosEmpleados();
            $newResponse = $response->withJson($listado, 200);  
            return $newResponse;
        }
    }


    public function TraerUno($request, $response, $args) 
    {
        $tipoEmp=MWAuthJWT::ValidarEmpleado($request);
        if($tipoEmp !=1)
        {
            $responseFalla= array('estado' => "No Autorizado");
            return $response->withJson($responseFalla,401);
        }else
        {
            $idEmpleado=$args['idEmpleado'];
            $unEmpleado = empleado::SelectUnEmpleado($idEmpleado);
            $newResponse = $response->withJson($unEmpleado, 200);  
            return $newResponse;
        }
    }


    public function BorrarUno($request, $response, $args) 
    {
        $tipoEmp=self::ValidarEmpleado($request);
        if($tipoEmp !=1)
        {
            $responseFalla= array('estado' => "No Autorizado");
            return $response->withJson($responseFalla,401);
        }else
        {
            $idEmpleado=$args['idEmpleado'];        
            $empleado = new empleado();
            $empleado->idEmpleado=$idEmpleado;
            $cantidadDeBorrados=$empleado->DeleteEmpleado();

            $resultado= new stdclass();
            $resultado->cantidad=$cantidadDeBorrados;
            if($cantidadDeBorrados>0)
            {
                $resultado->mensaje="Baja Ok";
            }
            else
            {
                $resultado->mensaje="Error en la Baja";
            }
            $empBorrado=empleado::SelectUnEmpleado($idEmpleado);
            $resultado->emp=$empBorrado;
            $newResponse = $response->withJson($resultado, 200);  
            return $newResponse;
        }
    }


    public function ModificarUno($request, $response, $args)
    {
        $tipoEmp=self::ValidarEmpleado($request);
        if($tipoEmp !=1)
        {
            $responseFalla= array('estado' => "No Autorizado");
            return $response->withJson($responseFalla,401);
        }else
        {
            $idEmpleado=$args['idEmpleado'];
            $datosRecibidos = $request->getQueryParams();
            $unEmpleado = empleado::SelectUnEmpleado($idEmpleado);        
            
            $unEmpleado->nombre= isset($datosRecibidos['nombre']) ?$datosRecibidos['nombre']:$unEmpleado->nombre;
            $unEmpleado->apellido= isset($datosRecibidos['apellido']) ?$datosRecibidos['apellido']:$unEmpleado->apellido;
            $unEmpleado->idTipo= isset($datosRecibidos['tipo']) ? $datosRecibidos['tipo']:$unEmpleado->idTipo;
            $unEmpleado->idEstado= isset($datosRecibidos['idEstado']) ? $datosRecibidos['idEstado']:$unEmpleado->idEstado;        

            $cantidadModificados=$unEmpleado->PutEmpleado();        

            $resultado= new stdclass();
            $resultado->cantidad=$cantidadModificados;
            if($cantidadModificados>0)
            {
                $resultado->mensaje="Modificado Ok";
            }
            else
            {
                $resultado->mensaje="Error en la Modificacion";
            }
            $empModif=empleado::SelectUnEmpleado($idEmpleado);
            $resultado->emp=$empModif;
            $newResponse = $response->withJson($resultado, 200);  

            return $newResponse;
        }
        
    }


    public static function Login($request,$response)
    {
        $datosRecibidos = $request->getParsedBody();
        $idEmpleado = $datosRecibidos['idEmpleado'];
        $pass = $datosRecibidos['password'];
      
        $empleadoLogeado = empleado::Login($idEmpleado,$pass);        
        if($empleadoLogeado!=null)
        {
            
            $datos=new stdClass;
            $datos->idEmpleado=$empleadoLogeado->idEmpleado;
            $datos->nombre=$empleadoLogeado->nombre; 
            $datos->apellido=$empleadoLogeado->apellido; 
            $datos->idTipo=$empleadoLogeado->idTipo; 
            $datos->idEstado=$empleadoLogeado->idEstado;
            
            $token=authJWT::CrearToken($datos);
            $newResponse= array('Mensaje' => $empleadoLogeado->apellido, 'idEmpleado' => $empleadoLogeado->idEmpleado,'token'=>$token);
            return $response->withJson($newResponse,200);
        }else{
            $newResponse= array('Mensaje' => "Empleado no Existe");
            return $response->withJson($newResponse,404);
        }
        
    }

}