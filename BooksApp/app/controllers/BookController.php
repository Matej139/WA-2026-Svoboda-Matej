<?php

class BookController {


    //0. Výchozí metoda pro zobrazení úvodní stránky
    public function index() {
        // V dalších krocích se zde přidá komunikace s Modelem pro získání dat z databáze a zobrazení pomocí View
        // (např. načtení všech uložených knih)


        // Nyní se pouze načte (vloží) připravený soubor s HTML strukturou pro zobrazení úvodní stránky
        require_once '../app/views/books/books_list.php';
    }
}