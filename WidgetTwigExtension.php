<?php
namespace Gos\Component\Widget;

use Twig_Extension;
use Twig_Function_Method;

class WidgetTwigExtension extends Twig_Extension
{
    /**
     * @var WidgetRegistryInterface
     */
    protected $widgetRegistry;

    /**
     * @param WidgetRegistryInterface $widgetRegistry
     */
    public function __construct(WidgetRegistryInterface $widgetRegistry)
    {
        $this->widgetRegistry = $widgetRegistry;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gos_widget';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'widget' => new Twig_Function_Method($this, 'renderWidget', array(
                'is_safe' => array('html'),
                'need_environment' => true
            ))
        );
    }

    /**
     * @param \Twig_Environment $environment
     * @param                   $alias
     * @param array             $parameters
     *
     * @return string
     */
    public function renderWidget(\Twig_Environment $environment, $alias, Array $parameters = array())
    {
        $widget = $this->widgetRegistry->getWidget($alias);
        $widget->build($parameters);

        return $environment->render($widget->getTemplate(), $widget->getOptions());
    }
}
