# copyFeeds

# PHP CLI Tool for Copying Data from PostgreSQL Database

This project provides a PHP command-line tool designed to copy specific data entries from a PostgreSQL database,
including related records from various source tables and associated posts. The tool is fully Dockerized, making it easy
to set up and run without needing a local PHP installation.

## Project Structure

The project consists of a single PHP script file and a Docker setup. Here's the key file and its purpose:

- **`copy`**: The main PHP script that performs the data copying operation.

## Prerequisites

Make sure you have the following installed on your machine:

- Docker
- Docker Compose

## Setup and Installation

1. **Clone the Repository:**

   Clone the project repository to your local machine:

   ```bash
   git clone https://github.com/yourusername/project-name.git
   cd project-name

2. **Build and Start Docker Containers:**
    ```bash
   docker-compose up --build


3. **Test the command:**
    ```bash
    docker-compose run app copy 1
    docker-compose run app copy --only=instagram 1
    docker-compose run app copy --only=instagram --include-posts=5 1


4. **Run phpunit:**
   ```bash
     docker-compose run --rm phpunit


### what can be improved
* currently once we feed tables with a specific option we cannot feed the same id again : can be refresh option or just make it possible to recreate the data
* we should have .env file instead of hardcoded variables in docker-compose.yml and in src/Database.php
* improve the way we handle the errors add try... catch, call $pdo->beginTransaction(), $pdo->commit() and $pdo->rollBack()
* for phpunit it's better to have another DB so other container (dev_db_test) at least for dev_db
* for phpunit tests we can have provider() in which we can provide all cases, it's easy to read in test files
* for phpunit can use tearDow() to truncate data everytime a test is run or use Illuminate\Foundation\Testing\RefreshDatabase; or createMock()