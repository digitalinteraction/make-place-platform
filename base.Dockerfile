#
# Base image Dockerfile for Make Place
# Specifically sets up all packages without adding any project code
#



# Start with a php-fpm-nginx-composer image
FROM openlab.ncl.ac.uk:4567/rob/composer-image:1.0.4


# Run the node setup
RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -


# Add Sqlite3 & node packages
RUN apt-get -y update \
 && apt-get -y upgrade -y \
 && DEBIAN_FRONTEND=noninteractive apt-get -y install \
        sqlite3 \
        php5-sqlite \
        nodejs \
        && rm -rf /var/lib/apt/lists/*


# Add package configuration files
COPY ["package.json", "composer.json", "/app/"]


# Run Composer to install php packages
RUN /composer.phar install


# Run npm to install js packages
RUN npm install


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
