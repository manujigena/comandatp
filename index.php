<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once './vendor/autoload.php';
require_once './clases/AccesoDatos.php';
require_once './clases/bussinessEmpleado.php';
require_once './clases/bussinessProducto.php';
require_once './clases/bussinessPedido.php';
require_once './clases/authJWT.php';
require_once './clases/MWAuthJWT.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;


$app = new \Slim\App(["settings" => $config]);

/* $app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});
$app->get('/', function (Request $request, Response $response, array $args) {    
    $response->getBody()->write("GET => Bienvenido!!! ,a SlimFramework");
    return $response;

}); */


$app->group('/empleado', function () {
    $this->get('/' , \bussinessEmpleado::class . ':TraerTodos');
    $this->get('/{legajo}' , \bussinessEmpleado::class . ':TraerUno')-> add(\MWAuthJWT::class . ':ValidarTipoEmpleado');
    $this->post('/' , \bussinessEmpleado::class . ':CrearUno') -> add(\MWAuthJWT::class . ':ValidarTipoEmpleado');
    $this->post('/login', \bussinessEmpleado::class . ':Login');
});


//MENU
$app->group('/producto', function () {
    $this->get('/' , \bussinessProducto::class . ':TraerTodos');
    $this->get('/{legajo}' , \bussinessProducto::class . ':TraerUno');
    $this->post('/' , \bussinessProducto::class . ':CrearUno');// -> add(\MWAuthJWT::class . ':ValidarTipoEmpleado');    
});



//PEDIDO
$app->group('/pedido', function () {
    $this->get('/' , \bussinessPedido::class . ':TraerTodos');
    $this->get('/{legajo}' , \bussinessPedido::class . ':TraerUno');
    $this->post('/' , \bussinessPedido::class . ':CrearUno');// -> add(\MWAuthJWT::class . ':ValidarTipoEmpleado');    
});



$app->run();