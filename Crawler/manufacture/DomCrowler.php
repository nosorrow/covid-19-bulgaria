<?php

namespace Manufacture;

class DomCrowler
{
    public $xpath;
    public $regex;
    private $response;

    /**
     * Mz constructor.
     */
    public function __construct($response)
    {
        $response = html_entity_decode($response);
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * @param $regex
     * @return $this
     */
    public function setRegex($regex)
    {
        $this->regex = $regex;
        return $this;
    }

    /**
     * @return \DOMNodeList|false
     */
    public function getContext()
    {
        $doc = new \DOMDocument();

        libxml_use_internal_errors(true);
        $doc->loadHTML($this->getResponse());
        libxml_use_internal_errors(false);

        $xpath = new \DOMXpath($doc);

        return $xpath->query($this->getXpath());
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function getXpath()
    {
        return $this->xpath;
    }

    /**
     * Xpath query
     * @param mixed $xpath
     * @return DomCrowler
     */
    public function setXpath($xpath)
    {
        $this->xpath = $xpath;
        return $this;
    }
}
