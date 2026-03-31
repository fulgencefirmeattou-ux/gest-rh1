# 🏢 Gestion RH - Application de Gestion des Ressources Humaines

<p align="center">
  <strong>Plateforme complète de gestion des ressources humaines</strong><br>
  Construite avec Laravel 11 | PHP 8.2+ | MySQL
</p>

---

## 📋 Table des matières

- [À propos](#-à-propos)
- [Fonctionnalités](#-fonctionnalités)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Structure du projet](#-structure-du-projet)
- [Utilisation](#-utilisation)
- [Base de données](#-base-de-données)
- [Contribution](#-contribution)

---

## 📱 À propos

**Gestion RH** est une application web complète dédiée à la gestion des ressources humaines. Elle permet aux entreprises de gérer efficacement leurs employés, contrats, congés, paies, recrutements et bien d'autres aspects de la gestion RH.

Développée avec **Laravel**, ce framework PHP moderne offre une base solide, maintenable et sécurisée pour une application d'entreprise.

### Stack Technique

| Élément | Version | Description |
|---------|---------|-------------|
| **Framework** | Laravel 11 | Framework PHP moderne |
| **Langage** | PHP 8.2+ | Langage serveur |
| **Base de données** | MySQL/MariaDB | Système de gestion de BD relationnel |
| **Frontend** | Blade + JavaScript | Templating et interactivité |
| **Build** | Vite | Bundler de développement |
| **ORM** | Eloquent | Object-Relational Mapping |

---

## ✨ Fonctionnalités

### 👥 Gestion des Employés
- ✅ Création et gestion des profils employés
- ✅ Historique et évolution de carrière
- ✅ Documents et pièces jointes
- ✅ Photo de profil
- ✅ Gestion des droits d'accès

### 📋 Gestion Administrative
- ✅ Gestion des départements et services
- ✅ Gestion des contrats de travail
- ✅ Gestion des primes et bonus
- ✅ Historique des modifications

### 🏖️ Gestion des Congés et Absences
- ✅ Demandes de congés en ligne
- ✅ Historique des congés
- ✅ Gestion des absences
- ✅ Suivi des présences
- ✅ Approbation/rejet des demandes

### 💰 Gestion de la Paie
- ✅ Génération de bulletins de paie
- ✅ Gestion des éléments de bulletin (salaire, retenues, bonus)
- ✅ Export et archivage des bulletins
- ✅ Génération de PDF

### 🔍 Recrutement
- ✅ Gestion des offres d'emploi
- ✅ Suivi des candidatures
- ✅ Étapes d'évaluation
- ✅ Tests et évaluations
- ✅ Workflow de sélection

### 📊 Évaluations et Entretiens
- ✅ Évaluations de performances
- ✅ Entretiens d'appréciation
- ✅ Suivi du développement professionnel
- ✅ Rapport d'évaluation

### 📧 Communication
- ✅ Notifications aux employés
- ✅ Envoi de credentials de connexion
- ✅ Alertes administrateur
- ✅ Suivi des communications

---

## 🔧 Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés :

- **PHP 8.2** ou supérieur
- **Composer** (gestionnaire de dépendances PHP)
- **Node.js 18+** et **npm**
- **MySQL 8.0** ou **MariaDB 10.5+**
- **Git** (optionnel mais recommandé)

### Vérification des prérequis

```bash
# Vérifier PHP
php --version

# Vérifier Composer
composer --version

# Vérifier Node.js et npm
node --version
npm --version

# Vérifier MySQL (si disponible)
mysql --version
```

---

## 📥 Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/servicedevfac/app_grh.git
cd gestion_rh
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Installer les dépendances JavaScript

```bash
npm install
```

### 4. Créer le fichier `.env`

```bash
cp .env.example .env
```

### 5. Générer la clé d'application

```bash
php artisan key:generate
```

### 6. Exécuter les migrations

```bash
php artisan migrate
```

### 7. (Optionnel) Charger les données de démarrage

```bash
php artisan db:seed
```

---

## ⚙️ Configuration

### Base de données

Modifiez le fichier `.env` avec vos paramètres de base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_rh
DB_USERNAME=root
DB_PASSWORD=
```

### Mail (pour les notifications)

```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username
MAIL_PASSWORD=votre_password
MAIL_FROM_ADDRESS=no-reply@gestion-rh.com
```

### Application

```env
APP_NAME="Gestion RH"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

---

## 📁 Structure du Projet

```
gestion_rh/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Contrôleurs (logique métier)
│   │   └── Middleware/         # Middlewares (validation, auth)
│   ├── Models/                 # Modèles Eloquent
│   ├── Mail/                   # Classes d'email
│   └── Notifications/          # Classes de notification
├── config/                     # Fichiers de configuration
├── database/
│   ├── migrations/             # Migrations base de données
│   ├── seeders/                # Données de démarrage
│   └── factories/              # Factories pour tests
├── public/
│   ├── index.php               # Point d'entrée
│   └── assets/
├── resources/
│   ├── views/                  # Templates Blade
│   ├── css/                    # Feuilles de style
│   └── js/                     # Fichiers JavaScript
├── routes/
│   ├── web.php                 # Routes web
│   └── console.php             # Commandes console
├── storage/                    # Fichiers d'application
├── tests/                      # Tests automatisés
├── vendor/                     # Dépendances Composer
└── README.md                   # Ce fichier
```

Pour plus de détails, consultez `public/assets/css/structure.txt`.

---

## 🚀 Utilisation

### Lancer le serveur de développement

```bash
php artisan serve
```

Accédez à l'application via `http://localhost:8000`

### Compiler les assets (développement)

```bash
npm run dev
```

### Compiler les assets (production)

```bash
npm run build
```

### Lancer les tests

```bash
php artisan test
# ou
phpunit
```

### Commandes utiles

```bash
# Créer une migration
php artisan make:migration create_table_name

# Créer un contrôleur
php artisan make:controller NomController

# Créer un modèle
php artisan make:model NomModel -m

# Lister les routes
php artisan route:list

# Effacer le cache
php artisan cache:clear
php artisan config:clear
```

---

## 🗄️ Base de Données

### Modèles disponibles

- **User** : Utilisateurs de l'application
- **Employe** : Employés de l'entreprise
- **Departement** : Départements
- **Service** : Services
- **Contrat** : Contrats de travail
- **ContratPrime** : Primes associées aux contrats
- **DemandeConge** : Demandes de congés
- **HistoriqueConge** : Historique des congés
- **Absence** : Absences
- **Presence** : Présences
- **BulletinPaie** : Bulletins de paie
- **BulletinItem** : Éléments des bulletins
- **Recrutement** : Offres de recrutement
- **Candidature** : Candidatures
- **EtapeRecrutement** : Étapes du processus de recrutement
- **Evaluation** : Évaluations de performances
- **Entretien** : Entretiens d'appréciation

### Exécuter les migrations

```bash
# Appliquer toutes les migrations
php artisan migrate

# Annuler la dernière migration
php artisan migrate:rollback

# Réinitialiser la base de données
php artisan migrate:reset

# Réinitialiser et remplir avec des données
php artisan migrate:refresh --seed
```

---

## 👥 Contribution

Les contributions sont les bienvenues ! Pour contribuer :

1. **Fork** le projet
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. **Commit** vos changements (`git commit -m 'Add some AmazingFeature'`)
4. **Push** vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une **Pull Request**

### Standards de code

- Respecter les conventions de nommage Laravel
- Écrire des tests pour les nouvelles fonctionnalités
- Commenter le code complexe
- Utiliser PHP 8.2+ features quand approprié

---

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

---

## 📞 Support et Contact

Pour toute question ou problème :

- 📧 Email : charaadewale@attouco.com
- 🐛 Issues : [GitHub Issues](https://github.com/servicedevfac/app_grh/issues)
- 📖 Documentation : Consultez `public/assets/css/structure.txt`

---



**Dernière mise à jour** : 14 janvier 2026


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
