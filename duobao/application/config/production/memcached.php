<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Memcached settings
| -------------------------------------------------------------------------
| Your Memcached servers can be specified below.
|
|	See: https://codeigniter.com/user_guide/libraries/caching.html#memcached
|
*/
$config = array(
	'default' => array(
		'hostname' => '10.104.177.231',
		'port'     => '11211',
		'weight'   => '1',
	),
    'default2' => array(
        'hostname' => '10.104.177.232',
        'port'     => '11211',
        'weight'   => '1',
    ),
);
