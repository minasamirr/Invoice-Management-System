Invoice Management System

Overview:

The Invoice Management System is a Laravel-based web application designed to manage invoices and customer data. The system supports full CRUD (Create, Read, Update, Delete) operations for invoices and customers. It features advanced search functionality, pagination, email notifications for invoice updates, and a RESTful API for invoice management with role-based permissions for Admins and Employees.

Features:

Invoice CRUD Operations:

Create, view, update, and delete invoices with a user-friendly interface.

Customer Management:

Manage customer information and associate each invoice with a specific customer.

Role-Based Permissions:

Admins: Have full control over invoices (create, update, delete).

Employees: Can only update existing invoices.

Invoice Logs:

Maintain an audit log for all actions performed on invoices (create, update, delete) including user details and timestamps.

Advanced Invoice Search:

Search invoices based on multiple criteria including:
    Invoice number,
    Customer name,
    Invoice date range (from–to),
    Invoice amount range (from–to),
    Payment status,
    Currency.

Pagination:

Display invoices with pagination (default 10 per page) and allow the user to select the number of items per page.

Email Notifications:

Automatically send an email notification to customers when an invoice is updated. The email details the changes made to the invoice.

RESTful API:

A separate API (secured via Laravel Sanctum) allows authenticated users to:
    Employees: Update invoice data.
    Admins: Create, update, and delete invoices.

Technologies

Backend Framework: Laravel 8

Frontend: Blade Templating Engine, Bootstrap 5

Database: MySQL

API Authentication: Laravel Sanctum (session based for web and token based for api) (use gate for route and policy for model)

Email: Laravel Mail using gmail

Additional: Eloquent ORM, Form Request Validation

Setup Instructions:

Download XAMPP.

Open XAMPP:

Start Apache & MySQL.

Clone the Repository:

    git clone https://github.com/your-username/invoice-management-system.git
    cd invoice-management-system

Install Dependencies:
    Make sure you have Composer installed, then run:

    composer install

Environment Setup:
    Copy the example environment file and update the settings:
    
    cp .env.example .env

Generate an application key:

    php artisan key:generate
    Edit the .env file to set your database configuration, mail settings, and any other necessary environment variables.

Run Migrations:

    Migrate your database to create the necessary tables:
    php artisan migrate

Optionally, seed the database if you have seeders set up:

    php artisan db:seed

Serve the Application:
    Start the local development server:

    php artisan serve

Your application will typically be available at http://127.0.0.1:8000.

Postman Collection
A Postman collection is provided with the project, demonstrating how to use the API endpoints:

    Authentication Endpoints:

        POST /api/register

        POST /api/login

        POST /api/logout

    Invoice Endpoints:

        GET /api/invoices

        GET /api/invoices/{invoice}

        POST /api/invoices (Admin only)

        PUT /api/invoices/{invoice} (Admin & Employee)

        DELETE /api/invoices/{invoice} (Admin only)

    Search Functionality:

        GET /api/invoices/search

    Invoice Logs Endpoint:

        GET /api/invoice_logs (Admin only)

To use the collection:

Open Postman and import the JSON collection file provided in the repository.
Configure your environment variables (API base URL, tokens, etc.).
Follow the documentation within the collection for request parameters and examples.
