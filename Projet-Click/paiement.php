<?php
session_start();
require_once("getapikey.php");

// Bloquer l'accès si pas en POST ou sans données attendues
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || 
    !isset($_POST['voyage_id']) || 
    (!isset($_POST['options_serialisees']) && !isset($_POST['options']))) {
    echo "<p style='color:red'>Accès non autorisé ou données manquantes.</p>";
    exit;
}

$voyage_id = $_POST['voyage_id'];
$_SESSION['voyage_id'] = $voyage_id;

$prix_total = isset($_POST['prix_total']) ? floatval($_POST['prix_total']) : 0;

// Encodage des options pour éviter l’erreur Array to string
if (isset($_POST['options_serialisees'])) {
    $options_serialisees = $_POST['options_serialisees'];
} else {
    $options_serialisees = base64_encode(serialize($_POST['options']));
}

$voyages = json_decode(file_get_contents("data/voyages.json"), true);
$voyage = null;

foreach ($voyages as $v) {
    if ($v['id'] == $voyage_id) {
        $voyage = $v;
        break;
    }
}

if (!$voyage) {
    echo "<p style='color:red'>Voyage introuvable.</p>";
    exit;
}

// Données pour CY Bank
$vendeur = "MIM_A";
$transaction_id = uniqid();
$retour_url = "http://localhost/Projet-Click/retour_paiement.php";
$api_key = getAPIKey($vendeur);
$control = md5($api_key . "#" . $transaction_id . "#" . number_format($prix_total, 2, '.', '') . "#" . $vendeur . "#" . $retour_url . "#");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement – <?php echo htmlspecialchars($voyage['titre']); ?></title>
    <style>
        body { font-family: sans-serif; background: #f7f7f7; padding: 20px; }
        h1, h2 { text-align: center; }
        form {
            background: white; padding: 20px;
            max-width: 500px; margin: auto;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        input[type="text"], select {
            width: 100%; padding: 8px; margin-bottom: 12px;
        }
        button {
            background: #007bff; color: white;
            padding: 10px 20px;
            border: none; border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h1>Paiement sécurisé (Mode local)</h1>

<div style="max-width: 500px; margin: auto;">
    <h2><?php echo htmlspecialchars($voyage['titre']); ?></h2>
    <p><strong>Dates :</strong> <?php echo $voyage['date_debut']; ?> → <?php echo $voyage['date_fin']; ?></p>
    <p><strong>Montant à payer :</strong> <?php echo number_format($prix_total, 2); ?> €</p>
</div>

<form method="post" action="https://www.plateforme-smc.fr/cybank/index.php">
    <label>Numéro de carte bancaire :</label>
    <input type="text" name="cb1" maxlength="16" minlength="16" required pattern="\d{16}" placeholder="1234123412341234" title="Entrez un numéro de carte à 16 chiffres">

    <label>Nom complet du titulaire :</label>
    <input type="text" name="nom" required>

    <label>Date d’expiration :</label>
    <select name="mois" required>
        <?php for ($m = 1; $m <= 12; $m++): ?>
            <option value="<?php echo str_pad($m, 2, "0", STR_PAD_LEFT); ?>">
                <?php echo str_pad($m, 2, "0", STR_PAD_LEFT); ?>
            </option>
        <?php endfor; ?>
    </select>
    <select name="annee" required>
        <?php for ($a = date('Y'); $a <= date('Y') + 10; $a++): ?>
            <option value="<?php echo $a; ?>"><?php echo $a; ?></option>
        <?php endfor; ?>
    </select>

    <label>Code de sécurité :</label>
    <input type="text" name="crypto" maxlength="3" required pattern="\d{3}">

    <!-- Données pour CY Bank -->
    <input type="hidden" name="transaction" value="<?php echo $transaction_id; ?>">
    <input type="hidden" name="montant" value="<?php echo number_format($prix_total, 2, '.', ''); ?>">
    <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
    <input type="hidden" name="retour" value="<?php echo $retour_url; ?>">
    <input type="hidden" name="control" value="<?php echo $control; ?>">

    <!-- Données persistantes -->
    <input type="hidden" name="voyage_id" value="<?php echo $voyage_id; ?>">
    <input type="hidden" name="prix_total" value="<?php echo $prix_total; ?>">
    <input type="hidden" name="options_serialisees" value="<?php echo $options_serialisees; ?>">
    <?php if (isset($_SESSION['login'])): ?>
        <input type="hidden" name="utilisateur" value="<?php echo is_array($_SESSION['login']) ? htmlspecialchars($_SESSION['login']['login']) : htmlspecialchars($_SESSION['login']); ?>">
    <?php endif; ?>

    <button type="submit">Simuler paiement local</button>
</form>

<a href="index.php" style="
    position: absolute;
    top: 15px;
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

</body>
</html>
