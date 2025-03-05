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
  - 
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
1. **Using Docker**
2. **Setting Up Locally (Without Docker)**

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

## Additional Information

- **Seeding**: The database seeder is configured to populate initial data. Run `php artisan migrate --seed` to use it.
- **Environment Variables**: Ensure all necessary environment variables are set in the `.env` file.
- **Database Configuration**: The application is configured for MySQL by default. Update the `.env` file as needed for other database connections.
