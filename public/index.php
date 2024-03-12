<?php
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

use \App\Models\MysqlAdapter;
use \App\Models\Post;
use \App\Models\User;

use \App\Controllers\PostController;
use \App\Controllers\UserController;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

//Setup db connection
$container->set('dbAdapter', function () : MysqlAdapter {
  return new MysqlAdapter('mysql:host=localhost;dbname=phpunit', 'phpunit', 'FCBmLq*8jX@p9weU');
});

//Setup logging
$output = "[%datetime%] %channel%.%level_name%: %message%\n";
$formatter = new LineFormatter($output);

$streamHandler = new StreamHandler('php://stdout');
$streamHandler->setFormatter($formatter);

$logger = new Logger('logger');
$logger->pushHandler($streamHandler);

$container->logger = $logger;

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->add(new BasePathMiddleware($app));
$app->addErrorMiddleware(true, true, true);

/**
 * Get all posts
 * 
 * @param Request $request
 * @param Response $response
 * @param array $args
 * 
 * @return Response
 */
$app->get('/posts', function(Request $request, Response $response, array $args) : Response {
  $dbAdapter = $this->get('dbAdapter');
  $postController = new PostController($dbAdapter, $this->logger);
  $posts = $postController->fetchAll();

  $response->getBody()->write(json_encode($posts));

  return $response
    ->withHeader('Content-Type', 'application/json')
    ->withStatus(200);
});

/**
 * Get post by id
 * 
 * @param Request $request
 * @param Response $response
 * @param array $args
 * 
 * @return Response
 */
$app->get('/posts/{id}', function(Request $request, Response $response, array $args) : Response {
  $id = $args['id'];

  $dbAdapter = $this->get('dbAdapter');
  $postController = new PostController($dbAdapter, $this->logger);
  $post = $postController->fetchById($id);

  $response->getBody()->write(json_encode($post));

  return $response
    ->withHeader('Content-Type', 'application/json')
    ->withStatus(200);
});

/**
 * Get add new post
 * 
 * @param Request $request
 * @param Response $response
 * @param array $args
 * 
 * @return Response
 */
$app->post('/posts', function(Request $request, Response $response, array $args)  : Response {
  $data = $request->getParsedBody();

  $post = new Post();
  $post->setPostTitle($data["post_title"]);
  $post->setAuthorId($data["author_id"]);
  $post->setPost($data["post"]);
  
  $dbAdapter = $this->get('dbAdapter');
  $postController = new PostController($dbAdapter, $this->logger);
  $post = $postController->insert($post);

  $response->getBody()->write(json_encode($post));

  return $response
    ->withHeader('Content-Type', 'application/json')
    ->withStatus(201);
});

/**
 * Get update post by id
 * 
 * @param Request $request
 * @param Response $response
 * @param array $args
 * 
 * @return Response
 */
$app->put('/posts/{id}', function(Request $request, Response $response, array $args) : Response {
  $data = $request->getParsedBody();
  $id = $args['id'];

  $post = new Post();
  $post->setId($id);
  $post->setPostTitle($data["post_title"]);
  $post->setAuthorId($data["author_id"]);
  $post->setPost($data["post"]);

  $dbAdapter = $this->get('dbAdapter');
  $postController = new PostController($dbAdapter, $this->logger);
  $post = $postController->update($post);

  $response->getBody()->write(json_encode($post));

  return $response
    ->withHeader('Content-Type', 'application/json')
    ->withStatus(201);
});

/**
 * Get delete a post by id
 * 
 * @param Request $request
 * @param Response $response
 * @param array $args
 * 
 * @return Response
 */
$app->delete('/posts/{id}', function(Request $request, Response $response, array $args) : Response {
  $id = $args['id'];
  $dbAdapter = $this->get('dbAdapter');
  $postController = new PostController($dbAdapter, $this->logger);
  $postController->delete($id);

  return $response
    ->withHeader('Content-Type', 'application/json')
    ->withStatus(200);
});

