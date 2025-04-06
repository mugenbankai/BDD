<?php
require_once("../connexion.php"); // Inclure la connexion à la base de données

// Requête SQL pour récupérer les informations des étapes par coureur
$sql = "SELECT ALL [num_etape], [num_coureur_], [temps_coureur], [bonification_coureur], [Rang], [gap_sec], [penalite_coureur]
        FROM [Tour_De_France].[dbo].[participer]";

$stmt = sqlsrv_query($conn, $sql); // Exécution de la requête

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true)); // Si erreur de requête, l'afficher
}

$etapes_by_coureurs = []; // Tableau pour stocker les résultats
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $etapes_by_coureurs[] = $row; // Ajouter chaque ligne dans le tableau
}

// Renvoie les résultats en format JSON
echo json_encode($etapes_by_coureurs);
?>