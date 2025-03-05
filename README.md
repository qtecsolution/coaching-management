# Coaching Management System - Project Setup Guide

## Overview

Welcome to the setup guide for the **Coaching Management System**. This document provides comprehensive steps to install, configure, and run the project in your local environment, using both Docker and a native setup. Follow these instructions to ensure proper configuration.

## Features

### User Management
- Dynamic role and permission management system
- Permission-based access control
- Two types of users:
  - Admin
  - Teacher

### Student Management
- Batch assignment system
- Dynamic custom fields for student data
- Student ID card generation (Admin only)

### Batch Management
- Course management and batch assignment
- Batch schedule management including:
  - Day Name
  - Teacher Name
  - Start Time
  - End Time

### Payment Management
- Batch-wise fee collection
- Milestone-based payment options
- Payment tracking system

### Attendance Management
- Automated weekly class schedule generation
- Dashboard display for students and teachers
- Teacher attendance marking system
- Admin monitoring capabilities

### Send Messages
- SMS notification system
- Individual student messaging
- Batch-wise bulk messaging

### Class Materials Management
- Resource management for batches
- Student access to batch-specific materials
- Centralized resource repository

### Lead Management
- Lead tracking system
- Dynamic custom fields for lead data
- Lead status management

### Settings Management
System configuration options including:
- System Information
  - Name
  - Logo
  - Contact Details
  - Social Media Links
- SMTP Configuration
  - SMS credentials
  - Email credentials

### Profile Management
Users can manage:
- Personal Information
  - Name
  - Phone
  - Email
- Security
  - Password
- Appearance
  - Avatar

## Prerequisites

Please ensure you have the following installed on your system:

- **PHP** (version 8.3 or higher)
- **Composer**
- **npm**
- **MySQL** (version 8.0 or compatible, e.g., MariaDB)
- **Git**

## Server Requirements

This application requires a server with the following specifications:

- **PHP** (version 8.3 or higher) with the extensions:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - PDO
  - GD
  - Zip
  - PDO MySQL
- **MySQL** (version 8.0) or **MariaDB**
- **Composer**
- **Web Server**: Apache or Nginx

---

## Setup Options

This guide covers two setup methods:
1. **Setting Up Locally (Without Docker)**
2. **Using Docker**

---

### Setup Without Docker

#### 1. Clone the Repository

```bash
git clone https://github.com/qtecsolution/coaching-management.git
cd coaching-management
```

#### 2. Install PHP and npm Dependencies

Within the project directory, run:

```bash
composer install
npm install
npm run build
```

#### 3. Configure the Environment

Create the `.env` file by copying the sample configuration:

```bash
cp .env.example .env
```

#### 4. Generate Application Key

Secure the application by generating a key:

```bash
php artisan key:generate
```

#### 5. Configure Database

1. **Access MySQL**:

    ```bash
    mysql -u {username} -p
    ```

2. **Create Database**:

    ```sql
    CREATE DATABASE coaching_management;
    ```

3. **Grant User Permissions**:

    ```sql
    GRANT ALL ON coaching_management.* TO '{your_username}'@'localhost' IDENTIFIED BY '{your_password}';
    ```

4. **Apply Changes and Exit**:

    ```sql
    FLUSH PRIVILEGES;
    EXIT;
    ```

5. **Update `.env` Database Settings**:

    ```plaintext
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=coaching_management
    DB_USERNAME={your_username}
    DB_PASSWORD={your_password}
    ```

#### 6. Run Migrations and Seed Data

To set up the database tables and populate them with initial data, run:

```bash
php artisan migrate --seed
```

#### 7. Start the Development Server

To run the application locally, execute:

```bash
php artisan serve
```

Your application will be available at [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

### Setup with Docker

#### 1. Clone the Repository

```bash
git clone https://github.com/qtecsolution/coaching-management.git
cd coaching-management
```

#### 2. Initialize the Project

```bash
sudo make setup
```

#### Additional Docker Commands

- **Install Dependencies**

    ```bash
    sudo make composer-install
    sudo make composer-update
    sudo make npm-install-build
    ```

- **Set File Permissions**

    ```bash
    sudo make set-permissions
    ```

- **Generate Application Key**

    ```bash
    sudo make generate-key
    ```

- **Run Migrations and Seed the Database**

    ```bash
    sudo make migrate-fresh-seed
    ```

- **Setup Environment File**

    ```bash
    sudo make setup-env
    ```

The application should now be accessible at [http://localhost](http://localhost).

---

## Additional Information

- **Seeding**: The database seeder is configured to populate initial data. Run `php artisan migrate --seed` to use it.
- **Environment Variables**: Ensure all necessary environment variables are set in the `.env` file.
- **Database Configuration**: The application is configured for MySQL by default. Update the `.env` file as needed for other database connections.

## 🤝 Contributing

This is an open source project and contributions are welcome. If you are interested in contributing, please fork the repository and submit a pull request with your changes. The project maintainers will review and merge your changes accordingly.

## 📝 License

The coaching-management project is open source and available under the MIT License. You are free to use, modify, and distribute this codebase in accordance with the terms of the license.

Please refer to the LICENSE file for more details.

## Support

If you encounter any issues or have questions, feel free to reach out through the following channels:

-   Open an issue on the [GitHub repository](https://github.com/qtecsolution/AI-Content-Image-Generator-SaaS).
-   **Call for Queries**: +8801313522828 (WhatsApp)
-   **Contact Form**: [Qtec Solution Contact Page](https://qtecsolution.com/contact-us)
-   **Email**: [info@qtecsolution.com](mailto:info@qtecsolution.com)

## Follow Us on Social Media

Stay updated with the latest news, updates, and releases:

![Qtec Solution Limited.](https://raw.githubusercontent.com/qtecsolution/qtecsolution/refs/heads/main/QTEC-Solution-Limited.png) <br>
[![View Portfolio](https://img.shields.io/badge/View%20Portfolio-%230077B5?style=for-the-badge&logo=portfolio&logoColor=white)](https://qtecsolution.com/Qtec-Solution-Limited-Portfolio.pdf)
[![Facebook](https://img.shields.io/badge/Facebook-4267B2?style=for-the-badge&logo=facebook&logoColor=white)](https://www.facebook.com/QtecSolution/)
[![Instagram](https://img.shields.io/badge/Instagram-E4405F?style=for-the-badge&logo=instagram&logoColor=white)](https://www.instagram.com/qtecsolution/)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/company/qtec-solution)
[![X](https://img.shields.io/badge/X-000000?style=for-the-badge&logo=x&logoColor=white)](https://twitter.com/qtec_solution)
[![YouTube](https://img.shields.io/badge/YouTube-FF0000?style=for-the-badge&logo=youtube&logoColor=white)](https://www.youtube.com/@qtecsolutionlimited)
[![Website](https://img.shields.io/badge/Website-000000?style=for-the-badge&logo=google-chrome&logoColor=white)](https://qtecsolution.com/)
