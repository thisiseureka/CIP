<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitcde19e80d033b3a074cd886a1cca4ff5
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitcde19e80d033b3a074cd886a1cca4ff5', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitcde19e80d033b3a074cd886a1cca4ff5', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitcde19e80d033b3a074cd886a1cca4ff5::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
