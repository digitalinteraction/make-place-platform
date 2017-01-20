
# Check for requirements
# php _check_env.php DB_HOST DB_USER DB_PASS DB_NAME SITE_ENV G_RECAPTCHA_PUBLIC G_RECAPTCHA_SECRET SENDGRID_API_KEY AWS_ACCESS_KEY_ID AWS_SECRET_ACCESS_KEY FB_APP_ID FB_SECRET
php _check_env.php DB_HOST DB_USER DB_PASS DB_NAME


# Build the database
# framework/sake dev/build "flush=1"


# Seed the database?
