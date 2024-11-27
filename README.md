# Laravel Video Processing App - Local Development Setup

## Setting Up the Project Locally with Laravel Sail

### Prerequisites
- Ensure you have **Docker** installed and running on your system.
- Clone the repository to your local environment.

### Steps to Set Up the Project

1. **Install Dependencies Using Composer**
   Run the following command to install the required PHP dependencies with a Docker-based Composer container:

   ```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php84-composer:latest \
       composer install --ignore-platform-reqs
   ```

2. **Set Up the Environment File**
   Copy the example `.env` file and adjust the configurations:

   ```bash
   cp .env.example .env
   ```

   Update your `.env` file with the following settings to match the Sail environment:
   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=root
   DB_PASSWORD=

   SESSION_DRIVER=redis
   QUEUE_CONNECTION=redis
   CACHE_STORE=redis
   REDIS_HOST=redis

   MAIL_MAILER=smtp
   MAIL_HOST=mailpit
   MAIL_PORT=1025
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_ENCRYPTION=null
   MAIL_FROM_ADDRESS="hello@example.com"
   MAIL_FROM_NAME="${APP_NAME}"
   ```

3. **Start the Sail Containers**
   Run the following command to start the Sail environment in detached mode:

   ```bash
   sail up -d
   ```

4. **Generate the Application Key**
   Use Artisan to generate a new application key:

   ```bash
   sail artisan key:generate
   ```

5. **Run Migrations**
   Set up the database schema by running migrations:

   ```bash
   sail artisan migrate
   ```

6. **Install Node Dependencies**
   Install the required Node.js dependencies using npm:

   ```bash
   sail npm install
   ```

7. **Run the Development Server**
   Start the Vite development server:

   ```bash
   sail npm run dev
   ```

   Leave this command running to enable hot-reloading during development.

8. **Access the Application**
   Open your browser and navigate to:

   ```
   http://localhost
   ```

   You should see the application running locally.
