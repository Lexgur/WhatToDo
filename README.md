WhatToDo Project - Setup and Usage Instructions
==============================================

Clone the Repository
--------------------
Open your terminal and run:

    git clone https://github.com/Lexgur/WhatToDo.git
    cd WhatToDo

Requirements
------------
- PHP 8.3 or higher installed and available in your PATH
- Composer installed (https://getcomposer.org/)
- SQLite enabled in your PHP installation (used as the database)
- Git installed (to clone the repo)

Setup Instructions
------------------
1. Install dependencies via Composer:

   composer install

2. Run the necessary scripts to initialize the database and seed data:

   - php bin/script Edgaras/WhatToDo/Scripts/CreateDatabaseScript
   - php bin/script Edgaras/WhatToDo/Scripts/RunMigrationsScript
   - php bin/script Edgaras/WhatToDo/Scripts/RunSeedersScript

These commands will:
- Create the SQLite database file
- Run database migrations to set up tables
- Seed the database with initial sports data

Running Tests
-------------
To run the PHPUnit test suite and verify everything works as expected:

    composer phpunit

This will execute all tests, including the SportController endpoint tests which validate the API responses.

Using the Application
---------------------
After setup, you can use the application endpoints to fetch sports data. The SportController serves data at URLs like:

    /sportas
    /sportas?city=Vilnius
    /sportas?type=public&kind=pool

You can test these endpoints through your web server or tools like curl or Postman.

Additional Notes
----------------
- Make sure your PHP CLI version is 8.3 or newer: run `php -v` to verify.
- If you run into permission issues with the SQLite file or migration/seed files, check your directory permissions.
- This project uses SQLite for simplicity; you can find the database file under `tmp/test/WhatToDo.sqlite`.

Support
-------
If you encounter issues, please check the documentation or open an issue in the GitHub repository.

---

Thank you for using WhatToDo!
