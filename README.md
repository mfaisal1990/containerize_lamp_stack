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

#### Create directories and files:
<img width="920" height="163" alt="image" src="https://github.com/user-attachments/assets/b4e947fe-1418-4d8f-aa67-1150f743246b" />

#### Dockerfile:
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

#### docker-compose.yaml:
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

#### Spinning up the containers:
```
root@ip-172-31-27-61:/home/ubuntu/myDocker/lamp_stack/project_dockerize_lamp_stack# docker compose up
WARN[0000] /home/ubuntu/myDocker/lamp_stack/project_dockerize_lamp_stack/docker-compose.yaml: the attribute `version` is obsolete, it will be ignored, please remove it to avoid potential confusion
[+] Running 4/4
 ✔ Network project_dockerize_lamp_stack_default         Created                                                                                                                         0.1s
 ✔ Container project_dockerize_lamp_stack-mysql-1       Created                                                                                                                         0.1s
 ✔ Container project_dockerize_lamp_stack-phpmyadmin-1  Created                                                                                                                         0.1s
 ✔ Container project_dockerize_lamp_stack-web-1         Created                                                                                                                         0.1s
Attaching to mysql-1, phpmyadmin-1, web-1
```

Three containers will be spun up:
- **web-1** Runs Apache & PHP 8.2, serves the web application, and handles all incoming HTTP requests.
- **mysql-1**	Provides the MySQL database, automatically initializes the testdb database and a users table using init.sql. 
- **phpmyadmin-1** A web-based UI to interact with the MySQL database.

#### Output of docker ps and volume, network:
```
root@ip-172-31-27-61:/home/ubuntu/myDocker/lamp_stack/project_dockerize_lamp_stack# docker ps
CONTAINER ID   IMAGE                              COMMAND                  CREATED         STATUS         PORTS                                     NAMES
c7e929b1097e   phpmyadmin/phpmyadmin              "/docker-entrypoint.…"   2 minutes ago   Up 2 minutes   0.0.0.0:8081->80/tcp, [::]:8081->80/tcp   project_dockerize_lamp_stack-phpmyadmin-1
b9b01d4fa3d5   project_dockerize_lamp_stack-web   "docker-php-entrypoi…"   2 minutes ago   Up 2 minutes   0.0.0.0:8080->80/tcp, [::]:8080->80/tcp   project_dockerize_lamp_stack-web-1
4b4b108dc984   mysql:8.0                          "docker-entrypoint.s…"   2 minutes ago   Up 2 minutes   3306/tcp, 33060/tcp                       project_dockerize_lamp_stack-mysql-1
root@ip-172-31-27-61:/home/ubuntu/myDocker/lamp_stack/project_dockerize_lamp_stack#
root@ip-172-31-27-61:/home/ubuntu/myDocker/lamp_stack/project_dockerize_lamp_stack# docker volume ls
DRIVER    VOLUME NAME
local     project_dockerize_lamp_stack_mysql-data
root@ip-172-31-27-61:/home/ubuntu/myDocker/lamp_stack/project_dockerize_lamp_stack#
root@ip-172-31-27-61:/home/ubuntu/myDocker/lamp_stack/project_dockerize_lamp_stack# docker network ls
NETWORK ID     NAME                                   DRIVER    SCOPE
ee7cb299c324   project_dockerize_lamp_stack_default   bridge    local
```
- App running at: http://host-ip:8080
- phpMyAdmin available at: http://host-ip:8081

## 📦 Output:
When I ran **docker compose up** command, three containers will will be spun up: web-1, mysql-1, and phpmyadmin-1. The web container runs the PHP app with Apache. The MySQL container sets up the database and adds some sample data. phpMyAdmin is also available to manage the database from the browser. The app can be opened at http://host-ip:8080 and phpMyAdmin at http://host-ip:8081.
Note: Since my host-ip is an AWS instance, so IP can be variable hence I mentioned as **host-ip**

#### Webpage on Port 8080:
<img width="1142" height="478" alt="image" src="https://github.com/user-attachments/assets/c6824dae-3046-457d-81bb-d961dda36b04" />

#### phpMyAdmin on Browser Port 8081:
<img width="1366" height="675" alt="image" src="https://github.com/user-attachments/assets/367a4a8c-f1a9-43f1-8f8e-604d2387b929" />

#### Adding a new row via phpMyAdmin:
<img width="846" height="158" alt="image" src="https://github.com/user-attachments/assets/92b4a4ef-7215-489d-af08-40738db31c13" />

#### Refreshing the Browser:
<img width="1162" height="533" alt="image" src="https://github.com/user-attachments/assets/b4209cf2-cef0-4f47-a732-0b74c8f1e5e4" />
