const Encore = require('@symfony/webpack-encore');

// Configure l'environnement d'exécution si ce n'est pas encore fait
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // Répertoire où les assets compilés seront stockés
    .setOutputPath('public/build/')
    // Chemin public utilisé par le serveur web pour accéder aux assets
    .setPublicPath('/build')

    /*
     * CONFIGURATION DES POINTS D'ENTRÉE
     *
     * Chaque point d'entrée produira un fichier JavaScript (ex. app.js)
     * et un fichier CSS (ex. app.css) si le JS importe du CSS.
     */
    .addEntry('app', './assets/app.js')

    // Active le découpage des fichiers pour une meilleure optimisation
    .splitEntryChunks()

    // Active un fichier runtime.js unique pour optimiser le cache
    .enableSingleRuntimeChunk()

    /*
     * CONFIGURATION DES FONCTIONNALITÉS
     *
     * Active et configure d'autres fonctionnalités si nécessaire.
     */
    .cleanupOutputBeforeBuild() // Nettoie le répertoire output avant chaque build
    .enableBuildNotifications() // Notifications sur les erreurs de build
    .enableSourceMaps(!Encore.isProduction()) // Génère des fichiers source map en mode dev
    .enableVersioning(Encore.isProduction()) // Active le versionnage des fichiers en production

    // Configure Babel pour la compatibilité avec les anciens navigateurs
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.38';
    })

    // Active le support de Sass/Scss
    // .enableSassLoader()

    // Ajoute le support de PostCSS (utile pour Tailwind CSS ou autres)
    .enablePostCssLoader()
;

// Exporte la configuration Webpack
module.exports = Encore.getWebpackConfig();
