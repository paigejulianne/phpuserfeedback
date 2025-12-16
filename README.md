# UserFeedback

A lightweight, modern feedback collection system written in pure PHP. It allows users to submit ideas, vote on features, discuss improvements, and track progress—similar to UserVoice or Canny.

![UserFeedback Screenshot](https://via.placeholder.com/800x400?text=UserFeedback+Dashboard)

## 🚀 Features

### Core Functionality
*   **Feedback Submission**: Users can submit feature requests or bug reports with categorization.
*   **Voting System**: AJAX-powered upvoting mechanism to surface the most popular ideas.
*   **Discussion**: Threaded comments section for deep-diving into specific feedback items.
*   **Search & Filtering**:
    *   Full-text search for titles and descriptions.
    *   Sort by "Most Popular", "Newest", or "Oldest".

### User Management
*   **Authentication**: Secure Registration and Login system.
*   **Profile Management**: Users can update their username, email, and password.
*   **Password Recovery**: Secure "Forgot Password" flow with email links (via PHPMailer).

### Administration
*   **Admin Dashboard**: Dedicated panel for administrators to manage content.
*   **Status Workflow**: Update feedback status (e.g., *Open → In Progress → Completed*) to keep users informed.

## 🛠 Tech Stack

*   **Language**: PHP 8.0+
*   **Database**: MySQL / MariaDB
*   **Frontend**: Vanilla HTML/CSS (Custom "Glassmorphism" Design System), Vanilla JS.
*   **Dependencies**: `PHPMailer` (via Composer).

## 📥 Installation

### Prerequisites
*   PHP 8.0 or higher
*   MySQL or MariaDB
*   Composer
*   Apache or Nginx

### Quick Setup

1.  **Clone the repository**
    ```bash
    git clone https://github.com/yourusername/phpuserfeedback.git
    cd phpuserfeedback
    ```

2.  **Configuration**
    Copy the sample config file and edit it with your database and mail credentials.
    ```bash
    cp src/Config/config.sample.php src/Config/config.php
    nano src/Config/config.php
    ```

3.  **Run the Setup Script**
    This script will install dependencies, create the database, and seed the default admin user.
    ```bash
    chmod +x setup.sh
    ./setup.sh
    ```

### Manual Setup (If not using the script)
1.  Run `composer install`.
2.  Create a MySQL database.
3.  Import `sql/schema.sql` into your database.
4.  Update `src/Config/config.php`.

## 🌐 Web Server Configuration (Critical)

**Security Warning**: You must configure your web server to serve the application from the `/public` directory. Do not point your document root to the project root, as this may expose your configuration files and source code.

### Apache
Ensure `mod_rewrite` is enabled. Point your `DocumentRoot` to `/path/to/phpuserfeedback/public`.

### Nginx Example
```nginx
server {
    listen 80;
    server_name example.com;
    root /var/www/phpuserfeedback/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }
}
```

## 👤 Default Credentials

If you used the setup script, a default admin account is created:
*   **Username**: `admin`
*   **Password**: `admin`

*Please change this password immediately after logging in.*

## 📄 License

GPL 2.0

