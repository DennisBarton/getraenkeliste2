<?php
$pdo = new PDO('mysql:host=mariadb;dbname=getraenkeliste2;charset=utf8', 'root', 'rootpwd');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
