# Silverstripe Sample Project
A skeleton project to bootstrap a [Silverstripe](https://www.silverstripe.org/) server to be deployed through `docker`/`dokku`


## Features
- A minimalistic [Bootstrap](https://getbootstrap.org) theme for Silverstripe & its blog
- A Dockerfile ready to create an nginx/php5-fpm/composer image & install Silverstripe
- Load all your config through environment variables

## Notes
- The image is based on [PHP Composer Image](https://openlab.ncl.ac.uk/gitlab/b30282237/composer-image)

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
DEFAULT_USER            | no        | The username of the default admin to create, defaults to `admin`
DEFAULT_PASS            | no        | The password of the default admin to create, defaults to `37g!6sS0YW8E`
LOG_EMAIL               | no        | An email to send server errors to
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



## Todo
- Finish CI to publish images to the gitlab registry
- Export CI coverage straight to stdout rather than an intermediate file
- Create cached Docker image for CI, you shouldn't have to `composer install` every push
