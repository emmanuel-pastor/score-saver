# Comment faire fonctionner ce projet sur votre machine ?

<br/>

**Note:** Le site est testable directement en ligne à <a href="https://www.ssa-apis.com" target="_blank" rel="noopener noreferrer">ssa-apis.com</a>

Un utilisateur de test existe déjà. **Adresse e-mail:** `user@test`. **Mot de passe:** `0000`.

<br/>

### 0- Décompresser le fichier .zip

### 1- Placer le projet dans le dossier de base de votre serveur local

Par exemple, placer le dossier `score-saver` du projet, dans le dossier `C:\wamp64\www` si vous utilsez WAMP

### 2- Créez une base de données pour le projet

Par exemple, créez une base de données nomée `score_saver` avec la commande SQL suivante :

	CREATE DATABASE score_saver;

**Note:** Les tables seront créées automatiquement par le site, vous n'avez rien à faire concernant les tables.

### 3- Modifiez les constantes du projet pour fonctionner avec votre configuration

- Ouvrez le fichier `constants.php` qui se trouve dans `score-saver/app/_config`

- Si vous avez bien placé le dossier `score-saver` dans le dossier de base de votre serveur, passez au point suivant.

Sinon, modifiez `BASE_PATH` en mettant après `HOST_NAME` le chemin qui permet d'arriver au dossier contenant index.php.

Par exemple, si vous avez placé le dossier `score-saver` dans `www/Modouv` alors changez:

	const BASE_PATH = 'http://' . HOST_NAME . '/score-saver/public/';
	
en 

	const BASE_PATH = 'http://' . HOST_NAME . '/Modouv/score-saver/public/';

**Attention:** veillez à bien commencer le chemin après `HOST_NAME` par un `/`. Par exemple écrivez bien `/Modouv/score-saver/public` et non `Modouv/score-saver/public`

- Modifiez `DB_NAME`, `DB_USER`, et `DB_PASSWORD` pour que les constantes correspondent à votre nom de base de données (BDD), votre nom d'utilisateur de votre BDD et le mot de passe de votre BDD.

Par exemple, si vous utilisez WAMP avec les paramètres par défaut, vous pouvez utiliser les valeurs suivantes:

	const DB_NAME = "score_saver";
	const DB_USER = "root";
	const DB_PASSWORD = "";

### 4- Modifiez les fichiers .htaccess

Si vous avez bien placé le dossier `score-saver` dans le dossier de base de votre serveur, passez à l'[étape 5](#5-lancez-le-site-en-tappant-le-base_path-dans-votre-navigateur).

- Sinon, modifiez le fichier `.htaccess` se trouvant dans `score-saver/public`. Changez la derniere ligne pour qu'elle corresponde au chemin menant au fichier `index.php`. 

Par exemple, si vous avez placé le dossier `score-saver` dans `www/Modouv`, changez:

	RewriteRule ^(.+)$ score-saver/public/index.php?url=$1 [QSA,L]

en

	RewriteRule ^(.+)$ Modouv/score-saver/public/index.php?url=$1 [QSA,L]

- Faîtes de même pour le fichier `.htaccess` de `score-saver`

Par exemple, si vous avez placé le dossier `score-saver` dans `www/Modouv`, changez:

	ErrorDocument 403 http://localhost/score-saver/public/403.php
	ErrorDocument 404 http://localhost/score-saver/public/404.php

en

	ErrorDocument 403 http://localhost/Modouv/score-saver/public/403.php
	ErrorDocument 404 http://localhost/Modouv/score-saver/public/404.php

### 5- Lancez le site en tappant le `BASE_PATH` dans votre navigateur

Par exemple, si vous avez suivi toutes les étapes comme il faut, tappez `http://localhost/score-saver/public` dans votre navigateur.

**Note:** Assurez vous de bien avoir lancé votre serveur local.