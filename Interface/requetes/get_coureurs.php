<?php
require_once("../connexion.php"); // Inclure la connexion à la base

$sql = "SELECT ALL num_coureur_, nom_coureur FROM Coureur";
$stmt = sqlsrv_query($conn, $sql); // Exécution de la requête

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true)); // Si erreur de requête, l'afficher
}

$coureurs = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $coureurs[] = $row; // Ajouter chaque ligne de la réponse dans un tableau
}

// Afficher les données en JSON
echo json_encode($coureurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>