<?php
require_once 'pedProd.php';
require_once 'IComun.php';
require_once 'authJWT.php';

class bussinessProducto extends producto implements IComun
{    


    public function CrearUno($request, $response, $args)
    {
        // $datosRecibidos = $request->getParsedBody();
        
        // $producto = new producto();
        // $producto->descripcion = $datosRecibidos['descripcion'];
        // $producto->monto = $datosRecibidos['monto'];
        // $producto->idTipo = $datosRecibidos['tipo'];
        
        // /* var_dump($empleado);
        // die(); */

        // $idProducto = $producto->InsertProducto();
        // return $response->write(json_encode($idProducto));        
    }


    public function TraerTodos($request, $response, $args)
    {
        /* $listado = empleado::SelectLosEmpleados();
        $response->write(json_encode($listado));			
    
        return $response; */
        $listado=producto::SelectLosProductos();
        $newResponse = $response->withJson($listado, 200);  
       return $newResponse;
    }

    public function TraerUno($request, $response, $args) 
    {
        $idProducto=$args['idProducto'];
        $unProducto = producto::SelectUnProducto($idProducto);
        $newResponse = $response->withJson($unProducto, 200);  
        return $newResponse;
    }

}