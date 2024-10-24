# Movie Filter and Search Application

## Introduction

The Movie Filter and Search Application is a web-based application that allows users to search for movies, filter them by genre, and view detailed information about each movie. This project leverages the TMDB API for movie data and is built using Symfony5.4 and Webpack Encore.

## Features

- Filter movies by genre
- Search for movies by title
- View detailed information in a popup, including movie description and video trailer
- Responsive design using Bootstrap

## Requirements

- PHP >=7.2.5
- Symfony CLI
- Node.js >= 12.0
- Composer
- TMDB API key

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/amalhsouna/we_movies
   cd we_movies

2. **Install PHP dependencies**
   composer install

4. **Copy Environment File**
   cp .env.dist .env And Add your TMDB_API_KEY

   **(You must create your account in https://developer.themoviedb.org/docs/getting-started)

3. **Install JavaScript dependencies with Webpack Encore**
   npm install

   If you are using Yarn:
    yarn install

4. **Compile assets with Webpack Encore**
   npm run dev

5. **Start the Symfony server**
   symfony server:start

6. ** Running Tests
   To run the tests, ensure that all dependencies are installed, then execute the following command:

   ```bash
   php bin/phpunit

## Running CS Fixer

To automatically fix the code according to the PSR-12 coding standard, you can use PHP CS Fixer. Run the following command:

  vendor/bin/php-cs-fixer fix

##  Running Docker

docker-compose up -d and You can show the web application http://localhost:8000/


