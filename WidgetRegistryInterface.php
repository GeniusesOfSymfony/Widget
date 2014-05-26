<?php
namespace Gos\Component\Widget;

interface WidgetRegistryInterface
{
    /**
     * @param WidgetInterface $widget
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public function addWidget(WidgetInterface $widget);

    /**
     * @param $alias
     *
     * @return mixed
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function getWidget($alias);
}
