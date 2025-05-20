Projet Click-Journey - Phase 3

Groupe : Nasri Assaad, BenJemia Melek, Schilling Liam

Presentation du projet

Click-Journey est une plateforme web interactive développée pour simuler une agence de voyages
personnalisables. Les utilisateurs peuvent consulter des circuits, modifier des options, simuler un
panier, s'inscrire, gérer leur profil et effectuer des paiements simulés via une fausse interface
bancaire (CY Bank).

Objectifs de la Phase 3

Conformément au cahier des charges de la phase 3, cette étape visait à intégrer :
- Système de thème clair/sombre via JavaScript sans rechargement de page
- Changement dynamique de CSS via bouton avec mémorisation cookie
- Vérification JavaScript des formulaires (inscription, connexion)
- Bouton pour afficher/cacher mot de passe
- Compteur caractères en temps réel (login / mot de passe)
- Page profil.php : édition champs avec boutons, soumission conditionnelle
- Page voyages.php : tri dynamique JS (titre, date, prix, durée, étapes)
- Page voyage_detail.php : options dynamiques + prix estimé (JS)
- Page recapitulatif.php : options + prix affichés
- Paiement sécurisé (simulé) via CY Bank + redirection retour_paiement.php
- Gestion du panier avec suppression ou paiement
- Cookies + session PHP pour suivre état de connexion et thème

Repartition des taches

- Nasri Assaad : Développement PHP (connexion, panier, profil), stylisation CSS générale
- BenJemia Melek : JavaScript (thème, vérifications, profil, tri dynamique)
- Schilling Liam : Paiement CY Bank, sécurisation, sessions, stockage JSON

Problemes rencontres et solutions

- Mode sombre ne montrait pas le fond : solution -> background-image avec !important
- Sessions non persistantes : centralisation de session_start()
- Mauvaise redirection post-inscription : vérification des méthodes POST
- Tri JS ne fonctionnait pas : parsing correct + nettoyage balises
- Alignement des boutons : flex et justify-content
- Bouton accueil mal placé : déplacement dans header + position relative
- motdepasse non enregistré : uniformisation mot_de_passe dans HTML/PHP
- Problème de noms variables JS : préfixage clair (champ-, etc.)
- retour_paiement.php échouait : correction de la clé md5() et debug
- Visibilité champs profil : JS pour toggle readonly
- Thème ne chargeait pas toujours : DOMContentLoaded + cookies relus
