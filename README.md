[![build status](https://openlab.ncl.ac.uk/gitlab/make-place/web/badges/master/build.svg)](https://openlab.ncl.ac.uk/gitlab/make-place/web/commits/master)


# Make (your) Place
A geographical mapping platform designed to be reconfigurable and redeployable. Based on [Silverstripe](https://www.silverstripe.org/) and deployed through [Docker](https://www.docker.com/).


## Features
- Geographical surveys, place questions on a map
- Survey api to interact with your surveys outside of the site
- Full CMS to configure and design your deployment's website
- Customisable theme by docker variables
- Emails send through Sendgrid's smtp


## Project Structure
- Features are implemented as Silverstripe modules; root level folders each with their own MVC structure inside
- JS & SCSS transpiling via [Webpack](https://webpack.js.org/) (placed in `/public`), see `scripts/build-assets` and `scripts/dev-runtime`
- Server is split into 2 docker images, one to add all packages the other add project code, see `base.Dockerfile`
- Server is based on [PHP Composer Image](https://openlab.ncl.ac.uk/gitlab/b30282237/composer-image) to provide `php5-fpm` & `nginx` stack with `php-composer` to install modules


## Prerequisites
- [Docker](https://www.docker.com/) and [docker-compose](https://docs.docker.com/compose/)
- [Node Js](https://nodejs.org) for local development
- A [Sendgrid](https://sendgrid.com/) api key for sending emails through their smtp


## Setup
1. Start up your containers
```bash
cd into/your/project
npm install
docker-compose up -d --build
docker-compose ps
```
2. Start webpack watch to compile assets
```bash
bash scripts/dev-runtime
```
3. Visit `http://localhost:PORT` where PORT is the port of web, printed by `docker-compose ps`
4. All code should be mapped into your container so saving and reloading will always be the latest version


## Detailed Project Structure
Folder | Contents
------ | --------
`_config` | Various configurations for the project including webpack setup and scss shared variables
`assets` | Volumed mapped from container, where Silverstripe puts uplaoded assets
`auth` | **Auth Module**, logic related to logging in and registering
`docs` | Generated documentation for the API, generate with `scripts/apidoc`
`interaction` | **Interaction Module**, logic related to voting and commenting on things
`maps` | **Maps Module**, logic related to setting up and viewing configurable maps
`mysite` | **Mysite Module**, shared logic between modules and the basis for others to use
`node_modules` | Imported javascript modules, install with `npm install`
`public` | Static files to be included in html, Webpack also transpiles into here
`scripts` | Various scripts to ease development, from building the docker image or running Webpack
`surveys` | **Survey Module**, logic realted to creating and rendering surveys for people to answer
`themes` | Generic templates & styles for rendering pages server-side


## Environment variables
Here are the environment variables the project uses and what they are used for. Required variables will fail the docker build if they are missing, non-required can be ignored but their relevant features will **not** work.

Variable                | Required  | Meaning
----------------------- | --------- | -------
DB_HOST                 | **yes**   | The host providing the database, in Docker this can be the name of a linked database container
DB_USER                 | **yes**   | The user Silverstripe will use to access the database
DB_PASS                 | **yes**   | The password of the above user
DB_NAME                 | **yes**   | The database to store site data in, the user **must** have access to it and be able to edit the schema
DB_TYPE                 | no        | The type of database to connect to, default is `MySQLDatabase`
DB_PATH                 | no        | The directory a file-based database will be store, e.g. `/app/testdb/`
SITE_ENV                | no        | The mode of the site, `live`, `testing`, `dev`; defaults to `live`
LOCALE                  | no        | The local of the site, used for date formatting & translations, defaults to `en_GB`
LOG_FILE                | no        | Where to store the log file, relative to this `mysite/_config.php`
DEFAULT_USER            | no        | The username of the default admin to create, must also have `DEFAULT_PASS` set
DEFAULT_PASS            | no        | The password of the default admin to create, must also have `DEFAULT_USER` set
LOG_EMAIL               | no        | An email to send server errors to
ADMIN_EMAIL             | no        | The email adress emails will come from
SITE_BASE               | no        | If the server is being run on a subdirectory e.g. `openlab.ncl.ac.uk/dokku/my-site`, setting this will fix Silverstripe's URLs. For this example set to `/dokku/my-site/`
G_RECAPTCHA_PUBLIC      | no        | Your [Google Recaptcha](https://www.google.com/recaptcha) public key
G_RECAPTCHA_SECRET      | no        | Your [Google Recaptcha](https://www.google.com/recaptcha) secret key
SENDGRID_API_KEY        | no        | Your [Sendgrid](https://sendgrid.com) Api key
SHOULD_BACKUP           | no        | Whether to backup the database to Amazon S3 (If any value is set it will run the backup)
AWS_USERNAME            | no        | Your [Amazon Web Services](https://aws.amazon.com/) username
AWS_ACCESS_KEY_ID       | no        | Your [Amazon Web Services](https://aws.amazon.com/) secret key
AWS_SECRET_ACCESS_KEY   | no        | Your [Amazon Web Services](https://aws.amazon.com/) access key
FB_APP_ID               | no        | Your [Facebook](https://facebook.com) App ID
FB_SECRET               | no        | Your [Facebook](https://facebook.com) Secret, used for OAuth
