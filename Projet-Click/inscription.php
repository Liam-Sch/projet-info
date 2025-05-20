<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $motdepasse = $_POST['mot_de_passe'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $naissance = $_POST['naissance'];
    $adresse = $_POST['adresse'];

    $fichier = 'data/utilisateurs.json';
    $utilisateurs = [];

    if (file_exists($fichier)) {
        $json = file_get_contents($fichier);
        $utilisateurs = json_decode($json, true);
    }

    foreach ($utilisateurs as $user) {
        if ($user['login'] === $login) {
            echo "<p style='color:red;text-align:center;'>Ce login existe déjà. Choisissez-en un autre.</p>";
            exit;
        }
    }

    $nouvel_utilisateur = [
        "login" => $login,
        "motdepasse" => $motdepasse,
        "role" => "client",
        "nom" => $nom,
        "prenom" => $prenom,
        "naissance" => $naissance,
        "adresse" => $adresse,
        "date_inscription" => date("Y-m-d"),
        "derniere_connexion" => date("Y-m-d")
    ];

    $utilisateurs[] = $nouvel_utilisateur;
    file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));

    $_SESSION['login'] = $login;
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
    <style>
        h2 {
            text-align: center;
            margin-top: 30px;
        }

        .my-form button {
            padding: 8px 12px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .my-form input {
            padding: 8px;
            width: 250px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-wrapper {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            margin: 40px auto;
        }
    </style>
    <script>
    function validateForm() {
        let x = document.forms["myForm"]["login"].value;
        if (x.length < 3) {
            alert("Le login doit contenir au moins 3 caractères");
            return false;
        }
    }

    function changeFormat() {
        let input = document.getElementById("mdp");
        input.type = (input.type === "password") ? "text" : "password";
    }
</script>
</head>

<body>
<header class="main-header">
    <div class="header-left">
        <?php if (isset($_SESSION['login'])): ?>
            <a href="profil.php" class="profil-link">👤 Profil</a>
        <?php endif; ?>
    </div>
    <div class="header-center">
        <h1>Bienvenue sur <span class="highlight">Click-Journey</span></h1>
        <p class="subtitle">Explorez des destinations uniques à travers le monde</p>
    </div>
    <div class="header-right">
        <button onclick="switchTheme()" class="theme-button">🌓 Thème</button>
    </div>
</header>

<main>
    <div class="form-wrapper">
        <form name="myForm" method="post" action="inscription.php" onsubmit="return validateForm()">
            <table class="my-form">
                <tr><td><h2>Créer un compte</h2></td></tr>
                <tr><td><label>Adresse e-mail</label></td></tr>
                <tr><td>
                    <input type="text" id="login" name="login" maxlength="40" required>
                    <small><span id="count-login">0</span>/30 caractères</small>
                </td></tr>

                <tr><td><label>Mot de passe</label></td></tr>
                <tr>
                    <td>
                        <input type="password" id="mdp" name="mot_de_passe" maxlength="25" required>
                        <button type="button" onclick="changeFormat()">👁</button><br>
                        <small><span id="count-mdp">0</span>/20 caractères</small>
                    </td>
                </tr>

                <tr><td><label>Nom</label></td></tr>
                <tr><td><input type="text" name="nom" required></td></tr>

                <tr><td><label>Prénom</label></td></tr>
                <tr><td><input type="text" name="prenom" required></td></tr>

                <tr><td><label>Date de naissance</label></td></tr>
                <tr><td><input type="date" name="naissance" required></td></tr>

                <tr><td><label>Adresse</label></td></tr>
                <tr><td><input type="text" name="adresse" required></td></tr>

                <tr><td style="text-align:center; padding-top: 15px;">
                    <button type="submit">S'inscrire</button>
                </td></tr>
            </table>
        </form>
    </div>
</main>

<script>
function ajouterCompteur(idChamp, idCompteur, max) {
    const champ = document.getElementById(idChamp);
    const compteur = document.getElementById(idCompteur);

    if (champ && compteur) {
        champ.addEventListener("input", () => {
            compteur.textContent = champ.value.length;
            compteur.style.color = (champ.value.length > max) ? "red" : "";
        });
    }
}

ajouterCompteur("login", "count-login", 30);
ajouterCompteur("mdp", "count-mdp", 20);
</script>

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
