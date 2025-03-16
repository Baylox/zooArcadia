
# 🐘 Zoo Arcadia
Zoo Arcadia est une application web développée dans le cadre d'un projet fictif, dédié à la gestion complète d'un zoo. Ce projet permet aux utilisateurs d'explorer des informations détaillées sur les animaux, leurs habitats naturels, ainsi que les services proposés par le zoo, comme les visites guidées et les restaurants.

Conçu pour des objectifs pédagogiques, ce projet a été pensé pour offrir une gestion centralisée des informations sur les animaux, les espèces, les habitats et les services. Bien que le zoo soit fictif, l'application simule de manière réaliste la gestion d'un zoo moderne, permettant de démontrer les compétences en développement web.

**🌍 Objectifs du projet :**
- Offrir une plateforme complète pour la gestion des animaux, habitats, espèces et services du zoo.
- Permettre aux visiteurs de laisser des avis et consulter les services disponibles.
- Créer un système sécurisé de gestion des rôles et des permissions pour les administrateurs et les employés du zoo.
- Fournir une interface front-end responsive et adaptée aux appareils mobiles, grâce à l'utilisation de Bootstrap et SCSS.

**🚀 Principales fonctionnalités :**
- Gestion des animaux et de leurs espèces.
- Visualisation des habitats et des informations associées.
- Gestion des services proposés aux visiteurs (visites guidées, restaurant, etc.).
- Système d'avis des visiteurs, modéré par les employés.
- Interface d'administration permettant de gérer facilement les entités du zoo (animaux, services, avis, etc.).
## Tech Stack

**Client (Front-End) :**  
- **Twig** *(Pour le moteur de templates Symfony)*

- **Bootstrap** / **SCSS** *(Pour le design et la mise en page responsive)*
- **Webpack Encore** *(Pour la gestion des assets)*

**Server (Back-End) :**  
- **Symfony 6.4 (LTS)** *(Framework PHP)* 
- **Doctrine ORM** *(Pour la gestion de la base de données)*

**Base de données :**  
- **MySQL** *(Pour les données relationnelles)*
- **MongoDB** *(Pour les données NoSQL, via MongoDB Atlas)*

**Outils de développement :**  
- **Composer** *(Gestion des dépendances PHP)*
- **npm** / **Webpack Encore** *(Pour la gestion des assets front-end)*


## Prérequis

Avant de pouvoir lancer le projet en local, assurez-vous d'avoir les outils suivants installés sur votre machine :

