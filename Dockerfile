#
# Builds an instance of the app from the base adding the variable parts (code)
#


# Start from our base image
FROM openlab.ncl.ac.uk:4567/make-place/web:base-2.2.2


# Add nginx config file
COPY _config/default.nginx /etc/nginx/sites-available/default


# Add composer & cronjobs files
COPY _config/ .
RUN mkdir _config && mv vars.scss _config/vars.scss && mv common.scss _config/common.scss


# Start cron jobs
RUN crontab -u root cronjobs && rm cronjobs


# Add my code to the build
COPY scripts /app/scripts
COPY docs /app/docs
COPY mysite /app/mysite
COPY themes /app/themes
COPY surveys /app/surveys
COPY maps /app/maps
COPY auth /app/auth
COPY public /app/public
COPY interaction /app/interaction
