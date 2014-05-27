#Gos Widget Component#

**This project is currently in developpement, please take care.**

Installation
-------------

You need to have [composer](https://getcomposer.org/) to install dependencies.

```json
{
    "require": {
        "gos/widget": "{last stable version}"
    }
}
```

Then run the command on the root of your project`composer update`

How to use
------------

First create a widget class like

```php
<?php
namespace Gos\Bundle\I18nBundle\Widget\Locale;

use Gos\Component\Widget\Widget;

class LocaleSwitcherWidget extends Widget
{
    protected $localeEntityManager;
    protected $requestStack;

	/**
    * Use the constructor to inject your dependencies
    **/
    public function __construct(RequestStack $requestStack, LocaleEntityManager $localeEntityManager)
    {
        $this->localeEntityManager = $localeEntityManager;
        $this->requestStack = $requestStack;
    }

    /**
    * Method imposed by the interface
    **/
    public function build(Array $parameters)
    {
    	//Here I need the current request (See symfony/HttpFoundation)
        $request = $this->requestStack->getMasterRequest();

        //Set the template to be render
        $this->setTemplate('widget/locale_switcher.html.twig');

		//Data passed to the view
        $this->setData(array(
            'locales' => $this->localeEntityManager->getRepository()->findAllActive(),
            'current' => $request->getLocale(),
            'route' => array(
                'name' => $request->attributes->get('_route'),
                'parameters' => $request->attributes->get('_route_params')
            )
        ));
    }

    /**
     * @return mixed|string
     */
    public function getName()
    {
        /**
        * Define the name of the widget to retrieve it in the twig template
        **/
        return 'locale_switcher';
    }
}
```

Register your function to Twig.

```php
use Gos\Component\Widget\WidgetTwigExtension;

//Create widget registry instance, who contains your widget
$widgetRegistry = new WidgetRegistry();

//Arbitrary depedencies, related to your widget business
$localeSwitcherWidget = new LocaleSwitcherWidget($requestStack, $localeEntityManager);

//Add your widget on the widgetRegistry
$widgetRegistry->addWidget($localeSwitcherWidget);

//Add other ....
$widgetRegistry->add(...);

//Twig engine
$twig = new Twig_Environment($loader);

//Register the widget twig extension.
$twig->addExtension(new WidgetTwigExtension($widgetRegistry));
```

Then inside twig template

```twig
{{ widget('locale_switcher') }}
```

To retrieve manually your widget $widgetRegistry->getWidget('locale_switcher'); this will return your LocaleSwitcherWidget instance

Symfony2 Integration
---------------------

Create a compiler pass, for example `Acme\DemoBundle\DependencyInjection\Compiler\WidgetCompilerPass`
```php
<?php
namespace Acme\DemoBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class WidgetCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $widgetRegistryDefinition = new Definition('Gos\Component\Widget\WidgetRegistry');
        $container->setDefinition('gos.core_bundle.widget.registry', $widgetRegistryDefinition);

        $widgetTwigExtensionDefinition = new Definition('Gos\Component\Widget\WidgetTwigExtension');
        $widgetTwigExtensionDefinition->setArguments(array(new Reference('gos.core_bundle.widget.registry')));
        $widgetTwigExtensionDefinition->addTag('twig.extension');

        $container->setDefinition('gos.core_bundle.widget_twig_extension', $widgetTwigExtensionDefinition);
        $taggedServices = $container->findTaggedServiceIds('twig.widget');

        foreach ($taggedServices as $id => $tagAttributes) {
            $widgetRegistryDefinition->addMethodCall('addWidget', array(
                new Reference($id),
            ));
        }
    }
}
```

Register the compiler pass in your bundle class.

```php
<?php
namespace Acme\DemoBundle;

use Acme\DemoBundle\DependencyInjection\Compiler\WidgetCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AcmeDemoBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new WidgetCompilerPass());
    }
}

```

From now, simply registering your widget to twig as service like

```yaml
acme.demo_bundle.locale_switcher.widget:
    class: Acme\DemoBundle\Widget\LocaleSwitcherWidget
    arguments:
        - @request_stack
        - @gos.i18n_bundle.locale_entity.manager
    tags:
        - { name: twig.widget }
```

And now he is available from your template.


Running the tests:
------------------

PHPUnit 3.5 or newer together with Mock_Object package is required. To setup and run tests follow these steps:

* go to the root directory of fixture
* run: composer install --dev
* run: phpunit

License
---------

The project is under MIT lisence, for more information see the LICENSE file inside the project