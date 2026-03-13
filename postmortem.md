# 📝 Post-Mortem : Projet SecurEvent

**Date :** 15 Mars 2026  
**Développeur :** [Ton Nom]  
**Projet :** Plateforme de gestion d'événements Cybersécurité (ESDI)

---

## 1. Résumé de la mission
L'objectif était de concevoir une application robuste sous Symfony 7, capable de gérer des flux d'inscriptions à des événements (CTF, Conférences) tout en garantissant un niveau de sécurité "Security by Design". Le projet a été mené à bien en intégrant 100% du socle fonctionnel et l'intégralité du scope optionnel.

---

## 2. Succès et Réussites (Ce qui a bien fonctionné)

* **Sécurité et Intégrité :** L'utilisation des composants natifs de Symfony (Voters, CSRF protection, Argon2id) a permis de créer une forteresse numérique répondant aux exigences du cahier des charges. Aucune faille critique (XSS, SQLi) n'a été détectée.
* **Algorithme d'Exclusion (LIFO) :** L'implémentation de la logique de réduction de capacité est une réussite technique majeure. Le système identifie et révoque les réservations les plus récentes de manière fluide, assurant la cohérence de la base de données.
* **Expérience Utilisateur (UX) :** L'intégration de Tailwind CSS a permis de produire une interface "Dark Mode" moderne et responsive en un temps record.
* **Scope Optionnel Complet :** L'ajout de la géolocalisation (Google Maps), de l'internationalisation (FR/EN) et de la catégorisation apporte une réelle plus-value professionnelle au projet.

---

## 3. Difficultés rencontrées et Solutions

### 🛠️ Problème : Synchronisation des entités et collections
Lors de la mise en place de la relation `OneToMany` entre `Event` et `Reservation`, la manipulation de la collection pour l'algorithme d'exclusion a initialement provoqué des erreurs d'indexation.
* **Solution :** Utilisation des méthodes de collection Doctrine (`$reservations->last()`) et injection de l' `EntityManagerInterface` pour forcer la suppression physique des objets en base de données.

### 🎨 Problème : Perte de style sur les formulaires (FormTypes)
L'ajout de l' `EntityType` pour les catégories a réinitialisé le rendu HTML par défaut, cassant le design Tailwind.
* **Solution :** Personnalisation des classes CSS directement dans les fichiers `EventType.php` et `CategoryType.php` via l'attribut `'attr' => ['class' => '...']`.

### 🌍 Problème : Persistance de la langue
Changer de langue via l'URL (`_locale`) ne persistait pas toujours lors de la navigation entre les pages.
* **Solution :** Création d'un `LocaleController` dédié qui stocke explicitement la langue choisie dans la session de l'utilisateur.

---

## 4. Ce qui aurait pu être amélioré

* **Automatisation des tests :** Bien que le code soit propre, l'ajout de tests unitaires (PHPUnit) pour valider l'algorithme d'exclusion aurait permis de garantir une non-régression à 100%.
* **Notifications automatiques :** Actuellement, l'utilisateur exclu suite à une réduction de capacité est informé par l'administrateur. L'automatisation par envoi d'emails (Symfony Mailer) serait une évolution logique.
* **Tableau de bord statistique :** Ajouter des graphiques (Chart.js) pour visualiser le taux de remplissage par catégorie en un coup d'œil.

---

## 5. Conclusion
Le projet SecurEvent m'a permis de passer d'une compréhension théorique de Symfony à une maîtrise pratique de l'écosystème (Services, Sécurité, Twig, Doctrine). La contrainte du cahier des charges sur la sécurité a été un moteur pour approfondir mes connaissances sur la protection des données et la gestion des rôles. Je suis fier de livrer une application complète, esthétique et sécurisée.