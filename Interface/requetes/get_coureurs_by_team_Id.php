<?php
require_once("../connexion.php"); // Connexion à la base

// Récupération du paramètre depuis l'URL : ?team_id=ALPE
if (!isset($_GET['team_id'])) {
    die(json_encode(["error" => "Paramètre 'team_id' manquant."]));
}

$teamId = $_GET['team_id'];

// Requête pour récupérer les coureurs de cette équipe
$sql = "SELECT num_coureur_, nom_coureur, prenom_coureur, tot_sec_coureur 
        FROM Coureur 
        WHERE num_equipe = ?"; // Utilisation de paramètre préparé

$params = array($teamId);
$stmt = sqlsrv_query($conn, $sql, $params);

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}

$coureurs = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $coureurs[] = $row;
}

// Affichage en JSON
echo json_encode($coureurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>