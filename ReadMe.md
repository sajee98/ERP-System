 
Setup my Project
1. Install a Local Server Environment Xampp
2. Create The Project File ERP-system to Xampp htdocs
3. Open phpMyAdmin in your browser: http://localhost/phpmyadmin/ create a Database erp-system
4. Configure Database Connection. dbconnect.php
5. Access the Project in Browser.http://localhost/ERP%20System/admin/


Project Overview
This project is a simple ERP system designed using PHP and MySQL. The system allows users to manage customers, items, and invoices, along with generating reports. It includes CRUD operations, validations, and AJAX-based functionality for a seamless user experience.

Features
1. Customers Management (customers.php)
Add new customer with form validation.
View existing customers in a table above the form.
Edit and delete customer details.
AJAX-based form submission to update the table without refreshing the page.

2. Items Management (items.php)
Add new items with form validation.
If the item already exists, the system updates the existing item instead of creating a new entry.
View all items in a table format.
Edit and delete items as needed.

3. Invoices (invoice.php)
Create invoices with validated forms.
Automatic invoice numbering: INV-YYYYMMDD-XXXX format (e.g., INV-20251110-0010).
Deducts item quantity from stock automatically when invoice is created.
Shows total sales and today's sales on the dashboard.
Supports searching and filtering by date or customer name on Invoicereport.

4. Reports
Invoice Report: View all invoices and filter by date or Names.
Invoice Item Report: See items associated with invoices.
Item Report: View stock status and item details.


1. Assumptions
Users will access the system in a single local environment (localhost).
Invoice numbering starts with yymmdd 001.
Item stock is deducted immediately upon invoice creation.
AJAX is used for customer and item forms for better UX.
PHP version 8+ and MySQL 5.7+ are assumed.
