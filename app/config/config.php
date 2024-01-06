<?php
namespace app;

setLocale(LC_TIME, 'nld_nld');

h::setConstant('DEBUG_MODE', 0);

if (DEBUG_MODE == 0) {
    error_reporting(0);
}

/** Database configuratie */
h::setConstant('DB_HOST', 'localhost');
h::setConstant('DB_USER', 'project_dbuser');
h::setConstant('DB_PASS', '#DBPASS#');
h::setConstant('DB_DBASE', 'project_db');

/** Applicatieconfiguratie */
h::setConstant('APP_NAME', 'Project');
h::setConstant('CLIENT_NAME', 'Project');
h::setConstant('NAMESPACES', serialize(['app', 'blueprint', 'framework']));

/** Style configuratie */
h::setConstant('CSS_FILES_VERSION', 1);

/** Javascript configuratie */
h::setConstant('JS_FILES_VERSION', 1);

/** Lokatieconfiguratie */
h::setConstant('ROOT', '/');
h::setConstant('DEFAULT_ENV_ID', 1);

/** Applicatieconfiguratie */
h::setConstant('SESSION_PREFIX', 'PROJECT_');