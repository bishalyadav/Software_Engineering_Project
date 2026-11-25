HostelHub - Setup and page flow

1. In Laragon, start Apache and MySQL.
2. In phpMyAdmin, import database.sql into a fresh installation.
   If you already have HostelHub tables and data, import database_update.sql instead.
   The update script only adds student registration profile fields and the contact inbox.
3. Check DB_HOST, DB_NAME, DB_USER, and DB_PASS in config.php.
4. Open http://localhost/HostelHub/ . The home page routes to Login or student registration.

User flow:
Home -> Login or Create Student Account -> role-specific dashboard -> Logout -> Home.
Students can log in with their email or enrollment number. Registration saves academic/contact details and can assign an available room.
Contact form submissions are saved in Contact Messages in the admin navigation.

Demo credentials (fresh database):
Admin: admin@hostelhub.com / password
Student: john@student.com / password
