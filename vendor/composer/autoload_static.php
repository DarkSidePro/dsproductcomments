<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit504bc7e14e8c97cb23be3847722d1c9e
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'Gumlet\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Gumlet\\' => 
        array (
            0 => __DIR__ . '/..' . '/gumlet/php-image-resize/lib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit504bc7e14e8c97cb23be3847722d1c9e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit504bc7e14e8c97cb23be3847722d1c9e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit504bc7e14e8c97cb23be3847722d1c9e::$classMap;

        }, null, ClassLoader::class);
    }
}
