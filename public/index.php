<?php 

require __DIR__.'./../vendor/autoload.php';


use DI\ContainerBuilder;
// Non Decorated Request and Response
//use Psr\Http\Message\ResponseInterface as Response;
//use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Decorated Requesat and Response by using Slim-Http package 
use Slim\Http\Response;
use Slim\Http\ServerRequest as Request;
use Psr\Http\Server\RequestHandlerInterface;

$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder->enableCompilation(__DIR__ . '/var/cache');

$container = (new ContainerBuilder())
    ->addDefinitions(__DIR__.'./../src/container/container-definitions.php')
    //->enableCompilation(__DIR__.'./../src/container/compiled') // if config file changes we should delete files ib compiled folder each time
    ->build();

AppFactory::setContainer($container);



$app = AppFactory::create();

$container->set('responseFactory', $app->getResponseFactory() );

$app->get('/',function(Request $req,Response $res,array $args){
    
    $welcome = $this->get('welcome');
    $view = $this->get('view');
    $result = $view->render('/home/index.twig', compact('welcome'));
    $res->getBody()->write($result);
    return $res;
});



function AuthMiddleware(Request $req,RequestHandlerInterface $handler){
    if($req->hasHeader('Authorization')){
        $token = substr($req->getHeader('Authorization')[0],7);
        return $handler->handle($req->withAttribute('token',$token));
    }
    return (new \Nyholm\Psr7\Response(400,[],'Invalid Auth'));
}

$app->get('/authors',function(Request $req,Response $res){
    return $res->withJson([
        'token'=> $req->getAttribute('token','NO TOKEN, WTF!!'),
        'authors'=>[
            ['first_name'=>'Bob','age'=>20],
            ['first_name'=>'Joe','age'=>25],
    ]]);
})->add('AuthMiddleware');

$app->run();

