# Laravel Video Processing App: Real-Time Video Management with TDD and Dockerized Workflow

Welcome to the **Laravel Video Processing Application**, a modern, feature-rich application designed for video management and processing. This application utilizes **Laravel Jetstream**, **Inertia.js**, **Vue 3**, **TailwindCSS**, and other modern tools to provide a seamless development and user experience.

## Features

- **Video Processing Pipeline**: Includes metadata extraction and user notifications.
- **Real-Time Updates**: Achieved via WebSockets powered by Laravel Reverb.
- **Advanced UI**: Built with **TailwindCSS** and **Vue.js**, ensuring responsiveness and a modern design.
- **RESTful API**: Supports programmatic access to comments.
- **TDD with Pest**: Built using Test-Driven Development principles for robustness.
- **VILT Stack**: Combines **Vue.js**, **Inertia.js**, **Laravel**, and **TailwindCSS**.

---

## Technologies Used

### Backend
- **Laravel**: A powerful PHP framework that simplifies web development.
- **Jetstream**: Provides authentication, session management, and scaffolding.
- **Reverb**: For real-time broadcasting via WebSockets.
- **Sanctum**: Handles API authentication using personal access tokens.
- **Redis**: Caches sessions, queues, and application data.

### Frontend
- **Vue 3**: A progressive JavaScript framework for building interactive UIs.
- **Inertia.js**: Bridges Laravel and Vue for SPA-like development.
- **TailwindCSS**: A utility-first CSS framework for fast and modern design.

### Testing
- **PestPHP**: Modern, elegant testing framework for Laravel, ensuring code quality through TDD principles.
- **PHPUnit**: Supports backend testing and integration with PestPHP.

---

## Development Environment Setup

### Prerequisites
- **Docker**: Ensure Docker is installed and running on your system.
- **Node.js**: Required for frontend asset management.
- **Composer**: To manage PHP dependencies.

### Setting Up the Project Locally

1. **Clone the Repository**:
   ```bash
   git clone <repository-url>
   cd <repository-directory>
   ```

2. **Install PHP Dependencies**:
   ```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php84-composer:latest \
       composer install --ignore-platform-reqs
   ```

3. **Configure Environment**:
   ```bash
   cp .env.example .env
   ```
   Update `.env` with Sail-specific configurations (see below).

4. **Start the Sail Environment**:
   ```bash
   ./vendor/bin/sail up -d
   ```

5. **Generate Application Key**:
   ```bash
   sail artisan key:generate
   ```

6. **Run Migrations**:
   ```bash
   sail artisan migrate
   ```

7. **Install Node Dependencies**:
   ```bash
   sail npm install
   ```

8. **Run Development Server**:
   ```bash
   sail npm run dev
   ```

### Environment Configuration
```dotenv
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

REDIS_HOST=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Testing with Pest

### Running Tests
This project follows **TDD (Test-Driven Development)** practices using **PestPHP**. To run the tests:
```bash
sail test
```

### Key Test Features
- **Unit Tests**: Validate models and relationships.
- **Feature Tests**: Test HTTP requests and application workflows.
- **Observers and Notifications**: Ensure correct behavior on video events.

---

## Key Components and File Structure

### Application Structure
The project follows a **modular structure** for maintainability:
- **Models**: Represent application entities (`Video`, `Comment`, `Tag`).
- **Controllers**: Manage HTTP requests (`VideoController`, `TagController`).
- **Observers**: Monitor model events (`VideoObserver`).
- **Jobs**: Handle asynchronous tasks (`ProcessVideoMetadata`, `SendVideoNotification`).
- **Policies**: Define access controls (`VideoPolicy`).

### Frontend
- **Components**: Located in `resources/js/Components`, reusable elements like `VideoCard` and `FormSection`.
- **Layouts**: Found in `resources/js/Layouts`, managing shared structure.
- **Pages**: Contain individual views (e.g., `Admin/Tags/Index.vue`).

---

## Enhancing Local Development with Supervisord

To streamline the development process and eliminate the need to run multiple commands in separate terminal windows, we have added a **Supervisord configuration file** (`docker/supervisord.conf`). This setup ensures that all essential processes for the application are automatically managed and executed within the Docker environment. 

### Why Supervisord?

**Supervisord** is a process control system that enables us to manage and monitor multiple processes in a Docker container. By using Supervisord, we avoid having to run critical Laravel and Node.js commands manually, simplifying the developer experience.

---

### Location of the Supervisord Configuration

The configuration file is located in:
```
docker/supervisord.conf
```

---

### Commands Managed by Supervisord

The following commands are managed via Supervisord:

1. **PHP Development Server**:
   ```bash
   php artisan serve
   ```
   Handles serving the Laravel application locally.

2. **Queue Worker**:
   ```bash
   php artisan queue:work
   ```
   Processes background jobs, such as sending notifications or processing video metadata.

3. **Specialized Queues**:
   - **Video Metadata Queue**:
     ```bash
     php artisan queue:work --queue=video-metadata
     ```
   - **Notification Queue**:
     ```bash
     php artisan queue:work --queue=send-notifications
     ```
   These queues allow task prioritization and avoid blocking critical application flows.

4. **Reverb WebSocket Server**:
   ```bash
   php artisan reverb:start --host="0.0.0.0" --port=8080 --no-interaction
   ```
   Powers real-time broadcasting for the application.

5. **Vite Development Server**:
   ```bash
   npm run dev
   ```
   Enables hot-reloading for front-end development.

---

## Development Highlights

1. **VILT Stack**: Combines Vue.js, Inertia.js, Laravel, and TailwindCSS for a modern development experience.
2. **API Integration**: Provides RESTful APIs with Sanctum for authentication.
3. **Real-Time Features**: Uses Pusher for notifications and Redis queues for background tasks.
4. **Dockerized Setup**: Runs on Docker using Sail, simplifying environment management.

---

## Additional Notes

### Tailwind CSS
The application uses **TailwindCSS** for building modern, utility-first designs. Customizations are managed in `tailwind.config.js`.

### Video Processing
- **Metadata Extraction**: Automatically processes video files using FFmpeg.
- **Notifications**: Sends user notifications when a video is published.