/**
 * Get all users
 * 
 * @param Request $request
 * @param Response $response
 * @param array $args
 * 
 * @return Response
 */
$app->get('/users', function(Request $request, Response $response, array $args) : Response {
  $dbAdapter = $this->get('dbAdapter');
  $userController = new UserController($dbAdapter, $this->logger);
  $users = $userController->fetchAll();

  $response->getBody()->write(json_encode($users));

  return $response
    ->withHeader('Content-Type', 'application/json')
    ->withStatus(200);
});

/**
 * Get usre by id
 * 
 * @param Request $request
 * @param Response $response
 * @param array $args
 * 
 * @return Response
 */
$app->get('/users/{id}', function(Request $request, Response $response, array $args) : Response {
  $id = $args['id'];
  $dbAdapter = $this->get('dbAdapter');
  $userController = new UserController($dbAdapter, $this->logger);
  $users = $userController->fetchById($id);

  $response->getBody()->write(json_encode($users));

  return $response
    ->withHeader('Content-Type', 'application/json')
    ->withStatus(200);
});

/**
 * Add user
 * 
 * @param Request $request
 * @param Response $response
 * @param array $args
 * 
 * @return Response
 */
$app->post('/users', function(Request $request, Response $response, array $args) : Response {
  $data = $request->getParsedBody();
  
  $user = new User();
  $user->setFirstName($data['first_name']);
  $user->setLastName($data['last_name']);
  $user->setUsername($data['username']);
  $user->setPassword($data['password']);
  $user->setEmail($data['email']);

  $dbAdapter = $this->get('dbAdapter');
  $userController = new UserController($dbAdapter, $this->logger);
  $user = $userController->insert($user);
  
  $response->getBody()->write(json_encode($user));

  return $response
    ->withHeader('Content-Type', 'application/json')
    ->withStatus(201);
});

/**
 * Update user by id
 * 
 * @param Request $request
 * @param Response $response
 * @param array $args
 * 
 * @return Response
 */
$app->put('/users/{id}', function(Request $request, Response $response, array $args) : Response {
  $id = $args['id'];
  $data = $request->getParsedBody();
  
  $user = new User();
  $user->setId($id);
  $user->setFirstName($data['first_name']);
  $user->setLastName($data['last_name']);
  $user->setUsername($data['username']);
  $user->setPassword($data['password']);
  $user->setEmail($data['email']);

  $dbAdapter = $this->get('dbAdapter');
  $userController = new UserController($dbAdapter, $this->logger);
  $user = $userController->update($user);
  
  $response->getBody()->write(json_encode($user));

  return $response
    ->withHeader('Content-Type', 'application/json')
    ->withStatus(201);
});

/**
 * Delete usre by id
 * 
 * @param Request $request
 * @param Response $response
 * @param array $args
 * 
 * @return Response
 */
$app->delete('/users/{id}', function(Request $request, Response $response, array $args) : Response {
  $id = $args['id'];
  $dbAdapter = $this->get('dbAdapter');
  $userController = new UserController($dbAdapter, $this->logger);
  $userController->delete($id);

  return $response
    ->withHeader('Content-Type', 'application/json')
    ->withStatus(200);
});

/**
 * Register a new user
 * 
 * @param Request $request
 * @param Response $response
 * @param array $args
 * 
 * @return Response
 */
$app->post('/register', function(Request $request, Response $response, array $args) : Response {
  $data = $request->getParsedBody();

  $user = new User();
  $user->setFirstName($data['first_name']);
  $user->setLastName($data['last_name']);
  $user->setUsername($data['username']);
  $user->setPassword($data['password']);
  $user->setEmail($data['email']);

  $dbAdapter = $this->get('dbAdapter');
  $userController = new UserController($dbAdapter, $this->logger);
  $user = $userController->registerUser($user);
  
  $response->getBody()->write(json_encode($user));

  return $response
    ->withHeader('Content-Type', 'application/json')
    ->withStatus(201);
});

$app->run();
