<?php
require_once("../connexion.php");

$sql = "SELECT num_equipe, nom_equipe FROM Equipe";
$stmt = sqlsrv_query($conn, $sql);

$equipes = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $equipes[] = $row;
}

echo json_encode($equipes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>