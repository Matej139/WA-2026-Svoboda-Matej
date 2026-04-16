<?php

// Zapnutí zobrazení chyb pro vývojové prostředí
// Tyto direktivy umožňují zobrazit všechny chyby, což je užitečné pro ladění během vývoje.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$baseDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])); // Dynamické určení základního adresáře projektu
define('BASE_URL', $baseDir); // Definice konstanty BASE_URL pro použití v celém projektu (např. pro odkazy)

// Autoloading tříd pomocí Composeru
// Tento řádek načítá všechny třídy definované v projektu pomocí Composer
require_once '../core/App.php';

// Vytvoření instance třídy App, která je zodpovědná za zpracování požadavků a směrování
$app = new App();

?>