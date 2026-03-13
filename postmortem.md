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
Lors de la mise en place de la relation `OneToMany` entre `Event` et `Reservation`, la manipulation de la collection pour