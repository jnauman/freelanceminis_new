<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
$dbParams = array(
    'database'  => 'flm_test',
    'username'  => 'flm_main',
    'password'  => 'OCi3hgplABmPrUCw',
    'hostname'  => 'mysql.freelanceminis.com'
);
return array(
    'db' => array(
        'driver'         => 'Pdo_Mysql',
        'dsn'            => 'mysql:dbname=flm_test;host=mysql.freelanceminis.com;',
        'username'       => 'flm_main',
        'password'       => 'OCi3hgplABmPrUCw',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter'
                    => 'Zend\Db\Adapter\AdapterServiceFactory',
        
           'Zend\Db\Adapter\Adapter' => function ($sm) use ($dbParams) {
                    $adapter = new BjyProfiler\Db\Adapter\ProfilingAdapter(array(
                        'driver'    => 'Pdo_Mysql',
                        'dsn'       => 'mysql:dbname='.$dbParams['database'].';host='.$dbParams['hostname'],
                        'database'  => $dbParams['database'],
                        'username'  => $dbParams['username'],
                        'password'  => $dbParams['password'],
                        'hostname'  => $dbParams['hostname'],
                    ));
     
                    $adapter->setProfiler(new BjyProfiler\Db\Profiler\Profiler);
                    $adapter->injectProfilingStatementPrototype();
                    return $adapter;
            },
        ),
    ),
);