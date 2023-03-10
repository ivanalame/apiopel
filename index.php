<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require "conexion/Conexion.php";

$app = AppFactory::create();
$app -> setBasePath("/apiopel");
$conn = Conexion::getPDO();
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Welcome apiopel Ivan");
    return $response;
});

// Get modelos

$app->get('/modelos', function ($request, $response, $args) use ($conn){
    $ordenSql = "select * from modelo order by nombre";
    $statement = $conn->prepare($ordenSql);
    $statement->execute();
    $salida = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement = null;
    $payload = json_encode(["modelos"=>$salida],JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json; charset=UTF8');

});
//get	/modelo/{idmodelo}/acabados	
$app->get('/modelos', function ($request, $response, $args) use ($conn){
    $ordenSql = "select * from modelo order by nombre";
    $statement = $conn->prepare($ordenSql);
    $statement->execute();
    $salida = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement = null;
    $payload = json_encode(["modelos"=>$salida],JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json; charset=UTF8');

});

//Get usuario por nick y pass

$app->get('/usuario', function ($request, $response, $args) use ($conn) {
    $nick = $request->getParam('nick');
    $pass = $request->getParam('pass');
    //select * from usuario where nick='paco' and pass=md5('paco')
    $ordenSql = "select * from usuario where nick=:nick and pass=md5(:pass)";
    $statement = $conn->prepare($ordenSql);
    $statement->bindParam(':nick', $nick, PDO::PARAM_STR);
    $statement->bindParam(':pass', $pass, PDO::PARAM_STR);
    $statement->execute();
    $salida = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement = null;

    if ($salida != null) {
        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json; charset=UTF8')
            ->write(json_encode(["usuario" => $salida[0]], JSON_UNESCAPED_UNICODE));
    } else {
        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json; charset=UTF8')
            ->write(json_encode(["usuario" => null], JSON_UNESCAPED_UNICODE));
    }
});

$app->run();
?>