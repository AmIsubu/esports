# UK E-Sports League Web Application

This is a PHP and MySQL-based web application for managing an E-Sports League. It includes admin and user interfaces for managing participants, teams, and merchandise registrations.

## Features

- **Admin Panel**: Secure login, manage participants, teams, and view merchandise registrations.
- **User Registration**: Register for free merchandise.
- **Participant Management**: Add, edit, delete, and search participants.
- **Team Management**: View teams and their members.
- **Responsive Design**: Modern UI with Bootstrap 5, mobile-friendly.

## Setup Instructions

1. **Requirements**

   - XAMPP or similar local server (PHP 7.4+ and MySQL)
   - Composer (optional, for future package management)

2. **Database Setup**

   - Import `esports.sql` into your MySQL server (phpMyAdmin or CLI).
   - Or, run `setup_database.php` in your browser to auto-create tables and a default admin user.

3. **Configuration**

   - Edit `dbconnect.php` with your MySQL credentials if needed.

4. **Default Admin Login**

   - Username: `admin`
   - Password: `password123`

5. **Running the App**
   - Place all files in your XAMPP `htdocs` directory.
   - Start Apache and MySQL from XAMPP control panel.
   - Visit `http://localhost/` in your browser.

## File Structure

- `admin_login.html` — Admin login page
- `admin_menu.php` — Admin dashboard
- `view_participants_edit_delete.php` — Manage participants
- `add_participant_form.php` — Add participant form
- `edit_participant.php` — Edit participant
- `delete.php` — Delete participant
- `view_merchandise.php` — View merchandise registrations
- `register_form.html` — User registration form
- `register.php` — Registration handler
- `dbconnect.php` — Database connection settings
- `setup_database.php` — Database/table setup script
- `esports.sql` — SQL dump for database

## Security Notes

- For production, always use password hashing for admin and user accounts.
- Change default admin credentials after setup.
- Sanitize and validate all user input.

## License

This project is for educational/demo purposes. Customize and extend as needed for your use case.
