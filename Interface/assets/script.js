// Fonction pour récupérer les données depuis un fichier PHP en fonction de l'endpoint
function fetchData(endpoint, withInput = false) {
  let url = `requetes/${endpoint}.php`;

  // Si on veut passer un team_id en paramètre
  if (withInput && endpoint === "get_coureurs_by_team_id") {
    const teamId = document.getElementById("teamIdInput").value.trim(); // Récupère la valeur de l'input teamId
    if (!teamId) {
      alert("Veuillez entrer un ID d'équipe !");
      return;
    }
    url += `?team_id=${teamId}`;
  }

  fetch(url)
    .then((response) => response.json()) // Convertir la réponse en JSON
    .then((data) => {
      const resultDiv = document.getElementById("result");
      resultDiv.innerHTML = generateTable(data); // Générer le tableau HTML à partir des données
    })
    .catch((error) => {
      document.getElementById(
        "result"
      ).innerHTML = `<p style="color: red;">Erreur : ${error}</p>`;
    });
}

// Fonction pour transformer les données JSON en tableau HTML
function generateTable(data) {
  if (!Array.isArray(data) || data.length === 0) {
    return "<p>Aucune donnée trouvée.</p>";
  }

  let table = "<table><thead><tr>";

  // En-têtes de colonnes
  Object.keys(data[0]).forEach((key) => {
    table += `<th>${key}</th>`;
  });
  table += "</tr></thead><tbody>";

  // Lignes de données
  data.forEach((row) => {
    table += "<tr>";
    Object.values(row).forEach((value) => {
      // Format de la date (si c'est un objet de type date)
      if (typeof value === "object" && value.date) {
        value = value.date.split(" ")[0]; // Afficher uniquement la date
      }
      table += `<td>${value}</td>`;
    });
    table += "</tr>";
  });

  table += "</tbody></table>";
  return table;
}

// Fonction pour afficher l'input et le bouton de soumission pour le team_id
function showTeamInput() {
  // Afficher l'input et le bouton lorsque l'utilisateur clique sur le bouton
  document.getElementById("teamInputContainer").style.display = "block";
}

// Fonction pour récupérer les coureurs par team_id et afficher les résultats
function fetchCoureursByTeam() {
  const teamId = document.getElementById("teamIdInput").value.trim(); // Récupérer l'ID de l'équipe

  if (!teamId) {
    alert("Veuillez entrer un ID d'équipe.");
    return;
  }

  // Appeler la requête PHP en passant le team_id dans l'URL
  fetch(`requetes/get_coureurs_by_team_id.php?team_id=${teamId}`)
    .then((response) => response.json())
    .then((data) => {
      // Afficher les résultats dans le div result
      let resultHtml =
        "<table><tr><th>Numéro de Coureur</th><th>Nom</th><th>Prénom</th><th>Total Temps</th></tr>";
      data.forEach((coureur) => {
        resultHtml += `<tr>
                      <td>${coureur.num_coureur_}</td>
                      <td>${coureur.nom_coureur}</td>
                      <td>${coureur.prenom_coureur}</td>
                      <td>${coureur.tot_sec_coureur}</td>
                  </tr>`;
      });
      resultHtml += "</table>";

      // Mettre à jour la div avec le tableau des résultats
      document.getElementById("result").innerHTML = resultHtml;

      // Cacher l'input et bouton après soumission
      document.getElementById("teamInputContainer").style.display = "none";
    })
    .catch((error) => {
      console.error("Erreur:", error);
    });
}
const doc = new jsPDF();
doc.text("Ceci est un texte à imprimer", 10, 10);
doc.save("document.pdf");
