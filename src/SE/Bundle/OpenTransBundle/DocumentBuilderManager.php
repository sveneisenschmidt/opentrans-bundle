<?php
/**
 * This file is part of the OpenTrans php library
 *
 * (c) Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SE\Bundle\OpenTransBundle;

use SE\Bundle\OpenTransBundle\Exception\UnknownDocumentBuilderException;
use SE\Component\OpenTrans\DocumentBuilder;

/**
 *
 * @package SE\Bundle\OpenTransBundle
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 */
class DocumentBuilderManager
{
    /**
     *
     * @var \SE\Component\OpenTrans\DocumentBuilder[]
     */
    protected $builders = array();

    /**
     * @param $name
     * @param \SE\Component\OpenTrans\DocumentBuilder $builder
     */
    public function addDocumentBuilder($name, DocumentBuilder $builder)
    {
        $this->builders[$name] = $builder;
    }

    /**
     * @param string $name
     * @throws \SE\Bundle\OpenTransBundle\Exception\UnknownDocumentBuilderException
     * @return \SE\Component\OpenTrans\DocumentBuilder
     */
    public function getDocumentBuilder($name)
    {
        if(isset($this->builders[$name]) === false) {
            throw new UnknownDocumentBuilderException(sprintf('Unknown document builder %s.', $name));
        }

        return $this->builders[$name];
    }

    /**
     *
     * @return \SE\Component\OpenTrans\DocumentBuilder[]
     */
    public function getAllDocumentBuilder()
    {
        return $this->builders;
    }
}