const Encore = require('@symfony/webpack-encore');

// Configure l'environnement d'exécution (développement ou production)
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // Répertoire où les assets compilés seront stockés
    .setOutputPath('public/build/')

    // Chemin public utilisé par le serveur pour accéder aux assets compilés
    .setPublicPath('/build')

    // Point d'entrée principal pour votre application (JS et SCSS)
    .addEntry('app', './assets/app.js')

    // Divise les fichiers en morceaux plus petits pour optimiser le chargement
    .splitEntryChunks()

    // Utilise un seul fichier runtime.js pour les dépendances partagées
    .enableSingleRuntimeChunk()

    // Nettoie le répertoire `public/build/` avant chaque build
    .cleanupOutputBeforeBuild()

    // Affiche des notifications après chaque build (utile pour le développement)
    .enableBuildNotifications()

    // Ajoute un hash aux noms de fichiers en production pour gérer le cache
    .enableVersioning(Encore.isProduction())
    
    // Désactive la génération de source maps en production
    .enableSourceMaps(false)

    // Configure Babel pour gérer les fonctionnalités modernes de JavaScript
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage'; // Charge uniquement les polyfills nécessaires
        config.corejs = '3.38'; // Utilise CoreJS version 3.38 pour les polyfills
    })

    // Active le support des fichiers SCSS
    .enableSassLoader((options) => {
        options.sassOptions = {
            outputStyle: 'expanded',
        };
    }, {
        resolveUrlLoader: true, // Active resolve-url-loader
    })
    .copyFiles({
        from: './assets/images', // Copier toutes les images
        to: 'images/[path][name].[ext]', // Les placer dans 'public/build/images'
    });
    
    // Ajoute d'autres fonctionnalités si nécessaire (par exemple jQuery)
    //.autoProvidejQuery()

    Encore.copyFiles({
        from: './assets/images', // Source des images
        to: 'images/[path][name].[ext]', // Destination des images dans 'public/build'
    });    
;

module.exports = Encore.getWebpackConfig();

