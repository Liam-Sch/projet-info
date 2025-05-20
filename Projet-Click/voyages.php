<?php
// Chargement des voyages
$fichier = "data/voyages.json";
$voyages = [];

if (file_exists($fichier)) {
    $contenu = file_get_contents($fichier);
    $voyages = json_decode($contenu, true);
}

// Pagination
$parPage = 6;
$total = count($voyages);
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$totalPages = ceil($total / $parPage);
$debut = ($page - 1) * $parPage;
$voyages_affiches = array_slice($voyages, $debut, $parPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nos Voyages</title>
    <link id="theme-link" rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: sans-serif;
            background-color: #f5f5f5;
            padding: 0;
            margin: 0;
        }

        .top-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 0 0 20px 20px;
            flex-wrap: wrap;
        }

        .top-header .left {
            flex: 0 0 auto;
        }

        .top-header .center {
            flex: 1 1 auto;
            text-align: center;
        }

        .btn-accueil {
            background-color: white;
            color: #007bff;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .voyages {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 30px;
        }

        .card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 300px;
            padding: 16px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 6px;
        }

        .card h3 {
            margin: 10px 0 6px;
        }

        .card p {
            margin: 5px 0;
        }

        .card a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 14px;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }

        .card a:hover {
            background-color: #0056b3;
        }

        .pagination {
            text-align: center;
            margin: 30px 0;
        }

        .pagination a {
            padding: 8px 12px;
            margin: 0 5px;
            background: #eee;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a.active {
            background: #007bff;
            color: white;
            font-weight: bold;
        }

        .tri-wrapper {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 12px 20px;
            margin: 20px auto;
            border-radius: 10px;
            width: fit-content;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tri-wrapper select,
        .tri-wrapper button {
            padding: 6px 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 15px;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="top-header">
    <div class="left">
        <a href="index.php" class="btn-accueil">🏠 Accueil</a>
    </div>
    <div class="center">
        <h1>🌍 Liste des voyages</h1>
        <p>Découvrez nos destinations disponibles autour du monde</p>
    </div>
</div>

<div class="tri-wrapper">
  <label for="tri">🔽 Trier par :</label>
  <select id="tri" onchange="trierVoyages()">
    <option value="">-- Sélectionner --</option>
    <option value="titre">Titre</option>
    <option value="date">Date</option>
    <option value="prix">Prix</option>
    <option value="duree">Durée</option>
    <option value="etapes">Nombre d’étapes</option>
  </select>
  <label for="ordreBtn">Ordre :</label>
  <button onclick="changerOrdre()" id="ordreBtn">🔽</button>
</div>

<div class="voyages">
    <?php foreach ($voyages_affiches as $voyage): ?>
        <div class="card">
            <img src="<?php echo htmlspecialchars($voyage['image']); ?>" alt="">
            <h3><?php echo htmlspecialchars($voyage['titre']); ?></h3>
            <p><strong>Dates :</strong> <?php echo $voyage['date_debut']; ?> → <?php echo $voyage['date_fin']; ?></p>
            <p><strong>Prix :</strong> <?php echo $voyage['prix']; ?> €</p>
            <p><strong>Étapes :</strong> <?php echo count($voyage['etapes']); ?></p>
            <a href="voyage_detail.php?id=<?php echo $voyage['id']; ?>">Voir en détail</a>
        </div>
    <?php endforeach; ?>
</div>

<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?php echo $i; ?>" class="<?php if ($i == $page) echo 'active'; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>
</div>

<script>
let ordreAsc = true;

function changerOrdre() {
    ordreAsc = !ordreAsc;
    document.getElementById("ordreBtn").innerText = "Ordre: " + (ordreAsc ? "🔽" : "🔼");
    trierVoyages();
}

function trierVoyages() {
    const critere = document.getElementById("tri").value;
    const cardsContainer = document.querySelector(".voyages");
    const cards = Array.from(cardsContainer.children);

    cards.sort((a, b) => {
        let valA = "", valB = "";

        switch (critere) {
            case "titre":
                valA = a.querySelector("h3").innerText;
                valB = b.querySelector("h3").innerText;
                break;
            case "date":
                valA = new Date(a.querySelector("p").innerText.split("→")[0].replace("Dates :", "").trim());
                valB = new Date(b.querySelector("p").innerText.split("→")[0].replace("Dates :", "").trim());
                break;
            case "prix":
                valA = parseFloat(a.innerHTML.match(/Prix : (\d+)/)[1]);
                valB = parseFloat(b.innerHTML.match(/Prix : (\d+)/)[1]);
                break;
            case "duree":
                const partsA = a.querySelector("p").innerText.split("→").map(s => new Date(s.trim()));
                const partsB = b.querySelector("p").innerText.split("→").map(s => new Date(s.trim()));
                valA = (partsA[1] - partsA[0]);
                valB = (partsB[1] - partsB[0]);
                break;
            case "etapes":
                valA = parseInt(a.innerHTML.match(/Étapes : (\d+)/)[1]);
                valB = parseInt(b.innerHTML.match(/Étapes : (\d+)/)[1]);
                break;
        }

        return ordreAsc ? (valA > valB ? 1 : -1) : (valA < valB ? 1 : -1);
    });

    cardsContainer.innerHTML = "";
    cards.forEach(card => cardsContainer.appendChild(card));
}
</script>

</body>
</html>
