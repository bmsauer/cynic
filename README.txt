=========================================
== CYNIC - A LOW-FI APPLICATION PLATFORM
=========================================

-- ABOUT THIS PROJECT
Cynic is a platform to develop "low-fi" web applications.  This means that web
applications on the Cynic platform:
  - Do not use javascript at all.  Applications are purely client/server and
    request/response.
  - Conform to XHTML Transitional 1.0 and CSS 2.1
  - Use a simple key/value store database maintained by Cynic.
  - Prioritize speed, stability, usability, and user respect/freedom.
The goal is to build copy-cat applications of common web applications in a way
that is aligned with these principles.
  
-- ABOUT THE CYNICS (GREEK PHILOSOPHY)
From Wikipedia:
"For the Cynics, the purpose of life is to live in virtue, in agreement with
nature. As reasoning creatures, people can gain happiness by rigorous training
and by living in a way which is natural for themselves, rejecting all
conventional desires for wealth, power, and fame, and even flouting conventions
openly and derisively in public. Instead, they were to lead a simple life free
from all possessions."

The Cynic platform is a rejection of the conveniences and greed of the modern
web.  However, much like the Cynics, although this may be virtuous, it is also
unpractical and unpopular.

=========================================
== TECHNOLOGY
=========================================

- PHP 8.1 with Composer (latest)
- CodeIgniter framework 4.2.1
- Docker and Docker-compose
- Apache Web Server
- MySQL 8.0.28

=========================================
== GETTING STARTED
=========================================

-- INSTALLATION
First, clone this repository to your local development environment using git.
Next, make sure you have Docker and Docker-Compose installed.  Everything else
is installed inside of containers.

Other tools you will need:
  - Git, to get this repo.
  - A good text editor.
  - An API tool.  Postman and Insomnia are good choices.
  - A bash interpreter.  Git Bash, WSL, and Cygwin all work in various 
    capacities on Windows.  Linux and OSX will have bash installed by default.

-- CONFIGURATION
Cynic platform is configured with environment variables in files called .env.
Each subsystem of the platform contains its own .env files that will need
configuring.  To start, you at least need coreapp and coredb configured.

Copy 'coredb/.env.template' to 'coredb/.env'.  Then, modify the .env to set
MYSQL_ROOT_PASSWORD and MYSQL_PASSWORD.

For coreapp, copy 'coreapp/coreapp/.env.template' to 'coreapp/coreapp/.env'.
Set database_default_password to the password set in the 'coredb/.env' for the
MYSQL_PASSWORD.  Also, set SECRET_KEY to a string value of sufficient 
complexity.

-- RUNNING
First, you need to build the base container that the applications are based 
off of, ci4-base.

cd ci4base
docker build -t ci4base .
cd ..

Next, build each container in each service in the docker-compose.yml file:

docker-compose build

Sometimes, there is a race condition with the database, where it doesn't
properly initialize on first run.  This is due to the docker volumes being
initialized slowly, the database reporting it is online, and the coreapp 
attempting to migrate the database while it isn't truly ready.  A workaround
to this is to start the database to initialize the volumes, wait, stop the
database, and then turn on the entire application.

docker-compose up coredb

Use CTRL+C to stop the database, and allow it to gracefully shut down.

Then, run the application.  This will start it in the background.  You can see
what is running with docker ps.

docker-compose up

To run the application in the background, you can use:

docker-compose up -d

And to view logs:

docker-compose logs

You should be able to view the coreapp homepage at 'localhost:8080' in your 
browser.

To stop the application, use CTRL+C in the terminal.  If you started the
containers with 'docker-compose up -d', then you can use:

docker-compose down

-- OTHER HELPFUL COMMANDS
When running, you may want to do these commands:

Add user login data to the coreapp:
docker-compose exec coreapp php spark db:seed Auth

Drop into a MySQL CLI to do database debugging.  Replace the $ variables with
their values from coredb.env.
docker-compose run db-cli mysql --host $DB_HOST --port 3306 --user $MYSQL_USER --password=$MYSQL_PASSWORD --database $MYSQL_DATABASE

Drop into a app container sheel to debug:
docker-compose exec coreapp /bin/bash

Reload the coreapp:
docker-compose up --build -d coreapp 

Stop the app and clear the database:
docker-compose down -v

-- TESTS AND DOCS
When not running, try these commands:

Test coreapp:
docker run  -v ${PWD}/coreapp/test-reports:/var/www/html/ci4app/test-reports cynic_coreapp ./vendor/bin/phpunit

Generate docs:
cd coreapp
docker run --rm -v ${PWD}:/data phpdoc/phpdoc:3

=========================================
== DESIGN NOTES
=========================================

-- ARCHITECTURE OVERVIEW
The purpose of Cynic is to provide a platform to develop applications.  There
are currently two parts of Cynic: coreapp and coredb.

coreapp is a CodeIgniter 4 web application that will provide common utilities 
to applications on the Cynic platform via an API.  Currently, coreapp provides:
  - Authentication and signup.  All applications on the platform should share a
    common authentication provider any user can use any application on Cynic.
  - Database.  Cynic provides a simple key/value database API.  Applications on
    the platform use a multi-tenant database for all of their data.
    
coredb is a MySQL 8.0.28 database that stores information for coreapp, and 
therefore, all other apps that utilize user authentication and database
functionality via coreapp.  It currently has one database, core, that provides
two tables, auth and kv. 

coredb's table schemas are controlled by CodeIgniter 4 Models in coreapp.  For
a list of fields, see the 'coreapp/coreapp/app/Models' directory and the Model
classes they contain.

coredb is initialized and maintained by CodeIgniter 4 migrations.  These can
be found in the 'coreapp/coreapp/app/Database' directory.

coredb can be seeded via data seeders from coreapp.  Testing is done with a 
SQLITE in-memory database.  Testing migrations and seeds are stored in the
'coreapp/coreapp/tests/_support/Database' directory.
    
-- COREAPP API DOCS
Coming soon.  For now, view 'coreapp/coreapp/app/Config/Routes.php' and
'coreapp/coreapp/app/Controllers'.

Authentication of the API is provided by Cynic and uses JWT tokens.  This is
currently unused by the database API, and is listed in Security TODOs.

=========================================
== ROADMAP
=========================================

-- CURRENT STATUS
  - The current status is that Cynic is a early stage prototype with very
    little testing.  It should be assumed that all code is buggy, but the build
    process works.
    
-- TODO ITEMS RELATED TO SECURITY
  - Secure cookies with same-site, httponly as outline at:
    https://developer.mozilla.org/en-US/docs/Web/HTTP/Cookies
  - XSS/Injection attack audit
  - KV database does not check user auth; relies on other Cynic apps.
  
-- TODO ITEMS RELATED THE THE PLATFORM
  - Container and API versioning of coreapp
  - Container versioning of coredb
  - Platform deployment.  Currently only runs locally.
  
-- TODO ITEMS RELATED TO CODE QUALITY
  - Use strict_types

-- DOES NOT DO
The Cynic platform does not cover the following:
  - SSL.  This is to be performed by a Web Application Firewall Proxy that 
    Cynic does not provide.

=========================================
== LICENSE
=========================================
Cynic is released under the AGPL license, which is provided in the COPYING
file.