<?php


namespace TripleBits\WpFramework\Component;


use TripleBits\WpFramework\Adapter\Hooks;
use TripleBits\WpFramework\Container\Container;

abstract class AbstractContainerAwareComponent extends AbstractComponent
{
    /** @var Container */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;

        /** @var Hooks $hooks */
        $hooks = $container->get(Hooks::class);
        parent::__construct($hooks);
    }

    protected function get(string $id): ?object
    {
        return $this->container->get($id);
    }

    protected function getSlug(): ?string
    {
        // TODO perhaps const?
        return $this->container->getParameter('slug');
    }

}