- **PHP 7.4+** : Symfony nécessite PHP pour fonctionner. Assurez-vous d'avoir une version compatible de PHP installée.
- **Composer** : Outil de gestion des dépendances PHP, utilisé pour installer les dépendances du projet.
  - Installation de Composer : [https://getcomposer.org/download/](https://getcomposer.org/download/)
- **Node.js** et **npm** : Utilisés pour gérer les dépendances front-end et compiler les fichiers avec Webpack Encore.
  - Installation de Node.js : [https://nodejs.org/](https://nodejs.org/)
- **MySQL** : Base de données relationnelle utilisée pour le projet.
  - Installation de MySQL : [https://dev.mysql.com/downloads/](https://dev.mysql.com/downloads/)
- **MongoDB** : Base de données NoSQL utilisée pour certaines parties du projet.
  - Installation de MongoDB : [https://www.mongodb.com/try/download/community](https://www.mongodb.com/try/download/community) 
- **Symfony CLI** (optionnel) : Utilisé pour faciliter le développement avec Symfony (serveur local, commandes Symfony, etc.).
  - Installation de Symfony : [https://symfony.com/download](https://symfony.com/download/) 

---

Assurez-vous également que votre serveur de base de données (MySQL et MongoDB) est démarré et que vous avez accès à ces services avant de lancer l'application.

# Installation
---
---
### 🔽 Cloner le projet

```bash
git clone https://github.com/Baylox/zooArcadia.git
```

Aller dans le répertoire du projet
```bash
cd zooArcadia
```

### 🔧 Installer les dépendances PHP 


```bash
composer install
```


### 🔧  Installez les dépendances front-end via npm 

```bash
npm install
```
Cela installera toutes les dépendances listées dans le fichier package.json, y compris Webpack Encore et d'autres librairies front-end.

### Configurer Webpack Encore
Une fois les dépendances installées, vous pouvez compiler les assets avec Webpack Encore. Voici la commande pour générer les fichiers optimisés pour la production :

```bash
npm run build
```
**Cela génère les fichiers compilés et minifiés dans le répertoire public/build/, qui seront utilisés par Symfony pour servir les assets dans les pages.**

⚠️ *Si vous êtes en environnement de développement* et que vous souhaitez une compilation plus rapide (sans optimisation), vous pouvez utiliser la commande suivante :

```bash
npm run dev
```
# Lancer un serveur de développement
Deux options s'offrent à vous pour exécuter le projet :

1️⃣ **Utiliser Docker** (Recommandé) – Cette méthode vous permet de bénéficier d’un environnement préconfiguré où tout est déjà prêt : base de données, serveur web, et autres dépendances essentielles.  
2️⃣ **Lancer Symfony directement** – Si vous avez déjà une configuration locale avec PHP, une base de données et les extensions nécessaires, vous pouvez démarrer le projet sans Docker.


## 1️⃣ Lancer le serveur Docker 
Assurez-vous que Docker est en cours d'exécution.

Lancez votre stack Docker :
```bash
docker-compose up -d
```
✅ Cela démarre tous les services (base de données, serveur web, etc.) en arrière-plan.

Vérifiez que les conteneurs sont bien en cours d'exécution :
```bash
docker ps
```
🎯 Si tout est correct, vous devriez voir une liste des conteneurs actifs.

### ⚠️ Arrêter le serveur Docker

Pour arrêter les conteneurs Docker, utilisez :
```bash
docker-compose down
```
💡 Cette commande arrête et supprime les conteneurs sans affecter les données persistantes.

Si vous voulez juste arrêter sans supprimer, utilisez :

```bash
docker-compose stop
```

## 2️⃣ Lancer le serveur Symfony 
Pour démarrer le serveur Symfony, utilisez la commande suivante :

```bash
symfony server:start
```

Cela démarrera le serveur de développement. Vous pouvez accéder à l'application via votre navigateur à l'adresse 
http://127.0.0.1:8000 ou http://localhost:8000.

**Note :** Symfony vous indiquera l'adresse exacte du serveur lorsqu'il sera démarré, mais en général, il fonctionne sur 127.0.0.1 (ou localhost) sur le port 8000.

Vous pouvez arrêter le serveur avec la commande suivante : 
```bash
symfony server:stop
```


## 🗃️ Base de données 

Ce projet ne comprend pas de migrations Doctrine. Afin de séparer les environnements de développement et de production et pour des raisons de sécurité, les données réelles de la base de données ne sont pas partagées.

📂 **Où sont les fichiers SQL ?**

Les fichiers nécessaires à la création des tables SQL sont disponibles dans le dossier suivant :

📂 scripts

    ├── nosql.js 
    └── schema.sql

Des données de test fictives ont été créées et sont présentes dans le dossier App.Fixtures. Elles sont uniquement destinées à vérifier le bon fonctionnement de l'application pendant le développement. Les données réelles ne sont pas partagées pour éviter tout risque lié à leur divulgation ou utilisation incorrecte dans un environnement de test. 

**De facto si vous êtes un examinateur, vous n'avez pas à vous soucier de cette partie. Les fichiers SQL nécessaires à la création des tables seront fournis séparément aux examinateurs. Une fois la base de données configurée et les tables créées, vous pourrez charger les véritables données.**

##  🔄 Lancer les Fixtures **(Pour le développement)**
Une fois que la base de données est prête, vous pouvez charger les données de test en exécutant la commande suivante dans le terminal à partir de la racine du projet 

```bash
php bin/console doctrine:fixtures:load
```
Cela peuplera la base de données avec les fausses données de test définies dans les fixtures. Si vous souhaitez réinitialiser la base de données avant de charger les fixtures, vous pouvez utiliser l'option --no-interaction pour éviter toute confirmation :

```bash
php bin/console doctrine:fixtures:load --no-interaction
```
## ⚡Exécuter les Tests

Pour exécuter tous les tests unitaires actuels, vous pouvez utiliser la commande suivante :

```bash
php bin/phpunit
```
