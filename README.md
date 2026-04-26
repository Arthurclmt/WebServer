# 🎮 Asso Gaming Platform

Plateforme numérique intelligente de gestion d'une association de jeux vidéo.  
Projet Web ING1 2025-2026 — CY Tech.

---

## 📋 Présentation

Cette plateforme centralise la gestion des objets connectés de l'association (consoles, PCs, bornes d'arcade, équipements réseau) et propose des services adaptés aux différents membres. Elle est divisée en 4 modules selon le niveau de l'utilisateur.

---

## ⚙️ Stack technique

| Technologie | Rôle |
|---|---|
| Laravel | Framework PHP backend |
| Bootstrap 5 | Framework CSS / responsive |
| MySQL | Base de données |
| Blade | Moteur de templates |
| Mailtrap | Envoi de mails (dev) |

---

## 🚀 Installation

### Prérequis

```bash
sudo apt install php php-cli php-mbstring php-xml php-curl php-mysql unzip
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/bin --filename=composer
sudo apt install mysql-server
sudo service mysql start
```

### Cloner le projet

```bash
git clone https://github.com/Arthurclmt/WebServer.git
cd WebServer
```

### Installer les dépendances

```bash
composer install
```

### Configurer le .env

```bash
cp .env.example .env
php artisan key:generate
```

Modifier le `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=asso_gaming
DB_USERNAME=root
DB_PASSWORD=Asso@2026
```

### Créer la base de données MySQL

```bash
sudo mysql --defaults-file=/etc/mysql/debian.cnf
```

```sql
CREATE DATABASE asso_gaming;
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'Asso@2026';
FLUSH PRIVILEGES;
exit
```

### Lancer les migrations et les seeders

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### Lancer le serveur

```bash
php artisan serve
```

Ouvrir **http://localhost:8000** dans le navigateur.

---

## 👤 Comptes de test

| Rôle | Email | Mot de passe |
|---|---|---|
| Admin | admin@test.com | (voir UserSeeder) |
| User | user@test.com | (voir UserSeeder) |

---

## 📁 Structure du projet

```
asso-gaming/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # AuthController, AppareilController...
│   │   └── Middleware/         # CheckRole, CheckLevel
│   └── Models/                 # User, Appareil, Room, News, Event...
├── database/
│   ├── migrations/             # Structure des tables
│   └── seeders/                # Données de test
├── public/
│   ├── css/custom.css          # Styles personnalisés
│   └── storage/                # Images uploadées
├── resources/views/
│   ├── layouts/app.blade.php   # Template Bootstrap commun
│   ├── appareil/               # Vues objets connectés
│   ├── auth/                   # Login, Register
│   ├── events/                 # Vues événements
│   ├── news/                   # Vues actualités
│   └── admin/                  # Vues administration
└── routes/web.php              # Toutes les routes
```

---

## 🧩 Modules

### Module Information (Visiteur)
- Page d'accueil publique
- Actualités et événements de l'asso
- Recherche avec filtres

### Module Visualisation (Utilisateur simple)
- Inscription / Connexion
- Gestion du profil
- Consultation des objets connectés
- Système de points et niveaux

### Module Gestion (Utilisateur complexe)
- Gestion des objets connectés (CRUD)
- Configuration des paramètres
- Export CSV des données
- Rapports d'utilisation

### Module Administration (Admin)
- Gestion des utilisateurs
- Gestion de la whitelist
- Supervision globale de la plateforme

---

## 🔄 Workflow Git

```bash
# Récupérer les dernières modifications
git pull origin main

# Travailler sur sa branche
git checkout <ta-branche>
git merge main

# Envoyer son travail
git add .
git commit -m "description"
git push -u origin <ta-branche>
```

Puis créer une **Pull Request** sur GitHub pour merger dans `main`.

---

## 👥 Équipe

Projet réalisé par 5 étudiants ING1 — CY Tech 2025-2026.
