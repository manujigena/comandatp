<?php

require_once 'authJWT.php';
class MWAuthJWT
{
    public function ValidarTipoEmpleado($request, $response, $next)
    {
        $arrayConToken=$request->getHeader('token');
        $token=$arrayConToken[0];
        $tokenDeco=authJWT::VerificarToken($token);
        
        return $response = $next($request, $response);
    }


    public function ValidarSoloSocios($request, $response, $next)
    {
        $arrayConToken=$request->getHeader('token');
        $token=$arrayConToken[0];
        
        $tokenDeco=authJWT::VerificarToken($token);
        if($tokenDeco->idTipo==1)
        {
            return $response = $next($request, $response);
        }
    }
    
    
    //Obtengo lo importante del payload
    public static function TraerDataToken($token)
    {
        return authJWT::ObtenerData($token);
    }

    
    //Envio el tipo de empleado del token
    public static function TipoEmpleadoToken($token)
    {
        $aux=self::TraerDataToken($token);
        return $tipoEmp=$aux->idTipo;
    }


    public static function ValidarEmpleado($request)
    {        
        $arrayConToken=$request->getHeader('token');
        $token=$arrayConToken[0];
        $tipoEmp=MWAuthJWT::TipoEmpleadoToken($token);
        return $tipoEmp;
    }


    
}