<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['dsn']      The full DSN string describe a connection to the database.
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database driver. e.g.: mysqlii.
|			Currently supported:
|				 cubrid, ibase, mssql, mysqli, mysqlii, oci8,
|				 odbc, pdo, postgre, sqlite, sqlite3, sqlsrv
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Query Builder class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For mysqli and mysqlii databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or mysqli < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysqli_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['encrypt']  Whether or not to use an encrypted connection.
|
|			'mysqli' (deprecated), 'sqlsrv' and 'pdo/sqlsrv' drivers accept TRUE/FALSE
|			'mysqlii' and 'pdo/mysqli' drivers accept an array with the following options:
|
|				'ssl_key'    - Path to the private key file
|				'ssl_cert'   - Path to the public key certificate file
|				'ssl_ca'     - Path to the certificate authority file
|				'ssl_capath' - Path to a directory containing trusted CA certificats in PEM format
|				'ssl_cipher' - List of *allowed* ciphers to be used for the encryption, separated by colons (':')
|				'ssl_verify' - TRUE/FALSE; Whether verify the server certificate or not ('mysqlii' only)
|
|	['compress'] Whether or not to use client compression (mysqli only)
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|	['ssl_options']	Used to set various SSL options that can be used when making SSL connections.
|	['failover'] array - A array with 0 or more data for connections if the main should fail.
|	['save_queries'] TRUE/FALSE - Whether to "save" all executed queries.
| 				NOTE: Disabling this will also effectively disable both
| 				$this->db->last_query() and profiling of DB queries.
| 				When you run a query, with this setting set to TRUE (default),
| 				CodeIgniter will store the SQL statement for debugging purposes.
| 				However, this may cause high memory usage, especially if you run
| 				a lot of SQL queries ... disable this to avoid that problem.
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $query_builder variables lets you determine whether or not to load
| the query builder class.
*/
$active_group = 'yydb_s1';
$query_builder = TRUE;

