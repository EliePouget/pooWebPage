<?php

declare(strict_types=1);
require_once('../autoload.php');

$html = new WebPage();

$html->setTitle('Table de Pythagore');

$html->appendCSS(
    <<<CSS
    table#pythagore {
          border-spacing : 0;
          border-collapse: collapse;
        }
        table#pythagore td, table#pythagore th {
          width: 1.5em;
          height: 1.5em;
          text-align: right;
          padding: 0.2em;
          border: solid 1px grey;
        }
CSS
);

$html->appendContent(
    <<<HTML
    <h1>Table de Pythagore</h1>
        <table id='pythagore'>
          <tr><th>&times;
HTML);


// Première ligne
for ($colonne = 0; $colonne <= INDICE_MAX; $colonne++) {
    $html .= "<th>$colonne";
}
// Les lignes de multiplication
for ($ligne = 0; $ligne <= INDICE_MAX; $ligne++) {
    $html .= "\n          <tr><th>$ligne";
    // Les colonnes de multiplication
    for ($colonne = 0; $colonne <= INDICE_MAX; $colonne++) {
        $html .= "<td>" . ($ligne * $colonne);
    }
}
$html .= "\n        </table>\n";



echo $html->toHTML();