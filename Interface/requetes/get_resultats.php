<?php
require_once("../connexion.php"); // Connexion à la base

$sql = "SELECT TOP (1000) 
            [num_etape],
            [num_coureur_],
            [temps_coureur],
            [bonification_coureur],
            [Rang],
            [gap_sec],
            [penalite_coureur]
        FROM [participer]
        ORDER BY [Rang] ASC"; // Tri par Rang croissant

$stmt = sqlsrv_query($conn, $sql);

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true)); // Affichage des erreurs si requête échoue
}

$resultats = [];

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // Champs temporels à formater
    $timeFields = ['temps_coureur', 'bonification_coureur', 'gap_sec', 'penalite_coureur'];

    foreach ($timeFields as $field) {
        if (isset($row[$field]) && $row[$field] instanceof DateTime) {
            $row[$field] = $row[$field]->format('H:i:s');
        }
    }

    $resultats[] = $row;
}

// Affiche les résultats en JSON formaté
echo json_encode($resultats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>