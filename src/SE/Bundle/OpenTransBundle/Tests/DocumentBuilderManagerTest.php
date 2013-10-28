<?php
/**
 * This file is part of the OpenTrans php library
 *
 * (c) Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SE\Bundle\OpenTransBundle\Tests;

/**
 *
 * @package SE\Bundle\OpenTransBundle\Tests
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 */
class DocumentBuilderManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @test
     */
    public function AddDocumentBuilder()
    {
        $manager = new \SE\Bundle\OpenTransBundle\DocumentBuilderManager();
        $factory = $this->getMockForAbstractClass('\SE\Component\OpenTrans\DocumentFactory\DocumentFactoryInterface');

        $this->assertEmpty($manager->getAllDocumentBuilder());

        $builder = new \SE\Component\OpenTrans\DocumentBuilder($factory);
        $name    = sha1(uniqid(microtime(), true));
        $manager->addDocumentBuilder($name, $builder);
        $this->assertSame($builder, $manager->getDocumentBuilder($name));
    }

    /**
     *
     * @test
     * @expectedException \SE\Bundle\OpenTransBundle\Exception\UnknownDocumentBuilderException
     */
    public function LoadUnknownBuilder()
    {
        $manager = new \SE\Bundle\OpenTransBundle\DocumentBuilderManager();
        $name    = sha1(uniqid(microtime(), true));
        $manager->getDocumentBuilder($name);
    }
}