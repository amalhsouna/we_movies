/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './movies'; // Importez votre fichier de script pour les films
import '../styles/app.css'; // Importez le CSS principal
import '../styles/movies.css'; // Importez le CSS spécifique aux films

// Example of a global event listener or functionality
document.addEventListener('DOMContentLoaded', () => {
    console.log('App is initialized');
});