<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Plugin;

use TripleBits\WpFramework\Container\Container;

class PluginFactory
{

    public static function make(string $pluginClass, array $config = []): PluginInterface
    {
        $container = new Container($config);

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
