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

3. **Install JavaScript dependencies with Webpack Encore**
   npm install

   If you are using Yarn:
    yarn install

4. **Compile assets with Webpack Encore**
   npm run dev

5. **Start the Symfony server**
   symfony server:start