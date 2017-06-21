#
# Builds an instance of the app from the base adding the variable parts (code)
#


# Start from our base image
FROM openlab.ncl.ac.uk:4567/make-place/web:base-2.2.5


# Add config files
COPY _config/default.nginx /etc/nginx/sites-available/default
COPY [".eslintrc.js", ".babelrc", "/app/"]


# Add composer & cronjobs files
COPY _config/ /app/_config
RUN mv _config/bootstrap.sh bootstrap.sh \
  && mv _config/cronjobs cronjobs \
  && mv _config/phpunit.xml phpunit.xml
# RUN mkdir _config && mv vars.scss _config/vars.scss && mv common.scss _config/common.scss


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


# Build javascript
RUN scripts/build-js > /dev/null

# Build docs
RUN scripts/build-docs > /dev/null
