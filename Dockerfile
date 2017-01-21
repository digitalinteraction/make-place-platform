#
# Dockerfile to deploy a silverstripe server
#



# Use my compose image
FROM openlab.ncl.ac.uk:4567/b30282237/composer-image:1.0.3


# Add Sqlite3
RUN apt-get -y update \
 && apt-get -y upgrade -y \
 && DEBIAN_FRONTEND=noninteractive apt-get -y install \
        sqlite3 \
        php5-sqlite \
        && rm -rf /var/lib/apt/lists/*


# Expose port 80 to serve html on
EXPOSE 80


# Add nginx config file
COPY default.nginx /etc/nginx/sites-available/default


# Make and own silverstripe folders & files
RUN mkdir -p /app/silverstripe-cache \
    && mkdir -p /app/assets \
    && mkdir -p /backup/db \
    && touch /app/silverstripe.log \
    && chown www-data /app/silverstripe.log \
    && chown www-data /app/assets \
    && chown www-data /app/silverstripe-cache \
    && chown www-data /backup \
    && chown www-data /backup/db


# Add volumes for assets & backup data
VOLUME ["/app/assets", "/backup"]


# Add my code to the build
COPY mysite /app/mysite
COPY themes /app/themes


# Add composer & cronjobs files
COPY ["phpunit.xml", "composer.json", "cronjobs", "bootstrap.sh", "/app/"]


# Add our scripts for testing
COPY scripts /app/scripts


# Start cron jobs
RUN crontab -u root cronjobs && rm cronjobs
