# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.0] - 2025-12-16

### Added
- **Core Architecture**: implemented a lightweight MVC structure with custom Routing and Autoloading.
- **Authentication System**:
    - User Registration and Login.
    - Secure Session management.
    - Password hashing using `password_hash`.
    - "Forgot Password" flow using PHPMailer for email delivery.
- **Feedback Management**:
    - Submission form with category selection.
    - Dynamic Home feed with Feedback cards.
    - Detailed Feedback view (`/feedback/view`).
    - Admin Dashboard (`/admin`) for managing feedback status.
- **Interaction Features**:
    - **Voting**: AJAX-powered upvote/toggle system.
    - **Commenting**: Threaded discussion on feedback items.
- **Search & Discovery**:
    - Search bar for filtering ideas by title/description.
    - Sorting options: Popular, Newest, Oldest.
- **User Profile**:
    - Profile editing page to update Username, Email, and Password.
- **Infrastructure**:
    - `setup.sh` script for automated installation and database seeding.
    - `src/Config/config.sample.php` for secure configuration distribution.
    - `.gitignore` rules for security.
    - Premium "Glassmorphism" UI design system using Vanilla CSS.

### Security
- Implemented prepared statements (PDO) for all database queries to prevent SQL injection.
- configured `public/` directory as the only web-accessible root.
- Added strict session checks for authorized actions (voting, posting, admin access).
