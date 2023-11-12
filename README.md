# Laravel Movie GraphQL API

This Laravel 10 project implements a GraphQL API for managing movies. The API allows fetching movie data from the OMDB API and storing it in the application's database, as well as updating existing movie records.

## Installation

1. **Clone the Repository**

    ```bash
    git clone https://github.com/kaliush/movies-api.git
    cd movies-api
    ```

2. **Set Up Environment File**

   Copy the `.env.example` file to a new `.env` file:

    ```bash
    cp .env.example .env
    ```

3. **Add OMDB API Key**

   Obtain an API key from [OMDB API](https://www.omdbapi.com/apikey.aspx) and add it to your `.env` file:

    ```
    OMDB_API_KEY=<your-omdb-api-key>
    ```

4. **Install Dependencies**

   Use Composer to install PHP dependencies:

    ```bash
    composer install
    ```

5. **Start Laravel Sail**

   Start the Docker containers using Laravel Sail:

    ```bash
    ./vendor/bin/sail up
    ```

6. **Run Migrations**

   Run database migrations:

    ```bash
    ./vendor/bin/sail artisan migrate
    ```

7. **Generate Application Key**

   Generate a new application key:

    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

## Using the GraphQL API

The GraphQL API is set up with the following main endpoints:

- `Query movies`: Fetch a list of movies based on various filters.
- `Mutation fetchMovies`: Fetch movie data from the OMDB API and store/update it in the database.
- `Mutation updateMovie`: Update details of an existing movie.

You can interact with the API using tools like GraphiQL 
```bash
localhost/graphiql
```

### Test Mutation Example

Use this mutation in GraphiQL to test fetching movies:

```graphql
mutation {
  fetchMovies(search: "Inception", type: movie, page: 1) {
    imdbID
    type
    released
    year
    poster
    genre
    runtime
    country
    imdbRating
    imdbVotes
    ratings {
      source
      value
    }
  }
}
```
## Running Tests

To run the PHPUnit tests:

```bash
./vendor/bin/sail artisan test
