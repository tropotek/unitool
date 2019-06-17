<?php
/*
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2007 Michael Mifsud
 *
 * Use this as the bootstrap file for all php files
 */
error_log('test');
$sitePath = dirname(__FILE__);
/** @var \Composer\Autoload\ClassLoader $composer */
$composer = include($sitePath . '/vendor/autoload.php');
include_once $sitePath.'/src/App/Bootstrap.php';

$config = \App\Config::getInstance();
$config->set('composer', $composer);


//\Tk\Request::setFactory(function (array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null) {
//    return new \Tk\Request($query, array_merge($query, $request), $attributes, $cookies, $files, $server, $content);
//});
$config->setRequest(\Tk\Request::createFromGlobals());
$config->setSession(\Tk\Session::getInstance()->start());

//$config['debug'] = true;
//$config['log.path'] = '/home/mifsudm/log/error.log';

$sitePath = dirname(__FILE__);
/** @var \Composer\Autoload\ClassLoader $composer */
$composer = include($sitePath . '/vendor/autoload.php');
\Tk\Config::getInstance()->setComposer($composer);

include_once $sitePath.'/src/App/Bootstrap.php';
