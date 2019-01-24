<?php
/**
 * This file is part of the OpenTrans php library
 *
 * (c) Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SE\Bundle\OpenTransBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

use SE\Component\OpenTrans\DocumentFactory\DocumentFactoryResolver;
use SE\Component\OpenTrans\NodeLoader;

/**
 *
 * @package SE\Bundle\OpenTransBundle
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 */
class SEOpenTransExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $documents = $config['documents'];
        foreach($documents as $id => $documentConfig) {

            $name = 'se.opentrans.document_builder'.$id;
            $type = $documentConfig['type'];

            $nodeLoaderInstance = new NodeLoader;
            $nodeLoaderDefinition = new Definition(trim(get_class($nodeLoaderInstance), '\\'));

            foreach($documentConfig['loader'] as $nodeName => $class) {
                $nodeLoaderDefinition->addMethodCall('set', array($nodeName, trim($class, '\\')));
                $nodeLoaderInstance->set($nodeName, trim($class, '\\'));
            }

            $factoryClass = DocumentFactoryResolver::resolveFactory($nodeLoaderInstance, $type);
            $factory = new Definition(trim($factoryClass, '\\'), array($nodeLoaderDefinition));
            $builder = new Definition('SE\Component\OpenTrans\DocumentBuilder', array($factory));

            $builder->addMethodCall('build');
            $builder->addMethodCall('load', array($documentConfig['document']));

            $container->setDefinition($name, $builder);

            $manager = $container->findDefinition('se.opentrans.document_builder_manager');
            $manager->addMethodCall('addDocumentBuilder', array($id, new Reference($name)));

            unset($nodeLoaderInstance);
        }
    }
}