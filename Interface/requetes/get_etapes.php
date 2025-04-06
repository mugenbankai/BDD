<?php
require_once("../connexion.php"); // Inclure la connexion à la base

$sql = "SELECT [num_etape], [date_etape], [km_etape], [type_etape], [num_coureur_], [num_ville], [num_ville_1] FROM Etape";
$stmt = sqlsrv_query($conn, $sql); // Exécution de la requête

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true)); // Si erreur de requête, l'afficher
}

$etapes = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $etapes[] = $row; // ✅ Corrigé ici !
}

// Afficher les données en JSON
echo json_encode($etapes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>