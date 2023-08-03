<?php

use DI\Container;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

// Instanciez le conteneur de dépendances
$container = new Container();

// Configurer les dépendances

// Exemple de configuration d'une dépendance pour la classe Post
$container->set('Post', function (ContainerInterface $container) {
    $db = $container->get('db');
    return new Post($db);
});

// Ajoutez d'autres configurations de dépendances selon vos besoins

// Retournez le conteneur
return $container;
