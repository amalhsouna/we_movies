document.addEventListener('DOMContentLoaded', () => {
    const genreCheckboxes = document.querySelectorAll('.genre-checkbox');
    const movieList = document.getElementById('movies-list');
    const genreForm = document.getElementById('genre-form');
    const searchInput = document.getElementById('movie-search');
    const searchResults = document.getElementById('search-results');

    // Fetch movies based on selected genres
    const fetchMoviesByGenres = (page = 1) => {
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
    
            fetch(`/movies/filter?page=${page}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: body
            })
            .then(response => response.json())
            .then(moviesData => {
                updateMovieList(moviesData); // Update the movie list with the fetched data
            })
            .catch(error => {
                console.error('Error fetching movies:', error);
                movieList.innerHTML = '<p>Error retrieving movies</p>';
            });
        } else {
            movieList.innerHTML = '<p>No genre selected.</p>'; // Display message if no genre is selected
        }
    };

    // Pagination function
    const paginate = (totalPages, currentPage) => {
        const paginationContainer = document.getElementById('pagination');
        paginationContainer.innerHTML = '';
    
        // Previous button
        if (currentPage > 1) {
            const prevButton = document.createElement('button');
            prevButton.textContent = 'Previous';
            prevButton.classList.add('page-btn');
            prevButton.addEventListener('click', () => {
                fetchMoviesByGenres(currentPage - 1); // Fetch previous page
            });
            paginationContainer.appendChild(prevButton);
        }
    
        // Display page numbers (maximum 3 pages)
        const startPage = Math.max(1, currentPage - 1);
        const endPage = Math.min(totalPages, currentPage + 1);
    
        for (let page = startPage; page <= endPage; page++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = page;
            pageButton.classList.add('page-btn');
            if (page === currentPage) {
                pageButton.classList.add('active'); // Highlight current page
            }
            pageButton.addEventListener('click', () => {
                fetchMoviesByGenres(page); // Fetch the selected page
            });
            paginationContainer.appendChild(pageButton);
        }
    
        // Next button
        if (currentPage < totalPages) {
            const nextButton = document.createElement('button');
            nextButton.textContent = 'Next';
            nextButton.classList.add('page-btn');
            nextButton.addEventListener('click', () => {
                fetchMoviesByGenres(currentPage + 1); // Fetch next page
            });
            paginationContainer.appendChild(nextButton);
        }
    };    

    // Update movie list
    const updateMovieList = (moviesData) => {
        const { movies, total_pages, current_page } = moviesData;

        movieList.innerHTML = ''; // Clear existing movie list
        movies.forEach(movie => {
            const movieItem = document.createElement('div');
            movieItem.classList.add('col-md-12');
            movieItem.innerHTML = `
            <div class="card d-flex flex-row align-items-start">
                <img src="https://image.tmdb.org/t/p/w500/${movie.poster_path}" class="card-img-left img-fluid movie-poster" alt="${movie.title}">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-left col-9">
                            <h5 class="card-title mb-0">${movie.title}</h5>
                            <p class="card-text mb-0 stars">
                                ${'★'.repeat(Math.round(movie.vote_average / 2))}
                                ${'☆'.repeat(5 - Math.round(movie.vote_average / 2))}
                            </p>
                            <p class="rate mt-1">(${movie.vote_count} votes)</p>
                        </div>
                        <p class="release-date">${new Date(movie.release_date).getFullYear()}</p>
                    </div>
                    <p class="card-text description">${movie.overview}</p>
                    <div class="d-flex justify-content-end">
                <button class="details-btn btn btn-primary" data-title="${movie.title}" data-description="${movie.overview}" data-id="${movie.id}"
                 data-vote="${ movie.vote_count }" data-rate="${ movie.vote_average }" data-star="${'★'.repeat(Math.round(movie.vote_average / 2))}
                ${'☆'.repeat(5 - Math.round(movie.vote_average / 2))}" data-video="${movie.video_url}">
                    Lire le details
                </button>
               </div>
                </div>
            </div>`;
            movieList.appendChild(movieItem); // Append movie item to the list
        });

        paginate(total_pages, current_page); // Update pagination
    };

    // Event listener for search input
    searchInput.addEventListener('input', () => {
        const query = searchInput.value;

        if (query.length < 3) {
            searchResults.innerHTML = ''; 
            searchResults.style.display = 'none';
            fetchMoviesByGenres(); // Reload full list
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
        .then(moviesData => {
            const { movies } = moviesData;

            searchResults.innerHTML = ''; // Clear previous results
            if (!Array.isArray(movies) || movies.length === 0) {
                searchResults.style.display = 'none';
                return; 
            }

            // Update the displayed movie list
            updateMovieList(moviesData);

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
                        if (result) {
                            movie.video_url = result; // Update movie video URL
                            updateMovieList([movie]); // Update display with selected movie and video
                        }
                    });
                });

                searchResults.appendChild(resultItem); // Append search results
            });

            searchResults.style.display = 'block'; // Show results box
        })
        .catch(error => {
            console.error('Error:', error);
            searchResults.innerHTML = '<p>Error retrieving movies</p>';
            searchResults.style.display = 'block'; 
        });
    });

    // Event listener for genre checkboxes
    genreCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            fetchMoviesByGenres(); // Update movie list when a checkbox changes
        });
    });

    // Default genre checkbox selection
    const defaultGenreCheckbox = document.getElementById('genre-28'); 
    if (defaultGenreCheckbox) {
        defaultGenreCheckbox.checked = true;
        fetchMoviesByGenres(); 
    }

    // Modal js
    let modal = document.getElementById("popup-modal");

    let span = document.getElementsByClassName("close")[0];

    // Get the modal elements to populate
    let modalTitle = document.getElementById("modal-title");
    let modalDescription = document.getElementById("modal-description");
    let modalStars = document.getElementById("modal-stars");
    let modalVideo = document.getElementById("modal-video"); // Get video URL
    let modalVote = document.getElementById("modal-vote-count");
    let modalRate = document.getElementById("modal-vote-rate");  

    // Event delegation for dynamically added buttons
    movieList.addEventListener('click', (event) => {
        if (event.target.classList.contains("details-btn")) {
            const movieTitle = event.target.getAttribute("data-title");
            const movieDescription = event.target.getAttribute("data-description");
            const movieEtoile = event.target.getAttribute("data-star");
            const movieVideo = event.target.getAttribute("data-video");
            const voteCount = event.target.getAttribute("data-vote");
            const voteRate = event.target.getAttribute("data-rate");

            // Set the movie data in the modal
            modalTitle.textContent = movieTitle;
            modalDescription.textContent = movieDescription;
            modalStars.textContent = movieEtoile;
            modalVote.textContent = 'pour ' + voteCount + ' utilisateur';
            modalRate.textContent = voteRate;
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
