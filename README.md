#  Mi Casa — Application Web de Gestion Hôtelière

**Mi Casa** est une application web moderne de gestion hôtelière et de réservation de chambres en ligne. Développée dans le cadre du projet S2 du département *Web and Mobile Engineering* (Filière *Data and Software Sciences*) à l'**ENSIAS**, cette plateforme simplifie la réservation pour les clients tout en offrant un outil complet de gestion administrative pour le personnel hôtelier.

---

##  Contexte & Objectifs

L'objectif principal du projet est de digitaliser et d'optimiser le processus de réservation d'un établissement hôtelier :
* **Côté Client :** Faciliter la recherche de chambres disponibles, la réservation en ligne, le paiement, et la consultation d'historique ou le dépôt d'avis.
* **Côté Personnel :** Offrir un tableau de bord permettant la gestion des chambres, la réservation directe en réception, la facturation et le suivi du chiffre d'affaires.

---

##  Profils Utilisateurs & Fonctionnalités

L'application prend en charge un **Contrôle d'Accès Basé sur les Rôles (RBAC)** articulé autour de 3 profils distincts :

### 1.  Client (Voyageur)
* **Consultation :** Recherche des chambres disponibles selon les dates de séjour.
* **Réservation :** Prise de réservation en ligne avec gestion des options / services supplémentaires.
* **Paiement & Facturation :** Paiement en ligne (Carte / Cash) et génération de factures / reçus en PDF.
* **Avis :** Publication de commentaires et de notes sur les séjours passés.

### 2.  Réceptionniste
* **Gestion des Réservations :** Création, modification et annulation de réservations pour les clients physiques.
* **Gestion du Planning :** Consultation de l'historique d'occupation des chambres.
* **Services :** Attribution des services supplémentaires aux réservations.
* **Facturation :** Édition et impression des factures.

### 3.  Administrateur
* **Tableau de Bord :** Suivi des statistiques globales et visualisation du Chiffre d'Affaires (CA).
* **Gestion du Personnel :** Création et administration des comptes réceptionnistes.
* **Gestion du Parc Hôtelier :** Ajout, modification, tarification et suppression des chambres et catégories.
* **Gestion des Prestations :** Administration du catalogue de services supplémentaires.

---

##  Architecture & Conception

Le projet repose sur un modèle d'architecture logicielle **MVC (Modèle-Vue-Contrôleur)** garantissant la modularité, la maintenabilité et la séparation des responsabilités.

* **Diagramme de Cas d'Utilisation :** Modélisation des interactions selon le rôle (Client, Réceptionniste, Admin).
* **Modèle Conceptuel de Données (MCD) / Modèle Relational :**
  * Entités clés : `Utilisateur`, `Client`, `Réceptionniste`, `Administrateur`, `Chambre`, `Catégorie`, `Réservation`, `Paiement`, `Facture`, `Supplémentaire`, `Commentaire`.
* **Design Pattern MVC :** Séparation stricte entre les vues (HTML/CSS/Bootstrap), la logique métier (Contrôleurs), et l'accès aux données (Modèles SGBD).

---

##  Stack Technique

* **Langage Back-End :** PHP (Architecture MVC)
* **Base de Données :** MySQL / SGBD Relationnel
* **Front-End :** HTML5, CSS3, JavaScript, Bootstrap
* **Génération de Documents :** Module PHP pour la création de factures PDF
* **Conception & UML :** Diagrammes de cas d'utilisation, de classes, de séquence et MCD

---
##  Équipe du Projet

Projet réalisé dans le cadre du cursus d'ingénieur à l'**ENSIAS** (Université Mohammed V de Rabat) :

* **Aya TAKI**
* **Anas BENAMARA**
