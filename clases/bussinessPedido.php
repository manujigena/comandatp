<?php
require_once 'pedido.php';
require_once 'IComun.php';
require_once 'authJWT.php';
require_once 'pedProd.php';
require_once 'producto.php';
require_once 'horario.php';

class bussinessPedido extends pedido implements IComun
{

    public function CrearUno($request, $response, $args)
    {
        $datosRecibidos = $request->getParsedBody();
        
        /*
        1. idEmpleado = viene del token
        2. codigo= intentar un random de 6char y validar que no exista en la tabla
        3. idProducto = se pasa en request
        4. cantidad del producto en request
        */
        $idEstado = 1;//estado pendiente de inicio
        $acumulador=0;
        //$fotoRequest=$request->getUploadedFiles();
        // $codigo=$this->CrearCodigoMesa();
        // echo$codigo;
        // $stringBDFOTO = $this->ManejoImagen($codigo);
        // echo"fotito ok";
        // die();
        // $idPedido=54;
        // $acumulador=1223;
        // $totalAmount=pedido::ActualizarMontoTotal($idPedido, $acumulador);
        // echo $totalAmount;
        // die();

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
    
            /* var_dump($empleado);
            die(); */
            //idPedido para poder agregar en tabla ped_prod
            //var_dump($pedido);
            $idPedido = $pedido->InsertPedido();
            //echo $idPedido;
    
    
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
                //INTENTAR TRAER EL MONTO
                //$acumunlador
                $unProduto=producto::SelectUnProducto($producto['idProducto']);
                $acumulador+=($unProduto->monto * $producto['cantidad']);
                
                //Para saber cantidad de productos en el pedido
                $cant+=$idPedProd;
            }
            //actualizo MontoTotal en el Alta
            $pedidoMontoOk=pedido::ActualizarMontoTotal($idPedido, $acumulador);
            
            
            //Lo mejor seria mostrar el idPedido con la cantidad de productos q contiene
            $response = array('status' => 200, 'idPedido' => $idPedido, 'cantidad' => $cant, 'precioTotal' => $acumulador);
            //echo "Se termino el alta del pedido con $cant productos, precio total ->"."$".$acumulador;
            //return $response->write(json_encode($idProducto));
            return json_encode($response);

        }
        else
        {
            echo "volver a intentar mas tarde";
        }


        // $fotoRequest=$request->getUploadedFiles();
        // var_dump($fotoRequest);
        // $aux= $fotoRequest['foto']->getClientFilename();
        // echo $aux;
        // die();







        
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