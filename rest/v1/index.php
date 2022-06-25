<?php
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
//use Slim\Factory\AppFactory;
/*
* Instancia accesos BD
*/
require '../vendor/autoload.php';
require '../src/config/db.php';         // DB Connect CL
require '../src/config/dbFact.php';     // DB Connect Fact
$app = new \Slim\App;

/*
* Instancia utilities
*/
require 'utils/manageResponse.php';
require 'utils/validaToken.php';

/*
* Instancia servicios por exponer
*/
require 'endpoints/token.php';      //Token
require 'endpoints/productos.php';  //Products
require 'endpoints/ventas.php';     //Ventas
require 'endpoints/emails.php';     //Correos
require 'endpoints/facturas.php';   //Facturas


$app->run();
