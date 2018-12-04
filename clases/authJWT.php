<?php
require_once './vendor/autoload.php';
use Firebase\JWT\JWT;

class authJWT
{
    private static $claveSecreta = 'Comand@Clave';
    private static $tipoEncriptacion = ['HS256'];
    
    
    public static function CrearToken($datos)
    {        
        /*
         parametros del payload
         https://tools.ietf.org/html/rfc7519#section-4.1
         + los que quieras ej="'app'=> "API REST CD 2017" 
        */
        //iat -> cuando fue creado
        /*'iat'=>$ahora,
            'exp' => $ahora + (60),  */
            
        $payload = array(
        	          
            'data' => [
                'idEmpleado'=> $datos->idEmpleado,
                'nombre'=> $datos->nombre, 
                'apellido'=> $datos->apellido,
                'idTipo'=> $datos->idTipo,
                'idEstado'=> $datos->idTipo
            ],
            'app'=> "Token Comanda"
        );
        
        return JWT::encode($payload, self::$claveSecreta);
    }


    public static function VerificarToken($token)
    {
       
        if(empty($token)|| $token=="")
        {
            throw new Exception("El token esta vacio.");
        } 
        // las siguientes lineas lanzan una excepcion, de no ser correcto o de haberse terminado el tiempo       
        try {
            
            return $decodificado = JWT::decode(
            $token,
            self::$claveSecreta,
            self::$tipoEncriptacion
            )->data;
            
            
        } catch (ExpiredException $e) {
            //var_dump($e);
           throw new Exception("Clave fuera de tiempo");
        }        
        
    }
    
    /*VER SI SIRVE*/
    public static function ObtenerData($token)
    {   
        
        return JWT::decode(
            $token,
            self::$claveSecreta,
            self::$tipoEncriptacion
        )->data;
    }

}