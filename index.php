<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Middleware\MethodOverrideMiddleware; // needs to be enabled in the case of delete
use DI\Container;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/vendor/autoload.php';

// Inclusion du fichier de configuration
require_once __DIR__ . '/includes/config.php';

// Inclusion du fichier du modèle
require_once __DIR__ . '/core/post.php';

// Création d'un conteneur
$container = new Container();

// Création d'une nouvelle instance de l'application Slim avec le conteneur
$app = AppFactory::create(null, $container);

// Activation de la surcharge de méthode (dans le cas de DELETE)
$app->addRoutingMiddleware();
$app->add(MethodOverrideMiddleware::class);

// Ajout du middleware de parsing du corps des requêtes
$app->addBodyParsingMiddleware();

// Définition d'une route pour récupérer les articles
$app->get('/api/posts', function (Request $request, Response $response, array $args) use ($db) {

    // Instantiate the Post model and pass the database connection
    $post = new Post($db);

    // Récupération des articles depuis la base de données
    $result = $post->read();

    // Vérification si des articles ont été trouvés
    if ($result->rowCount() > 0) {
        $posts = $result->fetchAll(PDO::FETCH_ASSOC);

        // Retour des articles en tant que réponse JSON
        $response->getBody()->write(json_encode($posts));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        // Aucun article trouvé
        $response->getBody()->write(json_encode(['message' => 'No posts found.']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
});

// Définition d'une route pour supprimer un article
$app->delete('/api/post/delete/{id}', function (Request $request, Response $response, $args) use ($db) {
    // Get the post ID from the URL
    $id = $args['id'];

    // Instantiation du modèle Post en passant la connexion à la base de données
    $post = new Post($db);

    // Suppression de l'article
    if ($post->delete($id)) {
        // Retour d'une réponse JSON de succès
        $data = ['message' => 'Post deleted successfully'];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        // Retour d'une réponse JSON d'erreur
        $data = ['message' => 'Failed to delete post'];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});


// Définition d'une route pour mettre à jour un article
$app->put('/api/post/update/{id}', function (Request $request, Response $response, $args) use ($db) {
    // Récupération de l'ID de l'article depuis l'URL
    $id = $args['id'];

    // Récupération des données mises à jour de l'article depuis le corps de la requête
    $data = $request->getParsedBody();
    $title = $data['title'] ?? '';
    $body = $data['body'] ?? '';

    // Instantiation du modèle Post en passant la connexion à la base de données
    $post = new Post($db);

    // Update the post
    if ($post->update($id, $title, $body)) {
        // Return a success JSON response
        $data = ['message' => 'Post updated successfully'];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        // Return an error JSON response
        $data = ['message' => 'Failed to update post'];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

$app->patch('/api/post/update_title/{id}', function (Request $request, Response $response, $args) use ($db) {
    // Get the post ID from the URL
    $id = $args['id'];

    // Get the updated post data from the request body
    $data = $request->getParsedBody();
    $title = $data['title'] ?? '';


    // Instantiate the Post model and pass the database connection
    $post = new Post($db);

    // Update the post
    if ($post->update_title($id, $title)) {
        // Return a success JSON response
        $data = ['message' => 'Post title updated successfully'];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        // Return an error JSON response
        $data = ['message' => 'Failed to update title of the post'];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// Définition d'une route pour ajouter un article
$app->post('/api/post/create', function (Request $request, Response $response, $args) use ($db) {

    // Récupération des données
    $data = $request->getParsedBody();
    $category_id = $data['category_id'] ?? '';
    $title = $data['title'] ?? '';
    $body = $data['body'] ?? '';
    $author = $data['author'] ?? '';
    $create_at = date("Y-m-d H:i:s");

    // Instantiation du modèle Post en passant la connexion à la base de données
    $post = new Post($db);

    // Create the post
    if ($post->create($category_id, $title, $body, $author, $create_at)) {
        // Return a success JSON response
        $data = ['message' => 'Post create successfully'];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        // Return an error JSON response
        $data = ['message' => 'Failed to create post'];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// Définition d'une réponse si la route n'existe pas
$app->map(['GET', 'POST', 'PATCH', 'PUT', 'DELETE'], '/{routes:.+}', function (Request $request, Response $response) {
    $response->getBody()->write("404 Not Found");
    return $response->withStatus(404);
});

// Run the Slim app
$app->run();
