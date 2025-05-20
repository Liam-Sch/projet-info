<?php
session_start();

// Vérifie que l'utilisateur est connecté via le login
if (!isset($_SESSION['login'])) {
    header('Location: connexion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Chemin vers le bon fichier utilisateurs.json
    $fichier = __DIR__ . '/data/utilisateurs.json';
    if (!file_exists($fichier)) {
        die("Fichier utilisateur introuvable.");
    }

    $utilisateurs = json_decode(file_get_contents($fichier), true);
    if (!$utilisateurs) {
        die("Erreur de lecture des utilisateurs.");
    }

    $login = $_SESSION['login'];
    $trouve = false;

    foreach ($utilisateurs as &$user) {
        if ($user['login'] === $login) {
            $user['nom'] = $nom;
            $user['adresse'] = $adresse;
            if (!empty($password)) {
                $user['motdepasse'] = $password;
            }
            $trouve = true;
            break;
        }
    }

    if ($trouve) {
        file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        header("Location: profil.php?updated=1");
        exit();
    } else {
        echo "Utilisateur non trouvé.";
    }
} else {
    echo "Requête non autorisée.";
}