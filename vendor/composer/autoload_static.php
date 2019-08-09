<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1459c40c2602eee3ff0080853c0ae45a
{
    public static $files = array (
        '673d02b57df8f6d75b622cf6030bc00b' => __DIR__ . '/..' . '/landscapeinstitute/wp-github-plugin-updater/updater.php',
    );

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

    public static $classMap = array (
        'liod\\core\\core' => __DIR__ . '/../..' . '/classes/core/core.php',
        'liod\\core\\event' => __DIR__ . '/../..' . '/classes/core/event.php',
        'liod\\core\\post' => __DIR__ . '/../..' . '/classes/core/post.php',
        'liod\\core\\video' => __DIR__ . '/../..' . '/classes/core/video.php',
        'liod\\custom_post_types\\custom_post_type' => __DIR__ . '/../..' . '/classes/custom-post-types/custom-post-type.php',
        'liod\\custom_post_types\\event' => __DIR__ . '/../..' . '/classes/custom-post-types/event.php',
        'liod\\custom_post_types\\video' => __DIR__ . '/../..' . '/classes/custom-post-types/video.php',
        'liod\\custom_product_types\\custom_product_type' => __DIR__ . '/../..' . '/classes/custom-product-types/product-type.php',
        'liod\\custom_product_types\\event' => __DIR__ . '/../..' . '/classes/custom-product-types/event.php',
        'liod\\custom_product_types\\subscription' => __DIR__ . '/../..' . '/classes/custom-product-types/subscription.php',
        'liod\\helpers\\cmb2' => __DIR__ . '/../..' . '/helpers/cmb2.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1459c40c2602eee3ff0080853c0ae45a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1459c40c2602eee3ff0080853c0ae45a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1459c40c2602eee3ff0080853c0ae45a::$classMap;

        }, null, ClassLoader::class);
    }
}
