# Store Reviews

A PHP and MySQL web application for adding stores and managing customer reviews.

## Demo

> The application requires PHP and MySQL, so it cannot run directly on GitHub Pages. The screenshots below demonstrate the complete workflow.

### Main dashboard

![Initial page with sample data](demo/Page%20initial%20with%20my%20data.png)

### Add a store and review

| Add a store                               | Add a review                                    |
| ----------------------------------------- | ----------------------------------------------- |
| ![Add store](demo/Part%20Add%20store.png) | ![Add review](demo/Part%20Add%20a%20review.png) |

### Filter reviews by store

| Select a store                                                                      | Filtered reviews                                                                            |
| ----------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------- |
| ![Select a store](demo/Search%20one%20store%20to%20see%20review%20-%20part%201.png) | ![Filtered store reviews](demo/Search%20one%20store%20to%20see%20review%20-%20part%202.png) |

### Edit a review

| Edit form                              | Saved update                                 |
| -------------------------------------- | -------------------------------------------- |
| ![Edit review](demo/Edit%20review.png) | ![Review updated](demo/Review%20updated.png) |

### Delete a review

| Delete confirmation                                     | Deleted successfully                         |
| ------------------------------------------------------- | -------------------------------------------- |
| ![Delete review confirmation](demo/Delete%20review.png) | ![Review deleted](demo/Review%20deleted.png) |

## Features

- Add stores
- Publish reviews
- View and filter reviews by store
- Edit reviews
- Delete reviews with confirmation
- Integrated single-page interface
- Prepared queries to help prevent SQL Injection
- Escaped output to reduce XSS risks

## Technologies

- PHP 8+
- MySQL or MariaDB
- HTML5 and CSS3
- MySQLi

## Main Files

- `index.php` — integrated main page
- `creds.php` — database connection using environment variables or local defaults
- `database/schema.sql` — database schema and sample stores
- `Dockerfile` and `railway.json` — Railway deployment configuration
- `demo/` — project screenshots

## Local Installation

1. Install PHP 8+ and MySQL/MariaDB.
2. Create a database named `reviews`.
3. Update the local connection defaults in `creds.php` if needed.
4. Run the SQL statements from `database/schema.sql`.
5. Start the local PHP server:

```bash
php -S 127.0.0.1:8000
```

6. Open http://127.0.0.1:8000/index.php

## Deployment

GitHub Pages cannot execute PHP or connect to MySQL. The project is prepared for deployment to a PHP-compatible container host such as Railway. See the deployment instructions in `README.txt`.

## Author

**Caio Nogueira Mendes**  
Web development academic project.
