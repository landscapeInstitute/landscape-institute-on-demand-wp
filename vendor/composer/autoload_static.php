<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit54a7ca6baf5d8b331f4e1ec22c74080e
{
    public static $prefixLengthsPsr4 = array (
        'l' => 
        array (
            'liod\\helpers\\' => 13,
            'liod\\custom_product_types\\' => 26,
            'liod\\custom_post_types\\' => 23,
            'liod\\core\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'liod\\helpers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/helpers',
        ),
        'liod\\custom_product_types\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes/custom-product-types',
        ),
        'liod\\custom_post_types\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes/custom-post-types',
        ),
        'liod\\core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes/core',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit54a7ca6baf5d8b331f4e1ec22c74080e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit54a7ca6baf5d8b331f4e1ec22c74080e::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
