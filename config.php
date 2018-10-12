<?php
set_time_limit(0);
error_reporting(E_ALL & ~E_NOTICE);

ini_set("session.use_cookies", 1);
ini_set("session.use_trans_sid", 1);
ini_set("session.gc_maxlifetime", 65535);


ini_set("arg_separator.output", "&amp;");
ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("error_log", 'temp/error.log');

//database settings
define("DB_HOST"    , 'localhost');
define("DB_LOGIN"   , 'root');
define("DB_PASSWORD", '');
define("DB_NAME"    , 'scrub2');


define("CURRENT_DIR"  , getcwd() . DIRECTORY_SEPARATOR );   //stand-alone classes
define("CLASSES_DIR"  , CURRENT_DIR . 'classes' .  DIRECTORY_SEPARATOR);   //stand-alone classes
define("ACTIONS_DIR"  , CURRENT_DIR . 'actions' .  DIRECTORY_SEPARATOR);   //controllers processing sumbitted data and preparing output
define("TEMP_DIR",  CURRENT_DIR . 'temp' . DIRECTORY_SEPARATOR); //all uploaded files will be copied here so that they won't be deleted between requests
define("SESSIONS_DIR",  TEMP_DIR . 'sessions' . DIRECTORY_SEPARATOR); //sessions are stored here
define("ARCHIVE_DIR",  CURRENT_DIR . 'archive' . DIRECTORY_SEPARATOR);
define('SESSION_TTL', 60 * 60 * 24 * 7); //7 days

define('DUMP_LINES_LIMIT', 10000); //dump this many records at a time (to fit into memory_limit)

