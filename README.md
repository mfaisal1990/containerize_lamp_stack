# Containerizing LAMP Stack

### What is Stack?
The term **stack** short for **solution stack** means a group of software tools that work together to support an application.<br>
These individual tools combine to create a complete setup, so no extra software is needed for the application to run.

### What is LAMP Stack?
The **LAMP stack** is a collection of open-source software used to build and run web applications.<br>
To function properly, a web application typically requires four key components: an operating system, a web server, a database, and a programming language.<br>
The term **LAMP** is an acronym representing the following software components:
- *Linux* – Operating System
- *Apache* – Web Server
- *MySQL* or *MariaDB* – Database Management System
- *PHP* – Programming Language

## ✨ Features
🔧 Fully Dockerized LAMP Stack
Runs Apache, MySQL, and PHP services with isolated containers using Docker Compose.

🗃️ Auto-Initialized MySQL Database
Creates a testdb database and a users table with demo user data on first launch.

💻 PHP Frontend with MySQL Integration
A simple PHP script connects to MySQL and displays user data in a styled HTML table.

🎨 Basic CSS Styling
Custom style.css file adds a clean, modern UI to your PHP app.

🛠️ Apache with Custom Config
Apache is configured to support .htaccess and mod_rewrite for cleaner routing.

🧮 phpMyAdmin Included
Web-based interface to manage the MySQL database visually.

## 💻 Tech Stack
| Technology           | Description               |
| ------------------- | ------------------------ |
| 🐳 Docker Compose | Defining and running multi-container Docker applications |
| 🐧 Linux | Lightweight Linux base image used in all containers |
| 🌐 Apache 2       | Serves PHP content and handles HTTP requests |
| 🛢️ MySQL 8.0        | Stores application data |
| 🐘 PHP 8.2          | Handles server-side logic and database interaction |
| 🧭 phpMyAdmin         | Web-based interface to manage MySQL databases |

## 🗂️ Project Structure
<img width="530" height="431" alt="image" src="https://github.com/user-attachments/assets/8967407f-3c25-4a21-9935-fb6d0c6f8731" />

- apache-config.conf --> Custom Apache virtual host config
- docker-compose.yaml --> Defines services: web, mysql, phpMyAdmin
- Dockerfile --> Builds the Apache + PHP container
- init.sql --> SQL script to initialize MySQL database
- index.php --> Main PHP page displaying data from DB
- style.css --> Basic CSS for frontend styling
- README.md --> Project documentation

## 🚀 Deployment Steps

#### Folders
<img width="920" height="163" alt="image" src="https://github.com/user-attachments/assets/b4e947fe-1418-4d8f-aa67-1150f743246b" />

#### Dockerfile
```
FROM php:8.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy custom Apache config
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Copy PHP files
COPY php/ /var/www/html/
```

#### docker-compose.yaml
```
services:
  web:
    build:
      context: .
      dockerfile: Dockerfile
    ports:
      - "8080:80"
    depends_on:
      - mysql
    volumes:
      - ./php:/var/www/html

  mysql:
    image: mysql:8.0
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: root
    volumes:
      - mysql-data:/var/lib/mysql
      - ./mysql/init.sql:/docker-entrypoint-initdb.d/init.sql

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    restart: always
    ports:
      - "8081:80"
    environment:
      PMA_HOST: mysql
      MYSQL_ROOT_PASSWORD: root
    depends_on:
      - mysql

volumes:
  mysql-data:
```

