<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticIniteb973afa7a1aeb1f976d85cb37938d0f
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'CronRun\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'CronRun\\' => 
        array (
            0 => __DIR__ . '/../..' . '/CronRun',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticIniteb973afa7a1aeb1f976d85cb37938d0f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticIniteb973afa7a1aeb1f976d85cb37938d0f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
