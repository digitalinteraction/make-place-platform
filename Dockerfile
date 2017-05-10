#
# Builds an instance of the app from the base adding the variable parts (code)
#


# Start from our base image
FROM openlab.ncl.ac.uk:4567/make-place/web:base-2


# Add nginx config file
COPY default.nginx /etc/nginx/sites-available/default


# Add composer & cronjobs files
COPY ["phpunit.xml", "cronjobs", "bootstrap.sh", "/app/"]


# Start cron jobs
RUN crontab -u root cronjobs && rm cronjobs


# Add my code to the build
COPY scripts /app/scripts
COPY mysite /app/mysite
COPY themes /app/themes
COPY surveys /app/surveys
COPY maps /app/maps
COPY auth /app/auth
