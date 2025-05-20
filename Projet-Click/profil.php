<?php
// ✅ Ne PAS redémarrer la session si elle est déjà active (header.php le fait déjà normalement)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login'])) {
    header("Location: connexion.php");
    exit;
}

$utilisateur = $_SESSION['login'];
$commandes = json_decode(file_get_contents("data/commandes.json"), true);
$voyages = json_decode(file_get_contents("data/voyages.json"), true);

// Sécurité : vérifier que 'user' existe bien
$utilisateurs = json_decode(file_get_contents("data/utilisateurs.json"), true);
$userData = [
    'nom' => '',
    'adresse' => '',
    'password' => ''
];

foreach ($utilisateurs as $u) {
    if ($u['login'] === $utilisateur) {
        $userData['nom'] = $u['nom'];
        $userData['adresse'] = $u['adresse'];
        $userData['password'] = $u['motdepasse']; // ou ce que tu veux afficher
        break;
    }
}

$mes_commandes = array_filter($commandes, function($cmd) use ($utilisateur) {
    return $cmd['login'] === $utilisateur;
});
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: sans-serif; background: #f2f2f2; padding: 20px; }
        h1, h2 { text-align: center; }
        .carte {
            background: white;
            max-width: 700px;
            margin: 20px auto;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .carte a {
            display: inline-block;
            padding: 6px 12px;
            margin-top: 10px;
            background: royalblue;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }
        form#form-profil {
            margin-top: 30px !important; 
        }

        body {
            padding-top: 20px; 
        }   

    </style>
</head>
<body>

<h1>Bienvenue <?php echo htmlspecialchars($utilisateur); ?></h1>


<h2>Mes informations</h2>

<?php if (isset($_GET['updated'])): ?>
  <p style="text-align: center; color: green;">✅ Profil mis à jour avec succès.</p>
<?php endif; ?>

<h2>  
  <a href="logout.php" style="float: right; font-size: 16px; background: #dc3545; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none;">🚪 Déconnexion</a>
</h2>

<form method="POST" action="update_profil.php" id="form-profil" style="max-width: 600px; margin: auto;">
  <div class="carte">
    <label for="champ-nom">Nom :</label>
    <input type="text" name="nom" id="champ-nom" value="<?= htmlspecialchars($userData['nom']) ?>" readonly>
    <button type="button" onclick="activerEdition('nom')">✏️</button>
    <button type="button" id="valider-nom" onclick="validerChamp('nom')" style="display:none;">✅</button>
    <button type="button" id="annuler-nom" onclick="annulerChamp('nom')" style="display:none;">❌</button>
  </div>

  <div class="carte">
    <label for="champ-adresse">Adresse :</label>
    <input type="text" name="adresse" id="champ-adresse" value="<?= htmlspecialchars($userData['adresse']) ?>" readonly>
    <button type="button" onclick="activerEdition('adresse')">✏️</button>
    <button type="button" id="valider-adresse" onclick="validerChamp('adresse')" style="display:none;">✅</button>
    <button type="button" id="annuler-adresse" onclick="annulerChamp('adresse')" style="display:none;">❌</button>
  </div>

  <div class="carte">
    <label for="champ-password">Mot de passe :</label>
    <input type="password" name="password" id="champ-password" value="<?= htmlspecialchars($userData['password']) ?>" readonly>
    <button type="button" onclick="activerEdition('password')">✏️</button>
    <button type="button" id="toggle-password" onclick="togglePasswordVisibility()">👁️</button>
    <button type="button" id="valider-password" onclick="validerChamp('password')" style="display:none;">✅</button>
    <button type="button" id="annuler-password" onclick="annulerChamp('password')" style="display:none;">❌</button>
  </div>

  <div style="text-align: center; margin-top: 20px;">
    <button type="submit">💾 Soumettre les modifications</button>
  </div>
</form>

<h2>Mes voyages réservés</h2>

<?php foreach ($mes_commandes as $cmd): 
    $voyage = null;
    foreach ($voyages as $v) {
        if ($v['id'] == $cmd['voyage_id']) {
            $voyage = $v;
            break;
        }
    }
    if (!$voyage) continue;
?>
    <div class="carte">
        <h3><?= htmlspecialchars($voyage['titre']) ?></h3>
        <p><strong>Dates :</strong> <?= $voyage['date_debut'] ?> → <?= $voyage['date_fin'] ?></p>
        <p><strong>Montant payé :</strong> <?= number_format($cmd['montant'], 2) ?> €</p>
        <a href="voyage_détail_vue.php?voyage_id=<?= $voyage['id'] ?>&readonly=1">Voir</a>
    </div>
<?php endforeach; ?>

<script>
const valeursInitiales = {};

function activerEdition(champ) {
  const input = document.getElementById("champ-" + champ);
  const validerBtn = document.getElementById("valider-" + champ);
  const annulerBtn = document.getElementById("annuler-" + champ);
  valeursInitiales[champ] = input.value;
  input.removeAttribute("readonly");
  input.focus();
  validerBtn.style.display = "inline";
  annulerBtn.style.display = "inline";
}

function validerChamp(champ) {
  const input = document.getElementById("champ-" + champ);
  input.setAttribute("readonly", true);
  document.getElementById("valider-" + champ).style.display = "none";
  document.getElementById("annuler-" + champ).style.display = "none";
}

function annulerChamp(champ) {
  const input = document.getElementById("champ-" + champ);
  input.value = valeursInitiales[champ];
  input.setAttribute("readonly", true);
  document.getElementById("valider-" + champ).style.display = "none";
  document.getElementById("annuler-" + champ).style.display = "none";
}

function togglePasswordVisibility() {
  const input = document.getElementById("champ-password");
  input.type = (input.type === "password") ? "text" : "password";
}
</script>

</body>

<a href="index.php" style="
    position: absolute;
    top: 103px;
    left: 15px;
    background-color: #007bff;
    color: white;
    padding: 8px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    z-index: 1000;
">🏠 Accueil</a>


</html>