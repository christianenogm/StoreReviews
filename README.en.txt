STORE REVIEWS
=============

A PHP and MySQL web application for adding stores and managing customer reviews.

DEMO
----

Local demo:
http://127.0.0.1:8000/index.php

Public demo:
Add the URL of your PHP + MySQL hosting service after deployment.

FEATURES
--------

- Add stores
- Publish reviews
- View reviews
- Filter reviews by store
- Edit reviews
- Delete reviews with confirmation
- Integrated single-page interface
- Prepared queries to help prevent SQL Injection
- Escaped output to reduce XSS risks

TECHNOLOGIES
------------

- PHP 8+
- MySQL or MariaDB
- HTML5
- CSS3
- MySQLi

MAIN PROJECT FILES
------------------

index.php              - Integrated main page
creds.php              - Database credentials and connection
add_store.php           - Standalone store form
add_review.php          - Standalone review form
read_reviews.php        - Standalone review list
edit_review.php         - Standalone review editor
delete_review.php       - Standalone review deletion
list_stores_reviews.php - Reviews grouped by store
create_table.php        - Helper script for creating the entries table

LOCAL INSTALLATION
------------------

1. Install PHP 8 or later and MySQL/MariaDB.
2. Create a database named "reviews".
3. Update the host, username, password, and database in creds.php.
4. Create the required tables using the SQL below.
5. Open a terminal in the project directory.
6. Run:

   php -S 127.0.0.1:8000

7. Open:

   http://127.0.0.1:8000/index.php

DATABASE SCHEMA
---------------

Stores table:

CREATE TABLE stores (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    location VARCHAR(30) NULL
) CHARACTER SET utf8mb4;

Entries table:

CREATE TABLE entries (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    review TEXT NOT NULL,
    date_entered DATETIME NOT NULL,
    storeId INT NOT NULL
) CHARACTER SET utf8mb4;

PUBLISHING TO GITHUB
--------------------

The source code can be published in a GitHub repository.
Never commit real production passwords or credentials.

IMPORTANT: GITHUB PAGES
-----------------------

GitHub Pages only hosts static content and does not run PHP or MySQL.
To provide a fully functional demo, deploy the application to a hosting service
that supports PHP and MySQL, such as Railway, Render with an external MySQL
database, or shared PHP hosting. Then add the public URL to the Demo section.

AUTHOR
------

Caio Nogueira Mendes

Web development academic project.
