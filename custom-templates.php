<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;

/**
 * Class CustomTemplatesPlugin
 * @package Grav\Plugin
 */
class CustomTemplatesPlugin extends Plugin
{
    /**
     * @return array
     *
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => [
                ['autoload', 100000], // TODO: Remove when plugin requires Grav >=1.7
                ['onPluginsInitialized', 0]
            ]
        ];
    }

    /**
    * Composer autoload.
    *
    * @return ClassLoader
    */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Initialize the plugin
     * 
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        $this->enable([
		'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0]
        ]);

    }

    /**
     * Add data/templates directory to Twig lookup paths
     *
     */
    public function onTwigTemplatePaths()
    {
        // If the location for custom templates does not exist create it
        $user_dir = $this->grav['locator']->findResource('user://');
        if (!file_exists($user_dir . '/data/templates')) {
            mkdir($user_dir . '/data/templates', 0775, true);
        }

        // Add local templates folder to the Twig templates search path
        $this->grav['twig']->twig_paths[] = $this->grav['locator']->findResource('user://') . '/data/templates';

    }

}
