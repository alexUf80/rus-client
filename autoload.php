<?php

function core_autoload($classname)
{
    if (file_exists(__DIR__.'/core/'.$classname.'.php'))
        require __DIR__.'/core/'.$classname.'.php';
    if (file_exists(__DIR__.'/models/'.$classname.'.php'))
        require __DIR__.'/models/'.$classname.'.php';
    if (file_exists(__DIR__.'/controllers/'.$classname.'.php'))
        require __DIR__.'/controllers/'.$classname.'.php';
    if (file_exists(__DIR__.'/scorings/'.$classname.'.php'))
        require __DIR__.'/scorings/'.$classname.'.php';
    if (file_exists(dirname(__FILE__).'/vendor/autoload.php'))
        require dirname(__FILE__).'/vendor/autoload.php';
    if (file_exists(__DIR__.'/tools/'.$classname.'.php'))
        require __DIR__.'/tools/'.$classname.'.php';
    if (file_exists(__DIR__.'/api/'.$classname.'.php'))
        require __DIR__.'/api/'.$classname.'.php';
}
spl_autoload_register('core_autoload');
