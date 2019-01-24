<?php
/**
 * This file is part of the OpenTrans php library
 *
 * (c) Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SE\Bundle\OpenTransBundle\Tests\DependencyInjection;

use SE\Bundle\OpenTransBundle\SEOpenTransBundle;

use Symfony\Component\DependencyInjection\Compiler\ResolveParameterPlaceHoldersPass;
use Symfony\Component\DependencyInjection\Compiler\ResolveDefinitionTemplatesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 *
 * @package SE\Bundle\OpenTransBundle\Tests
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 */
class SEBmecatBundleExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @test
     */
    public function DocumentBuilderManagerRegisteredAsService()
    {
        $container = $this->getContainerForConfig(array(array()));
        $service   = $container->get('se.opentrans.document_builder_manager');

        $this->assertInstanceOf('\SE\Bundle\OpenTransBundle\DocumentBuilderManager', $service);
    }

    /**
     *
     * @test
     */
    public function LoadsBuilderFromConfig()
    {
        $name = sha1(uniqid(microtime(), true));
        $config = array(
            array(
                'documents' => array($name => array('type' => 'order', 'document' => array()) )
            )
        );

        $container = $this->getContainerForConfig($config);
        $service   = $container->get('se.opentrans.document_builder_manager');
        $builder   = $service->getDocumentBuilder($name);
    }

    /**
     *
     * @test
     */
    public function LoadNodeLoaderMapping()
    {
        $name = sha1(uniqid(microtime(), true));
        $node = $this->getMock('SE\Component\OpenTrans\Node\Order\DocumentNode', array(), array(), 'Fixture_Order_Document_Node'.$name);

        $config = array(
            array(
                'documents' => array($name => array('type' => 'order', 'document' => array(), 'loader' => array(
                    'node.order.document' => get_class($node)
                )) )
            )
        );

        $container = $this->getContainerForConfig($config);
        $service   = $container->get('se.opentrans.document_builder_manager');
        $builder   = $service->getDocumentBuilder($name);
        $document  = $builder->getDocument();

        $this->assertInstanceOf(get_class($node), $document);
    }

    /**
     *
     * @test
     * @expectedException \SE\Bundle\OpenTransBundle\Exception\UnknownDocumentBuilderException
     */
    public function LoadsUnknownBuilderFromConfig()
    {
        $name = sha1(uniqid(microtime(), true));
        $config = array(
            array(
                'documents' => array()
            )
        );

        $container = $this->getContainerForConfig($config);
        $service   = $container->get('se.opentrans.document_builder_manager');
        $builder   = $service->getDocumentBuilder($name);
    }

    /**
     * @author Johannes M. Schmitt <schmittjoh@gmail.com>
     *
     * @param array $configs
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    private function getContainerForConfig(array $configs)
    {
        $bundle = new SEOpenTransBundle();
        $extension = $bundle->getContainerExtension();

        $container = new ContainerBuilder();
        $container->setParameter('kernel.debug', true);
        $container->setParameter('kernel.cache_dir', sys_get_temp_dir().'/opentrans-bundle');
        $container->setParameter('kernel.bundles', array());
        $container->set('service_container', $container);
        $container->registerExtension($extension);
        $extension->load($configs, $container);

        $bundle->build($container);

        $container->getCompilerPassConfig()->setOptimizationPasses(array(
            new ResolveParameterPlaceHoldersPass(),
            new ResolveDefinitionTemplatesPass(),
        ));
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        return $container;
    }
}