$db['yydb_m1'] = array(
    'dsn'	=> '',
    'hostname' => '10.104.0.211',
    'username' => 'dev',
    'password' => 'ftXtKQuAIC',
    'database' => DATABASE_YYDB,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['yydb_s1'] = array(
    'dsn'	=> '',
    'hostname' => '10.104.35.170',
    'username' => 'oruser',
    'password' => 'inksgotDKEk',
    'database' => DATABASE_YYDB,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['yydb_s2'] = array(
    'dsn'	=> '',
    'hostname' => '10.104.177.226',
    'username' => 'oruser',
    'password' => 'inksgotDKEk',
    'database' => DATABASE_YYDB,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['yydb_user_m1'] = array(
    'dsn'	=> '',
    'hostname' => '10.104.0.211',
    'username' => 'dev',
    'password' => 'ftXtKQuAIC',
    'database' => DATABASE_YYDB_USER,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['yydb_user_s1'] = array(
    'dsn'	=> '',
    'hostname' => '10.104.35.170',
    'username' => 'oruser',
    'password' => 'inksgotDKEk',
    'database' => DATABASE_YYDB_USER,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['yydb_user_s2'] = array(
    'dsn'	=> '',
    'hostname' => '10.104.177.226',
    'username' => 'oruser',
    'password' => 'inksgotDKEk',
    'database' => DATABASE_YYDB_USER,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
$db['yydb_active_m1'] = array(
    'dsn'	=> '',
    'hostname' => '10.104.0.211',
    'username' => 'dev',
    'password' => 'ftXtKQuAIC',
    'database' => DATABASE_YYDB_ACTIVE,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['yydb_active_s1'] = array(
    'dsn'	=> '',
    'hostname' => '10.104.35.170',
    'username' => 'oruser',
    'password' => 'inksgotDKEk',
    'database' => DATABASE_YYDB_ACTIVE,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['yydb_active_s2'] = array(
    'dsn'	=> '',
    'hostname' => '10.104.177.226',
    'username' => 'oruser',
    'password' => 'inksgotDKEk',
    'database' => DATABASE_YYDB_ACTIVE,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['yydb_statistics_m1'] = array(
    'dsn'	=> '',
    'hostname' => '10.104.177.226',
    'username' => 'dev',
    'password' => 'ftXtKQuAIC',
    'database' => DATABASE_YYDB_STATISTICS,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['yydb_statistics_s1'] = array(
    'dsn'	=> '',
    'hostname' => '10.104.177.226',
    'username' => 'oruser',
    'password' => 'inksgotDKEk',
    'database' => DATABASE_YYDB_STATISTICS,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['yydb_statistics_s2'] = array(
    'dsn'	=> '',
    'hostname' => '10.104.177.226',
    'username' => 'oruser',
    'password' => 'inksgotDKEk',
    'database' => DATABASE_YYDB_STATISTICS,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['mptools_m1'] = array(
    'dsn'	=> '',
    'hostname' => '10.104.0.211',
    'username' => 'dev',
    'password' => 'ftXtKQuAIC',
    'database' => DATABASE_MPTOOLS,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$db['wtg_wkd_m1'] = array(
    'dsn'	=> '',
    'hostname' => '10.104.0.211',
    'username' => 'dev',
    'password' => 'ftXtKQuAIC',
    'database' => DATABASE_WTG_WKD,
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

$a = array();
$multi_db = array(
    DATABASE_YYDB_ACTIVE => array(
        'master'=>array('host'=>'10.104.0.211', 'user'=>'dev', 'pwd'=>'ftXtKQuAIC'),
        'slave'=>array(
            's1' => array('host'=>'10.104.35.170', 'user'=>'dev', 'pwd'=>'ftXtKQuAIC'),
            's2' =>array('host'=>'10.104.177.226', 'user'=>'oruser', 'pwd'=>'inksgotDKEk')
        )
    )
);
for($i=0; $i<10; $i++) {
    foreach ($multi_db as $db_name => $conf) {
        $b = array();
        foreach($conf as $key=>$val) {
            switch ($key) {
                case 'master':
                    $db[$db_name.$i.'_m1'] = array(
                        'dsn'	=> '',
                        'hostname' => $val['host'],
                        'username' => $val['user'],
                        'password' => $val['pwd'],
                        'database' => $db_name.$i,
                        'dbdriver' => 'mysqli',
                        'dbprefix' => '',
                        'pconnect' => FALSE,
                        'db_debug' => (ENVIRONMENT !== 'production'),
                        'cache_on' => FALSE,
                        'cachedir' => '',
                        'char_set' => 'utf8',
                        'dbcollat' => 'utf8_general_ci',
                        'swap_pre' => '',
                        'encrypt' => FALSE,
                        'compress' => FALSE,
                        'stricton' => FALSE,
                        'failover' => array(),
                        'save_queries' => TRUE
                    );
                    break;
                case 'slave':
                    $len = count($val);
                    foreach ($val as $k=>$v) {
                        $db[$db_name.$i.'_'.$k] = array(
                            'dsn'	=> '',
                            'hostname' => $v['host'],
                            'username' => $v['user'],
                            'password' => $v['pwd'],
                            'database' => $db_name.$i,
                            'dbdriver' => 'mysqli',
                            'dbprefix' => '',
                            'pconnect' => FALSE,
                            'db_debug' => (ENVIRONMENT !== 'production'),
                            'cache_on' => FALSE,
                            'cachedir' => '',
                            'char_set' => 'utf8',
                            'dbcollat' => 'utf8_general_ci',
                            'swap_pre' => '',
                            'encrypt' => FALSE,
                            'compress' => FALSE,
                            'stricton' => FALSE,
                            'failover' => array(),
                            'save_queries' => TRUE
                        );
                    }
                    break;
            }
        }
    }
}
