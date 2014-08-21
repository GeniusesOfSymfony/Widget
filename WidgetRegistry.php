<?php
namespace Gos\Component\Widget;

use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class WidgetRegistry implements WidgetRegistryInterface
{
    /**
     * {@inheritdoc}
     */
    protected $widgets;

    public function __construct()
    {
        $this->widgets = [];
    }

    /**
     * {@inheritdoc}
     */
    public function addWidget(WidgetInterface $widget)
    {
        $this->widgets[$widget->getName()] = $widget;
    }

    /**
     * {@inheritdoc}
     */
    public function getWidget($alias)
    {
        if (!isset($this->widgets[$alias])) {
            throw new ServiceNotFoundException(sprintf('Widget %s is actually not load into the WidgetRegistry'), $alias);
        }

        return $this->widgets[$alias];
    }
}
