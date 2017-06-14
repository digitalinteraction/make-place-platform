#!/usr/bin/env bash


# Check for requirements
php _check_env.php DB_HOST DB_USER DB_PASS DB_NAME


# copy env vars for cronjobs
env | sed 's/^\(.\)/export \1/' >> /app/.cron_env


# Build the database
framework/sake dev/build > build.log 2> build.log


# Build the theme
sed -i -e "s/\$primaryColour.*/\$primaryColour: ${PRIMARY_COL:-"#3886c9"};/" _config.scss
sed -i -e "s/\$secondaryColour.*/\$secondaryColour: ${SECONDARY_COL:-"#aaaab2"};/" _config.scss

# Recompile the scss
echo "Compiling styles"
sh scripts/run-sass -f


# Configure php5-fpm
sed -i -e "s/pm =.*/pm = ondemand/" /etc/php5/fpm/pool.d/www.conf
sed -i -e "s/pm.max_children =.*/pm.max_children = 3/" /etc/php5/fpm/pool.d/www.conf
service php5-fpm restart
