/*
 * Welcome to your movies's JavaScript file!
 */

document.addEventListener('DOMContentLoaded', () => {
    const genreCheckboxes = document.querySelectorAll('.genre-checkbox');
    const movieList = document.getElementById('movies-list');
    const genreForm = document.getElementById('genre-form');
    const searchInput = document.getElementById('movie-search');
    const searchResults = document.getElementById('search-results');

    // update list of movies
    const updateMovieList = (movies) => {
        movieList.innerHTML = '';
        movies.forEach(movie => {
            const movieItem = document.createElement('div');
            movieItem.classList.add('col-md-12');
            movieItem.innerHTML = `
                <div class="card d-flex flex-row align-items-start">
                    <img src="https://image.tmdb.org/t/p/w500/${movie.poster_path}" class="card-img-left img-fluid movie-poster" alt="${movie.title}">
                    <div class="card-body">
                        <h5 class="card-title">${movie.title}</h5>
                        <p class="card-text">${movie.overview}</p>
                        <p class="card-text">
                            ${'★'.repeat(Math.round(movie.vote_average / 2))}
                            ${'☆'.repeat(5 - Math.round(movie.vote_average / 2))}
                         <button class="details-btn btn btn-primary" data-title="${movie.title}" data-description="${movie.overview}" data-id="${movie.id}"
                         data-star="${'★'.repeat(Math.round(movie.vote_average / 2))}
                         ${'☆'.repeat(5 - Math.round(movie.vote_average / 2))}" data-video="${movie.video_url}"
                         >View Details</button>
                         </p>
                    </div>
                </div>
            `;
            movieList.appendChild(movieItem);
        });
    };

    // get movies by gender
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
                body.append('genres[]', genre); 
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
                movieList.innerHTML = '<p>Error retrieving movies</p>'; 
            });
        } else {
            movieList.innerHTML = '<p>Aucun genre sélectionné.</p>'; 
        }
    };    

    // Checkbox change event
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
                return; 
            }

            movies.forEach(movie => {
                const resultItem = document.createElement('div');
                resultItem.innerHTML = `
                    <strong>${movie.title}</strong> (${movie.release_date ? movie.release_date.split('-')[0] : 'N/A'})
                `;
                resultItem.addEventListener('click', () => {
                    searchInput.value = movie.title; 
                    searchResults.innerHTML = ''; 
                    searchResults.style.display = 'none';
                    fetch(`/movie/${movie.id}/videos`, {
                        method: 'GET',
                        
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(result => {
                        console.log(result);
                        if (result) {
                            movie.video_url = result
                            updateMovieList([movie]);    
                        }
                    });
                });
                searchResults.appendChild(resultItem);
            });

            searchResults.style.display = 'block'; 
        })
        .catch(error => {
            console.error('Error:', error);
            searchResults.innerHTML = '<p>Error retrieving movies</p>';
            searchResults.style.display = 'block'; 
        });
    });

    const defaultGenreCheckbox = document.getElementById('genre-28'); 
    if (defaultGenreCheckbox) {
        defaultGenreCheckbox.checked = true;
        fetchMoviesByGenres(); // Call to fetch movies based on the default genre
    }

    // Modal js
    let modal = document.getElementById("popup-modal");

    let span = document.getElementsByClassName("close")[0];

    // Get the modal elements to populate
    let modalTitle = document.getElementById("modal-title");
    let modalDescription = document.getElementById("modal-description");
    let modalStars = document.getElementById("modal-stars");
    let modalVideo = document.getElementById("modal-video"); // Récupérer l'URL de la vidéo

    // Event delegation for dynamically added buttons
    movieList.addEventListener('click', (event) => {
        if (event.target.classList.contains("details-btn")) {
            const movieTitle = event.target.getAttribute("data-title");
            const movieDescription = event.target.getAttribute("data-description");
            const movieEtoile = event.target.getAttribute("data-star");
            const movieVideo = event.target.getAttribute("data-video");

            // Set the movie data in the modal
            modalTitle.textContent = movieTitle;
            modalDescription.textContent = movieDescription;
            modalStars.textContent = movieEtoile;
            modalVideo.src = movieVideo;

            // Open the modal
            modal.style.display = "block";
        }
    });

    span.onclick = function() {
        modal.style.display = "none";
    };

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
     
});
