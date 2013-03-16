<?php

session_start();

if (!isset($_GET['a']) | ($_GET['a'] == "")) {  header("Location: .."); die(); }

$i = intval($_GET['a']);
$xml = simplexml_load_file('data.xml');

if ($xml->command[$i]->login == "1" && (!isset($_SESSION['user']) | empty($_SESSION['user']))) { header("Location: ../?m=Vous devez être identifié pour exécuter cette commande."); die(); }

exec($xml->command[$i]->command);
header("Location: ../?m=".$xml->command[$i]->success);

?>