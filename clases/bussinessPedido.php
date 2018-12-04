<?php
require_once 'pedido.php';
require_once 'IComun.php';
require_once 'MWAuthJWT.php';
require_once 'pedProd.php';
require_once 'producto.php';
require_once 'horario.php';

class bussinessPedido extends pedido implements IComun
{
    

    public function CrearUno($request, $response, $args)
    {
        //SOLO MOZO Y SOCIO
        $tipoEmp=MWAuthJWT::ValidarEmpleado($request);
        
        if($tipoEmp ==1 || $tipoEmp ==5)
        {
            /*VER SI SIRVE*/
            $datosRecibidos = $request->getParsedBody();
            
            $idEstado = 1;//estado pendiente de inicio
            $acumulador=0;
            
            //Primero verifico que el codigo que genero no este en la tabla, si no esta sigo con el ALTA
            $codigo=$this->CrearCodigoMesa();
            if($codigo != ""){
                $pedido = new pedido();
                $pedido->idMesa = $datosRecibidos['idMesa'];
                /*Prueba de datos*/
                $pedido->idEmpleado=1;
                $pedido->codigo=$this->CrearCodigoMesa();
                /*hasta aca*/
                $pedido->nombreCte = $datosRecibidos['nombreCte'];
                $pedido->foto = $this->ManejoImagen($codigo);
                $pedido->idEstado=$idEstado;
        
                
                $idPedido = $pedido->InsertPedido();        
        
                /*Alta en horario*/
                $horarioInicioPedido = horario::InsertHorarioPedido($idPedido);
                $cant=0;
                /*Alta de productos en ped_prod*/
                foreach ($datosRecibidos['productos'] as $producto) {
        
                    $pedProd = new pedProd();
                    $pedProd->idPedido = $idPedido;
                    $pedProd->idProducto = $producto['idProducto'];
                    $pedProd->idEstado = 1;
                    $pedProd->cantidad = $producto['cantidad'];
        
                    $idPedProd = $pedProd->InsertPedProd();
                    
                    $unProduto=producto::SelectUnProducto($producto['idProducto']);
                    $acumulador+=($unProduto->monto * $producto['cantidad']);
                    
                    //Para saber cantidad de productos en el pedido
                    $cant+=$idPedProd;
                }
                //actualizo MontoTotal en el Alta
                $pedidoMontoOk=pedido::ActualizarMontoTotal($idPedido, $acumulador);
                
                
                //Lo mejor seria mostrar el idPedido con la cantidad de productos q contiene
                $responsePedido = array('codigoMesa' => $codigo, 'idPedido' => $idPedido, 'cantidadProductos' => $cant, 'precioTotal' => $acumulador);
                
                $newResponse = $response->withJson($responsePedido, 200);
                
                return $newResponse;
            }
            else
            {
                echo "volver a intentar mas tarde";
            }
        }else{
            $responseFalla= array('estado' => "No Autorizado");
            return $response->withJson($responseFalla,401);
        }
        
    }


    //LISTADO SEGUN EL EMPLEADO
    public function TraerTodos($request, $response, $args)
    {        
        $tipoEmp=MWAuthJWT::ValidarEmpleado($request);
        
        if($tipoEmp ==1 || $tipoEmp ==5)
        {
            
            $listado=pedido::SelectLosPedidos();            
        }
        else
        {            
            $listado=pedido::SelectLosPedidosPorEmpleado($tipoEmp);
            
        }        
        $newResponse = $response->withJson($listado, 200);
        return $newResponse;
    }

    public function TraerUno($request, $response, $args)
    {        
        //code...
    }

    public function BorrarUno($request, $response, $args)
    {
        //code...
    }
    public function ModificarUno($request, $response, $args)
    {
        //code...
    }


    //CREACION DEL CODIGO DEL PEDIDO
    //RESPONDE CON EL CODIGO ALFANUMERICO long. 5
    public function CrearCodigoMesa()
    {
        $alpha = "123qwertyuiopa456sdfghjklzxcvbnm789";
        $code = "";
        $longitud=5;
        $intentos=0;
        $limiteIntentos=4;
        //while para controlar los intentos de random
        while ($intentos < $limiteIntentos) {
            
            for($i=0;$i<$longitud;$i++){                

                $code .= $alpha[rand(0, strlen($alpha)-1)];
                // if($intentos==$limiteIntentos){echo $intentos;die();}
                
                if($i == ($longitud-1) && $intentos < $limiteIntentos)
                {
                    if(pedido::ValidarCodigoMesa($code) != 0)
                    {
                        //Si existe intento tirar otro random                        
                        $i=0;
                        $intentos+=1;
                        $code = "";
                        
                        break;
                    }
                    else
                    {
                        //Si codigo no existe en tabla, devuelvo el generado                        
                        $codigo=$code;                        
                        return $codigo;                        
                    }
                }

            }            
        }
    }


    //PONGO A LA FOTO EL NOMBRE DEL CODIGO DE MESA Y GUARDO EN BASE LA DIRECCION DONDE SE ALMACENO LA IMAGEN
    //DEVUELVE STRING PARA GUARDAR
    public function ManejoImagen($codigo)
    {
        if($_FILES != NULL){
            $path= "./fotos/";
            
            $fotoOrigen=$_FILES['foto']['tmp_name'];
            $aux= $_FILES['foto']['name'];       
            
            $extension = explode(".",$aux);        
            //$nombreFoto="poi098".".".$extension[1];
            $destinoString=$path.$codigo.".".$extension[1];
            move_uploaded_file($fotoOrigen,$destinoString);
            return $destinoString;
        }
        else
        {
            return $destinoString="Sin foto";
        }
    }

}