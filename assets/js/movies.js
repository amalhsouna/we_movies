/*
 * Welcome to your app's movies JavaScript file!
 */

document.addEventListener('DOMContentLoaded', () => {
    const genreCheckboxes = document.querySelectorAll('.genre-checkbox');
    const movieList = document.getElementById('movies-list');
    const genreForm = document.getElementById('genre-form');
    const searchInput = document.getElementById('movie-search');
    const searchResults = document.getElementById('search-results');

    // Fonction pour mettre à jour la liste des films
    const updateMovieList = (movies) => {
        movieList.innerHTML = ''; // Réinitialise la liste
        movies.forEach(movie => {
            const movieItem = document.createElement('div');
            movieItem.classList.add('col-md-4');
            movieItem.innerHTML = `
                <div class="card">
                    <img src="https://image.tmdb.org/t/p/w500/${movie.poster_path}" class="card-img-top" alt="${movie.title}">
                    <div class="card-body">
                        <h5 class="card-title">${movie.title}</h5>
                        <p class="card-text">${movie.overview}</p>
                        <p class="card-text">
                            ${'★'.repeat(Math.round(movie.vote_average / 2))}
                            ${'☆'.repeat(5 - Math.round(movie.vote_average / 2))}
                        </p>
                    </div>
                </div>
            `;
            movieList.appendChild(movieItem);
        });
    };

    // Fonction pour récupérer les films en fonction des genres sélectionnés
    const fetchMoviesByGenres = () => {
        const selectedGenres = [];
        genreCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedGenres.push(checkbox.value);
            }
        });
    
        if (selectedGenres.length > 0) {
            const body = new URLSearchParams();
            selectedGenres.forEach(genre => {
                body.append('genres[]', genre); // Ajoute chaque genre au corps
            });
    
            fetch('/movies/filter', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: body
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(updateMovieList)
            .catch(error => {
                console.error('Error fetching movies:', error);
                movieList.innerHTML = '<p>Error retrieving movies</p>'; // Afficher un message d'erreur
            });
        } else {
            movieList.innerHTML = '<p>Aucun genre sélectionné.</p>'; // Afficher un message si aucune checkbox n'est cochée
        }
    };    

    // Événement sur le changement des checkboxes
    genreForm.addEventListener('change', fetchMoviesByGenres);

    // Événement sur la saisie dans le champ de recherche
    searchInput.addEventListener('input', () => {
        const query = searchInput.value;

        if (query.length < 3) {
            searchResults.innerHTML = ''; // Clear results if input is less than 3 characters
            searchResults.style.display = 'none';
            return;
        }

        fetch(`/movies/search?query=${encodeURIComponent(query)}`, {
            method: 'GET',
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(movies => {
            searchResults.innerHTML = ''; // Clear previous results
            if (!Array.isArray(movies) || movies.length === 0) {
                searchResults.style.display = 'none';
                return; // Hide results if no movies found
            }

            movies.forEach(movie => {
                const resultItem = document.createElement('div');
                resultItem.innerHTML = `
                    <strong>${movie.title}</strong> (${movie.release_date ? movie.release_date.split('-')[0] : 'N/A'})
                `;
                resultItem.addEventListener('click', () => {
                    searchInput.value = movie.title; // Set input value to the selected movie title
                    searchResults.innerHTML = ''; // Clear results
                    searchResults.style.display = 'none'; // Hide results
                    // Optionally, trigger a function to display movie details
                });
                searchResults.appendChild(resultItem);
            });

            searchResults.style.display = 'block'; // Show results
        })
        .catch(error => {
            console.error('Error:', error);
            searchResults.innerHTML = '<p>Error retrieving movies</p>'; // Display error message
            searchResults.style.display = 'block'; // Show error message
        });
    });
});
