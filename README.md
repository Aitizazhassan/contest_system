# Contest Participation System

This is a full-stack web application for a contest participation system, built with a Laravel backend and a Vue.js frontend. It allows users to register, participate in contests, and view leaderboards. It also includes a full admin panel for managing users, contests, and prizes.

## Key Features

-   **User Authentication:** Secure user registration and login using Laravel Sanctum.
-   **Role-Based Access Control:** Differentiated access levels for Admin, VIP, and Normal users.
-   **Contest Management:** Admins can create and manage contests with various question types (single-choice, multiple-choice, boolean).
-   **Contest Participation:** Users can view and participate in contests based on their access level.
-   **Automatic Scoring & Leaderboards:** Scores are calculated automatically upon submission, and leaderboards display rankings for each contest.
-   **Prize System:** A prize is automatically awarded to the winner of each contest upon its conclusion.
-   **User History:** Users can view their past contest participation and any prizes they have won.
-   **Full Admin Panel:** A dedicated interface for admins to manage users, contests, and view all awarded prizes.

## Technology Stack

-   **Backend:** Laravel 12
-   **Frontend:** Vue.js 3 with Vite
-   **State Management:** Pinia
-   **Styling:** Tailwind CSS
-   **Database:** MySQL (or any other Laravel-supported database)

---

## Project Setup and Installation

### Prerequisites

-   PHP >= 8.2
-   Composer
-   Node.js >= 18.0
-   NPM
-   A local database server (e.g., MySQL, MariaDB)

### Installation Steps

1.  **Clone the repository:**
    ```bash
    git clone <your-repository-url>
    cd contest_system
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Install JavaScript dependencies:**
    ```bash
    npm install
    ```

4.  **Create your environment file:**
    Copy the example `.env` file.
    ```bash
    cp .env.example .env
    ```

5.  **Configure your environment (`.env` file):**
    Open the `.env` file and update the database connection details (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

6.  **Generate the application key:**
    ```bash
    php artisan key:generate
    ```

7.  **Run database migrations and seeders:**
    This will set up the database schema and populate it with a default admin user and sample contests.
    ```bash
    php artisan migrate:fresh --seed
    ```

---

## Running the Application

You will need to run two processes in separate terminals to run the application.

1.  **Start the Laravel backend server:**
    ```bash
    php artisan serve
    ```
    By default, this will be available at `http://localhost:8000`.

2.  **Start the Vite frontend development server:**
    This will watch for changes in your Vue components and automatically recompile them.
    ```bash
    npm run dev
    ```

Once both servers are running, you can access the application in your browser at `http://localhost:8000`.

---

## Default Admin User

The database seeder creates a default admin user with the following credentials. You can use this user to access the Admin Panel and test administrative features.

-   **Email:** `admin@example.com`
-   **Password:** `password`

---

## API Documentation

A complete Postman collection for the API is available in the `docs` directory:

-   `docs/Contest Participation System API.postman_collection.json`

You can import this file into Postman to test all available API endpoints. The collection includes a `base_url` variable which is preset to `http://localhost:8000`. For authenticated routes, you will need to first log in via the `Auth > Login` request to get a bearer token.
