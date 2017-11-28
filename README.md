# MySQL Fixer Module

This is intended to _fix_ problem
`Expression #1 of ORDER BY clause is not in SELECT list.....which is not in SELECT list; this is incompatible with DISTINCT` caused by some modules.

The distinct _error_ occures with MySQL >5.7.5 where the option __ONLY_FULL_GROUP_BY__ became part of the combination on __ANSI__ (which also includes 'REAL_AS_FLOAT, PIPES_AS_CONCAT, ANSI_QUOTES, IGNORE_SPACE') used by SilverStripe for sql_mode.

( See also here [https://dev.mysql.com/doc/refman/5.7/en/sql-mode.html#sql-mode-combo](https://dev.mysql.com/doc/refman/5.7/en/sql-mode.html#sql-mode-combo) )

 This module introduces the MySQL57Database class which extends the MySQLDatabase class, or better overwrites the connect() method using the sql_mode _'REAL_AS_FLOAT,PIPES_AS_CONCAT,ANSI_QUOTES,IGNORE_SPACE'_ and instead of to _'ANSI'_. 

## Maintainer Contact

Spliff Splendor
<Spliff (dot) Splendor (at) gmail (dot) com>

## Requirements

 * SilverStripe 3.3 or newer

## Installation

 * If using composer, run ` composer require spliffs/mysqlfixer `.
 * Otherwise, download, unzip and copy the 'mysqlfixer' folder to your project root so that it becomes a sibling of `framework/`.

## Configuration

Add this to your _config.php (right after
'require_once("conf/ConfigureFromEnv.php");') or in your _ss_environment.php where you configure your database.

### Sample/Excerpt _ss_environment.php
```php
// DB config
define('SS_DATABASE_CLASS', 'MySQL57Database');`
define('SS_DATABASE_SERVER',   'mysql5.7.20.local');
define('SS_DATABASE_USERNAME', 'dbuser');
define('SS_DATABASE_PASSWORD', 'top-secret-password');
define('SS_DATABASE_NAME',   'ss_mysite');
```
### Sample mysite/_config.php
```php
<?php
global $project;
$project = 'mysite';

global $database;
$database = 'SS_mysite';

require_once("conf/ConfigureFromEnv.php");

global $databaseConfig;

$databaseConfig = array(
	"type" => 'MySQL57Database',
	"server" => 'mysql5.7.20.local',
	"username" => 'dbuser',
	"password" => 'top-secret-password',
	"database" => 'ss_mysite',
);

SSViewer::set_theme('simple');
SiteTree::enable_nested_urls();
```

## Open Issues

