<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Click-Journey</title>

    <link id="theme-link" rel="stylesheet" href="style.css">
    <script src="js/theme.js" defer></script>

    <style>
        body {
            margin: 0;
        }

        .main-header {
            background-color: #007bff;
            color: white;
            padding: 20px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .header-left a,
        .header-left form button {
            background-color: white;
            color: #007bff;
            font-weight: bold;
            text-decoration: none;
            padding: 5px 5px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }

        .header-left form {
            margin: 0;
        }

        .header-left form button {
            background-color: #c0392b;
            color: white;
        }

        .header-center {
            flex: 1;
            text-align: center;
        }

        .header-center h1 {
            margin: 0;
            font-size: 2.2rem;
        }

        .header-center .highlight {
            color: yellow;
        }

        .header-center p {
            margin: 4px 0 0;
            font-style: italic;
        }

        /*.header-right {
            display: flex;
            align-items: center;
        }*/

        .theme-button {
            background-color: white;
            color: #007bff;
            font-weight: bold;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            border: none;
        }

        @media screen and (max-width: 768px) {
            .main-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .header-center {
                text-align: left;
                padding-top: 10px;
            }

            .header-right {
                align-self: flex-end;
            }
        }
    </style>
</head>
<body>

<header class="main-header">
    <div class="header-left">
        <a href="index.php">🏠 Accueil</a>
        <?php if (isset($_SESSION['login'])): ?>
            <a href="panier.php">🛒 Mon panier</a>
            <a href="profil.php">👤 Profil</a>
            <form action="logout.php" method="post">
                <button type="submit">🔓 Déconnexion</button>
            </form>
        <?php endif; ?>
    </div>

    <div class="header-center">
        <h1>Bienvenue sur <span class="highlight">Click-Journey</span></h1>
        <p>Explorez des destinations uniques à travers le monde</p>
    </div>

    <div class="header-right">
        <button onclick="switchTheme()" class="theme-button">🌓 Thème</button>
    </div>
</header>
