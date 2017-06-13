#
# Dockerfile to deploy a silverstripe server
#



# Use my compose image
FROM openlab.ncl.ac.uk:4567/b30282237/composer-image:1.0.4


# Add Sqlite3 & ruby
RUN apt-get -y update \
 && apt-get -y upgrade -y \
 && DEBIAN_FRONTEND=noninteractive apt-get -y install \
        sqlite3 \
        php5-sqlite \
        ruby-full \
        && rm -rf /var/lib/apt/lists/*

# Add sass
RUN gem install sass


# Add composer & cronjobs files
COPY ["composer.json", "/app/"]


# Run Composer to install packages
RUN /composer.phar install


# Expose port 80 to serve html on
EXPOSE 80


# Make and own silverstripe folders & files
RUN mkdir -p /app/silverstripe-cache \
    && mkdir -p /app/assets/surveymedia \
    && mkdir -p /backup/db \
    && touch /app/silverstripe.log \
    && chown www-data /app/silverstripe.log \
    && chown www-data /app/assets \
    && chown www-data /app/assets/surveymedia \
    && chown www-data /app/silverstripe-cache \
    && chown www-data /backup \
    && chown www-data /backup/db


# Add volumes for assets & backup data
VOLUME ["/app/assets", "/backup", "/app/silverstripe-cache"]
