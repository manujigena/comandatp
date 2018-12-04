<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once 'vendor/autoload.php';
require_once 'clases/AccesoDatos.php';
require_once 'clases/bussinessEmpleado.php';


$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

/*

¡La primera línea es la más importante! A su vez en el modo de 
desarrollo para obtener información sobre los errores
 (sin él, Slim por lo menos registrar los errores por lo que si está utilizando
  el construido en PHP webserver, entonces usted verá en la salida de la consola 
  que es útil).

  La segunda línea permite al servidor web establecer el encabezado Content-Length, 
  lo que hace que Slim se comporte de manera más predecible.
*/

$app = new \Slim\App(["settings" => $config]);

//require_once "saludo.php";


$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("GET => Bienvenido!!! ,a SlimFramework");
    return $response;

});


/* agrupacion de ruta y mapeado*/
// $app->group('/usuario/{id:[0-9]+}', function () {

//     $this->map(['POST', 'DELETE'], '', function ($request, $response, $args) {
//         $response->getBody()->write("Borro el usuario por p");
//     });

//     $this->get('/nombre', function ($request, $response, $args) {
//         $response->getBody()->write("Retorno el nombre del usuario del id ");
//     });
// });




// /*LLAMADA A METODOS DE INSTANCIA DE UNA CLASE*/
// $app->group('/cd', function () {   

// $this->get('/', \cd::class . ':traerTodos');
// $this->get('/{id}', \cd::class . ':traerUno');
// $this->delete('/', \cd::class . ':BorrarUno');
// $this->put('/', \cd::class . ':ModificarUno');
// //se puede tener funciones definidas
// /*SUBIDA DE ARCHIVO*/

/* $app->group('/empleado', function () {
    $this->post('/' , \bussinessEmpleado::class . ':AltaEmpleado');
}); */






$app->run();