<?php
namespace Gos\Component\Widget;

interface WidgetInterface
{
    /**
     * @param array $parameters
     *
     * @return mixed
     */
    public function build(Array $parameters);

    /**
     * @return mixed
     */
    public function getTemplate();

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param $template
     *
     * @return mixed
     */
    public function setTemplate($template);

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function setData(Array $data = array());
}
