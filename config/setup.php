<?php
    require_once 'database.php';

    try {
        $db_construct = new PDO("mysql:host=$DB_HOST", $DB_USER, $DB_PASSWORD);
    }
    catch (PDOException $e) {
        echo "Erreur construct : " . $e->getMessage();
        die();
    }

    $db_construct->prepare("CREATE DATABASE IF NOT EXISTS $DB_NAME DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci")->execute();

    try {
        $db_tables = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    }
    catch (PDOException $e) {
        echo "Erreur tables : " . $e->getMessage();
        die();
    }

    $db_tables->prepare("CREATE TABLE `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `username` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
        `password` varchar(255) NOT NULL,
        `confirmation_token` VARCHAR (60) NULL,
        `confirmed_at` DATETIME NULL,
        `reset_token` VARCHAR (60) NULL,
        `reseted_at` DATETIME NULL,
        `remember_token` VARCHAR (255) NULL,
        PRIMARY KEY (`id`)
       ) ENGINE=InnoDB DEFAULT CHARSET=utf8")->execute();

    $db_tables->prepare("CREATE TABLE `pictures` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `picture` blob NOT NULL,
        `author` varchar(255) NOT NULL,
        `likes` int(11) NOT NULL,
        `date` DATE NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8")->execute();

    $db_tables->prepare("CREATE TABLE `comments` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `picture` varchar(255) NOT NULL,
        `author` varchar(255) NOT NULL,
        `date` DATE NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8")->execute();
?>