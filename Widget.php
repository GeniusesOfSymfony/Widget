<?php
namespace Gos\Component\Widget;

abstract class Widget implements WidgetInterface
{
    /**
     * {@inheritdoc}
     */
    protected $template;

    /**
     * {@inheritdoc}
     */
    protected $data;

    /**
     * {@inheritdoc}
     */
    public function setData(Array $data = array())
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }
}
