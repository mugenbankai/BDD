<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requêtes Dynamiques</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h1>Interface du Tour de France</h1>

        <div class="button-container">
            <button onclick="fetchData('get_coureurs')">Get Coureurs</button>
            <button onclick="fetchData('get_equipes')">Get Equipes</button>
            <button onclick="fetchData('get_etapes')">Get Etapes</button>
            <button onclick="fetchData('get_etapes_by_coureurs')">Get Etapes By Coureurs</button>
            <button onclick="fetchData('get_resultats')">Get Resultats</button>

            <!-- Bouton pour afficher l'input de team ID -->
            <button onclick="showTeamInput()">Get Coureurs By Team</button>
        </div>

        <!-- Conteneur d'input pour team_id qui est caché au départ -->
        <div id="teamInputContainer" style="display: none;">
            <input type="text" id="teamIdInput" placeholder="Enter Team ID" />
            <button onclick="fetchCoureursByTeam()">Submit</button>
        </div>

        <!-- Zone pour afficher les résultats sous forme de tableau -->
        <div id="result"></div>
        <button onclick="window.print()">Imprimer cette page</button>

    </div>

    <script src="assets/script.js"></script>
</body>

</html>