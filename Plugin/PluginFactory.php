<?php

declare(strict_types=1);

namespace App\Service\Plugin;

use App\Service\Container\Container;

class PluginFactory
{

    public static function make(string $pluginClass): PluginInterface
    {
        /** @noinspection PhpIncludeInspection */
        $config = require plugin_dir_path(__FILE__) . '../../config.php';
        $container = new Container($config ?? []);

        /** @var PluginInterface $plugin */
        $plugin = new $pluginClass($container);

        if (!isset($config['components'])) {
            return $plugin;
        }

        foreach ($config['components'] as $id => $options) {
            if (is_string($id) && is_array($options)) {
                $plugin->addComponent($id, $options);
                continue;
            }

            $plugin->addComponent($options);
        }

        return $plugin;
    }
}
