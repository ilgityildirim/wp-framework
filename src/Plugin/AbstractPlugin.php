<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Plugin;

use TripleBits\WpFramework\Adapter\Directory;
use TripleBits\WpFramework\Adapter\Hooks;
use TripleBits\WpFramework\Container\Container;
use TripleBits\WpFramework\Filesystem\Filesystem;

abstract class AbstractPlugin implements PluginInterface
{
    /** @var Container */
    protected $container;

    /** @var array */
    protected $components = [];

    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->initDependencies();
        $this->registerLifeCycle();
        $this->loadLanguages();
    }

    public function init(): void
    {
        /** @var Hooks $hooks */
        $hooks = $this->container->get(Hooks::class);

        foreach ($this->components as $id => $options) {
            $this->container->setInitialized($id, $options);
        }

        $hooks->init();
    }

    public function getSlug(): ?string
    {
        return $this->container->getParameter('slug');
    }

    /** @noinspection PhpUnused */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /** @noinspection PhpUnused */
    public function addComponent(string $id, array $options = []): void
    {
        if (array_key_exists($id, $this->components)) {
            return;
        }

        $this->components[$id] = $options;
    }

    /** @noinspection PhpUnused */
    public function removeComponent(string $id): void
    {
        $key = array_search($id, $this->components, true);
        if (false === $key) {
            return;
        }

        unset($this->components[$key]);
        $this->container->remove($id);
    }

    private function loadLanguages(): void
    {
        /** @var Directory|null $directory */
        $directory = $this->container->get(Directory::class);

        /** @noinspection NullPointerExceptionInspection */
        $languagesDirectory = $directory->getPluginDirectory() . 'languages' . DIRECTORY_SEPARATOR;

        // Set filter for plugins languages directory
        $languagesDirectory = apply_filters($this->getSlug() . '_languages_directory', $languagesDirectory);

        // Traditional WP plugin locale filter
        $locale             = apply_filters('plugin_locale', get_locale(), $this->getSlug());
        $moFile             = sprintf('%1$s-%2$s.mo', $this->getSlug(), $locale);

        // Setup paths to current locale file
        $moFileLocal        = $languagesDirectory . $moFile;
        $moFileGlobal       = WP_LANG_DIR . DIRECTORY_SEPARATOR . $this->getSlug() . DIRECTORY_SEPARATOR . $moFile;

        if (file_exists($moFileGlobal)) {
            load_textdomain($this->getSlug(), $moFileGlobal);
        }
        elseif (file_exists($moFileLocal)) {
            load_textdomain($this->getSlug(), $moFileLocal);
        }
        else {
            load_plugin_textdomain($this->getSlug(), false, $languagesDirectory);
        }
    }

    private function initDependencies(): void
    {
        $this->container->set(Hooks::class, new Hooks);
        $this->container->set(Directory::class, new Directory(new Filesystem, $this->getSlug()));
    }

    private function registerLifeCycle(): void
    {
        /** @noinspection NullPointerExceptionInspection */
        $file = $this->container->get(Directory::class)->getPluginDirectory() . $this->getSlug() . '.php';

        if (method_exists($this, 'onActivation')) {
            register_activation_hook($file, [$this, 'onActivation']);
        }

        if (method_exists($this, 'onDeactivate')) {
            register_deactivation_hook($file, [$this, 'onDeactivate']);
        }

        if (method_exists($this, 'onUninstall')) {
            // $this does not work hence the usage of static::class
            register_uninstall_hook($file, [static::class, 'onUninstall']);
        }
    }
}
