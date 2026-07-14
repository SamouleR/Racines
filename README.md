# Racines - Projet Web

Bienvenue sur le dépôt du projet Web **Racines**. Ce projet est composé d'une structure classique en PHP, HTML, CSS et JavaScript pour la gestion d'événements, la présentation de l'agence, des traditions, d'un calendrier et d'une newsletter.

## 📂 Structure du Projet

- **Pages HTML/PHP** : `index.php`, `agence.html`, `calendrier.php`, `contact.php`, `evenements.php`, `traditions.php`, etc.
- **Styles** : `style.css`, `style_form.css`
- **Scripts et API internes** : `get_events.php`, `get_comments.php`, `ajouter_evenements.php`, `save_content.php`, `unsubscribe.php`
- **Dossiers** :
  - `/admin` : Espace d'administration
  - `/img` & `/video` : Ressources multimédias
  - `/newsletter` : Gestion de la newsletter
  - `/structure` : Éléments de structure partagés
  - `/membre` : Espace membre

## ⚙️ Configuration Requise

- Un serveur web (Apache/Nginx) avec **PHP**.
- Une base de données **MySQL** ou MariaDB.
- Assurez-vous de configurer les accès à la base de données (présents dans des fichiers comme `unsubscribe.php`, `ajouter_evenements.php`, etc.) avec vos propres identifiants (actuellement configurés pour `u237218091_racine`).

## 🚀 Installation & Lancement

1. Clonez ce dépôt dans le dossier racine de votre serveur web (ex: `htdocs` ou `www`).
2. Importez la structure de la base de données si un script SQL est fourni.
3. Modifiez les identifiants de connexion MySQL dans les différents scripts PHP.
4. Accédez au site depuis votre navigateur via `http://localhost/votre-dossier`.
