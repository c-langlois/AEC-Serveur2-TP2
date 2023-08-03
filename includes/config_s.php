<?php
//Sert à faire la connexion à la base de données
$db_user = 'root';
$db_password = 'Players1';
$db_name = 'test_tp2_serveur2';

$db = new PDO('mysql:host=localhost;port=3307; dbname=' . $db_name . ';charset=utf8', $db_user, $db_password);

//set some db attributes
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

define('APP_NAME', 'PHP API REST LAB');
