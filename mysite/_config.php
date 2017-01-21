<?php


/** A helper method to get an environment variable or a default value */
function _envVar($name, $default = null) {
    return isset($_SERVER[$name]) ? $_SERVER[$name] : $default;
}


// Define the project
global $project;
$project = 'mysite';


// Define the database
global $databaseConfig;
$databaseConfig = array(
    'type' => _envVar('DB_TYPE', 'MySQLDatabase'),
    'server' => _envVar('DB_HOST'),
    'username' => _envVar('DB_USER'),
    'password' => _envVar('DB_PASS'),
    'database' => _envVar('DB_NAME'),
    'path' => _envVar('DB_PATH', ''),
);



// Set the site locale
i18n::set_locale(_envVar('LOCALE', 'en_GB'));



// Add a log file where errors will be stored
SS_Log::add_writer(new SS_LogFileWriter(_envVar('LOG_FILE', '../silverstripe.log')), SS_Log::WARN, '<=');



// Define the default user
Security::setDefaultAdmin(_envVar('DEFAULT_USER', 'admin'), _envVar('DEFAULT_PASS', '37g!6sS0YW8E'));



// Define the environment type, defaulting to live
define('SS_ENVIRONMENT_TYPE', _envVar('SITE_ENV', 'live'));

// Define the recaptcha keys, defaulting to localhost ones
define('G_RECAPTCHA_PUBLIC', _envVar('G_RECAPTCHA_PUBLIC', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'));
define('G_RECAPTCHA_SECRET', _envVar('G_RECAPTCHA_SECRET', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'));

// Defien the sendgrid key, defaulting to an error one
define('SENDGRID_API_KEY', _envVar('SENDGRID_API_KEY', 'SENDGRID_KEY_NOT_DEFINED'));

// Define the backup procedure
define('SHOULD_BACKUP', (_envVar('SHOULD_BACKUP') != null));
define('DB_BACKUP_ROOT', '/backup/db/');

// Define AWS user (keys are automatically read from the environment)
define('AWS_USERNAME', _envVar('AWS_USERNAME', 'NO_AWS_USER_PROVIDED'));

// Define facebook OAuth keys
define('FB_APP_ID', _envVar('FB_APP_ID', 'NO_FB_APP_ID_PROVIDED'));
define('FB_SECRET', _envVar('FB_SECRET', 'NO_FB_SECRET_ID_PROVIDED'));



// Define file-url mapping for using framework/sake
global $_FILE_TO_URL_MAPPING;
if (isset($_SERVER['HTTP_HOST'])) {
    $_FILE_TO_URL_MAPPING['/app'] = 'http://'.$_SERVER['HTTP_HOST'];
}
else if (isset($_SERVER["CUSTOM_DOMAIN"])) {
    $_FILE_TO_URL_MAPPING['/app'] = $_SERVER["CUSTOM_DOMAIN"];
}



// Set where to store the session
ini_set('session.save_path', "/app/silverstripe-cache");



// If a log email is available, add a logger
if (_envVar("LOG_EMAIL")) {
	SS_Log::add_writer(new SS_LogEmailWriter(LOG_EMAIL), SS_Log::WARN, '<=');
}

// If the sitebase is set, apply it to the Director
if (_envVar("SITE_BASE")) {
	Director::setBaseURL(SITE_BASE);
}

// Turn on errors if not in dev mode
if (SS_ENVIRONMENT_TYPE != 'live') {
    ini_set('display_errors', 1);
}
