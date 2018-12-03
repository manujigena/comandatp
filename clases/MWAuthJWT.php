<?php

require_once 'authJWT.php';
class MWAuthJWT
{
    public function ValidarTipoEmpleado($request, $response, $next)
    {
        $arrayConToken=$request->getHeader('token');
        $token=$arrayConToken[0];
        /* var_dump($token);
        die(); */
        $tokenDeco=authJWT::VerificarToken($token);
        
        /* var_dump($tokenDeco);
        die(); */
        if($tokenDeco->idTipo==1)
        {
            /* echo"viene";
            die(); */
            return $response = $next($request, $response);
        }
            

    }
}