# 📁 Projet Transversal - Conception et Administration d’une Base de Données

## Phase I – Conception de la base de données

### Objectifs

Définir les entités, relations et dépendances pour modéliser les données du Tour de France.

### Sources utilisées

- Site officiel du [Tour de France](https://www.letour.fr/)
- Autres sources internet sur les étapes, coureurs, équipes...

### MCD – Modèle Conceptuel de Données

![Modèle conceptuel](images/mcd.png)

### MLD – Modèle Logique de Données

![Modèle logique](images/mld.png)

---

## Phase II – Création de la base et importation des données

### Script de création des tables

```sql
CREATE TABLE Pays(
   ID_Pays VARCHAR(50) ,
   nom_pays VARCHAR(50)  NOT NULL,
   PRIMARY KEY(ID_Pays)
);

CREATE TABLE Equipe(
   num_equipe VARCHAR(50) ,
   nom_equipe VARCHAR(50)  NOT NULL,
   ID_Pays VARCHAR(50)  NOT NULL,
   PRIMARY KEY(num_equipe),
   UNIQUE(nom_equipe),
   FOREIGN KEY(ID_Pays) REFERENCES Pays(ID_Pays)
);

CREATE TABLE Coureur(
   num_coureur_ INT,
   prenom_coureur VARCHAR(50)  NOT NULL,
   nom_coureur VARCHAR(50)  NOT NULL,
   naissance_coureur DATE NOT NULL,
   bonif_tot_coureur TIME,
   penalite_tot_coureur TIME,
   nbr_etape_coureur INT,
   tot_sec_coureur TIME,
   num_equipe VARCHAR(50)  NOT NULL,
   ID_Pays VARCHAR(50)  NOT NULL,
   PRIMARY KEY(num_coureur_),
   FOREIGN KEY(num_equipe) REFERENCES Equipe(num_equipe),
   FOREIGN KEY(ID_Pays) REFERENCES Pays(ID_Pays)
);

CREATE TABLE Ville(
   num_ville INT,
   nom_ville VARCHAR(50)  NOT NULL,
   ID_Pays VARCHAR(50)  NOT NULL,
   PRIMARY KEY(num_ville),
   FOREIGN KEY(ID_Pays) REFERENCES Pays(ID_Pays)
);

CREATE TABLE Etape(
   num_etape INT,
   date_etape DATE NOT NULL,
   km_etape DECIMAL(15,2)   NOT NULL,
   type_etape VARCHAR(50)  NOT NULL,
   num_coureur_ INT NOT NULL,
   num_ville INT NOT NULL,
   num_ville_1 INT NOT NULL,
   PRIMARY KEY(num_etape),
   FOREIGN KEY(num_coureur_) REFERENCES Coureur(num_coureur_),
   FOREIGN KEY(num_ville) REFERENCES Ville(num_ville),
   FOREIGN KEY(num_ville_1) REFERENCES Ville(num_ville)
);

CREATE TABLE participer(
   num_etape INT,
   num_coureur_ INT,
   temps_coureur TIME,
   bonification_coureur TIME,
   Rang INT,
   gap_sec TIME,
   penalite_coureur TIME,
   PRIMARY KEY(num_etape, num_coureur_),
   FOREIGN KEY(num_etape) REFERENCES Etape(num_etape),
   FOREIGN KEY(num_coureur_) REFERENCES Coureur(num_coureur_)
);

```

## 📌 Phase 3 : Requêtes SQL et Spécifications Techniques

---

## 🧠 Objectif

Ce phase a pour but de manipuler et interroger une base de données représentant le Tour de France 2022. Il comprend la création de tables, le nettoyage de données issues d'un fichier Excel, l'intégration dans une base relationnelle SQL Server, et enfin l’interrogation de cette base via des requêtes SQL avancées.

---

## 🛠️ Spécifications Techniques

- **SGBD utilisé :** SQL Server
- **Langage :** SQL
- **Source des données :** Fichier Excel `TOUR DE FRANCE 2022.xlsx`
- **Nettoyage / transformation :** import des données dans des tables temporaires, insertion contrôlée avec gestion des doublons
- **Tables principales :**
  - `Coureur`
  - `Equipe`
  - `Etape`
  - `Ville`
  - `Pays`
  - `Participer`

---

## 📄 Fonctionnalités réalisées

- Affichage des coureurs selon leur pays ou équipe
- Liste des villes impliquées dans les étapes
- Requêtes sur les participations, bonifications, et pénalités
- Calculs statistiques sur les étapes (moyenne, max, min)
- Requêtes d’analyse générale sur les coureurs, les étapes et les équipes

---

## 🧾 Requêtes SQL

### 1. Requête 1

```sql
SELECT num_coureur_, nom_coureur
FROM Coureur c
JOIN Pays p ON c.ID_Pays = p.ID_Pays
WHERE p.nom_pays = 'France';
```

### 2. Requête 2

```sql
SELECT c.num_coureur_, c.nom_coureur, p.nom_pays
FROM Coureur c
JOIN Equipe e ON c.num_equipe = e.num_equipe
JOIN Pays p ON c.ID_Pays = p.ID_Pays
WHERE e.nom_equipe = 'TOTALENERGIES';
```

### 3. Requête 3

```sql
SELECT DISTINCT c.nom_coureur
FROM Coureur c
WHERE c.num_coureur_ NOT IN (
    SELECT DISTINCT num_coureur_
    FROM participer
    WHERE bonification_coureur IS NOT NULL AND bonification_coureur > '00:00:00'
);
```

### 4. Requête 4

```sql
SELECT c.num_coureur_, c.nom_coureur
FROM Coureur c
WHERE c.num_coureur_ NOT IN (
    SELECT DISTINCT num_coureur_
    FROM participer
);
```

### 5. Requête 5

```sql
SELECT DISTINCT v.nom_ville
FROM Ville v
WHERE v.num_ville IN (
    SELECT num_ville FROM Etape
    UNION
    SELECT num_ville_1 FROM Etape
);
```

### 6. Requête 6

```sql
SELECT v.nom_ville
FROM Ville v
JOIN Pays p ON v.ID_Pays = p.ID_Pays
WHERE p.nom_pays = 'France'
  AND v.num_ville NOT IN (
    SELECT num_ville FROM Etape
    UNION
    SELECT num_ville_1 FROM Etape
);
```

### 7. Requête 7 a

```sql
SELECT
    c.nom_coureur, c.naissance_coureur, p.nom_pays, pa.num_etape
FROM Coureur c
JOIN participer pa ON c.num_coureur_ = pa.num_coureur_
JOIN Pays p ON c.ID_Pays = p.ID_Pays;
```

### 7. Requête 7 b

```sql
SELECT
    c.nom_coureur, c.naissance_coureur, p.nom_pays, pa.num_etape
FROM Coureur c
LEFT JOIN participer pa ON c.num_coureur_ = pa.num_coureur_
JOIN Pays p ON c.ID_Pays = p.ID_Pays;
```

### 8. Requête 8

```sql
SELECT
    p.nom_pays, e.nom_equipe, c.nom_coureur
FROM Coureur c
JOIN Equipe e ON c.num_equipe = e.num_equipe
JOIN Pays p ON c.ID_Pays = p.ID_Pays
ORDER BY p.nom_pays, e.nom_equipe, c.nom_coureur;
```

### 9. Requête 9

```sql
SELECT TOP 1 *
FROM Etape
ORDER BY km_etape ASC;
```

### 10. Requête 10

```sql
SELECT
    nom_coureur,
    DATEDIFF(YEAR, naissance_coureur, GETDATE())
    - CASE
        WHEN MONTH(naissance_coureur) > MONTH(GETDATE())
          OR (MONTH(naissance_coureur) = MONTH(GETDATE()) AND DAY(naissance_coureur) > DAY(GETDATE()))
        THEN 1 ELSE 0
      END AS age
FROM Coureur;
```

### 11. Requête 11

```sql
SELECT e.nom_equipe, COUNT(c.num_coureur_) AS nb_coureurs
FROM Equipe e
LEFT JOIN Coureur c ON e.num_equipe = c.num_equipe
GROUP BY e.nom_equipe;
```

### 12. Requête 12 a

```sql
WITH NbJoueurs AS (
    SELECT e.nom_equipe, COUNT(c.num_coureur_) AS nb_coureurs
    FROM Equipe e
    LEFT JOIN Coureur c ON e.num_equipe = c.num_equipe
    GROUP BY e.nom_equipe
)
SELECT * FROM NbJoueurs
WHERE nb_coureurs >= X;
```

### 12. Requête 12 b

```sql
SELECT e.nom_equipe,COUNT(c.num_coureur_) AS nb_coureurs
FROM Equipe e
JOIN Coureur c ON e.num_equipe = c.num_equipe
GROUP BY e.nom_equipe
HAVING COUNT(c.num_coureur_) >= X;
```

### 13. Requête 13

```sql
SELECT
    c.nom_coureur,
    CAST(DATEADD(SECOND, SUM(DATEDIFF(SECOND, '00:00:00', p.temps_coureur)), '00:00:00') AS TIME) AS duree_totale,
    MAX(p.temps_coureur) AS temps_max,
    MIN(p.temps_coureur) AS temps_min,
    CAST(DATEADD(SECOND, AVG(DATEDIFF(SECOND, '00:00:00', p.temps_coureur)), '00:00:00') AS TIME) AS temps_moyen
FROM Coureur c
JOIN participer p ON c.num_coureur_ = p.num_coureur_
GROUP BY c.nom_coureur;
```

### 14. Requête 14

```sql
SELECT
    e.num_etape,
    v1.nom_ville AS ville_depart,
    v2.nom_ville AS ville_arrivee,
    MIN(p.temps_coureur) AS temps_min,
    MAX(p.temps_coureur) AS temps_max,
    AVG(CAST(DATEDIFF(SECOND, '00:00:00', p.temps_coureur) AS FLOAT)) AS temps_moyen_sec
FROM Etape e
JOIN Ville v1 ON e.num_ville = v1.num_ville
JOIN Ville v2 ON e.num_ville_1 = v2.nom_ville
JOIN participer p ON e.num_etape = p.num_etape
GROUP BY e.num_etape, v1.nom_ville, v2.nom_ville;
```

### 15. Requête 15

```sql
SELECT TOP 3
    p.num_coureur_,
    p.temps_coureur,
    e.num_etape
FROM participer p
JOIN Etape e ON p.num_etape = e.num_etape
WHERE e.type_etape = 'Montagne'
ORDER BY p.temps_coureur ASC;
```

### 16. Requête 16

```sql
SELECT SUM(km_etape) AS total_km
FROM Etape;
```

### 17. Requête 17

```sql
SELECT SUM(km_etape) AS total_km_haute_montagne
FROM Etape
WHERE type_etape = 'Montagne';
```

### 18. Requête 18

```sql
SELECT c.nom_coureur, COUNT(p.num_etape) AS nb_etapes
FROM Coureur c
JOIN participer p ON c.num_coureur_ = p.num_coureur_
GROUP BY c.nom_coureur
HAVING COUNT(p.num_etape) >= X;
```

### 19. Requête 19

```sql
SELECT c.nom_coureur
FROM Coureur c
JOIN participer p ON c.num_coureur_ = p.num_coureur_
GROUP BY c.num_coureur_, c.nom_coureur
HAVING COUNT(DISTINCT p.num_etape) = (SELECT COUNT(*) FROM Etape);
```

### 20. Requête 20

```sql
SELECT
    c.nom_coureur,
    c.num_equipe,
    c.ID_Pays,
    SUM(DATEDIFF(SECOND, '00:00:00', p.temps_coureur)) AS temps_total_sec
FROM Coureur c
JOIN participer p ON c.num_coureur_ = p.num_coureur_
WHERE p.num_etape <= 13
GROUP BY c.nom_coureur, c.num_equipe, c.ID_Pays
ORDER BY temps_total_sec ASC;
```

### 21. Requête 21

```sql
SELECT
    e.nom_equipe,
    SUM(DATEDIFF(SECOND, '00:00:00', p.temps_coureur)) AS temps_total_sec
FROM Equipe e
JOIN Coureur c ON e.num_equipe = c.num_equipe
JOIN participer p ON c.num_coureur_ = p.num_coureur_
WHERE p.num_etape <= 13
GROUP BY e.nom_equipe
ORDER BY temps_total_sec ASC;
```

---

## 🖥️ Prochaine étape : Interface Graphique

Une interface web permettra d’ajouter, modifier et visualiser les données de façon conviviale. Les technologies envisagées seront proposées en fonction de la compatibilité avec SQL Server et ton niveau.

### Technologies utilisées

- PHP
- HTML / CSS
- JavaScript

## 📄 Fonctionnalités réalisées

- Connexion à une base de données SQL Server via PHP (connexion.php)

- Affichage de toutes les équipes (get_equipes.php)

- Affichage de tous les coureurs (get_coureurs.php)

- Affichage des coureurs par équipe (get_coureurs_by_team.php)

- Interface graphique simple avec boutons interactifs pour exécuter les requêtes (index.php)

- Retour des résultats en format JSON via JavaScript (fetch)

- Système dynamique d’entrée utilisateur (saisie d’un ID d’équipe)

- Structure de projet claire et organisée :

  . Dossier assets pour le style (style.css) et le script (script.js)

  . Dossier requetes pour toutes les requêtes SQL en PHP

- Préparation à l’intégration d’autres requêtes complexes (analyse, statistiques, etc.)

## 🖼️ Capture D'écran de l'interface

![Modèle logique](images/interface.png)

### Lien Github

https://github.com/mugenbankai/BDD

---
