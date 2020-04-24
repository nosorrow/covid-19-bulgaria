<?php
set_time_limit(300);
include_once 'Crawler/vendor/autoload.php';

use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;
use Manufacture\ManufactureCurl;

class MzParser
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
     * @param mixed $regex
     * @return DomCrowler
     */
    public function setRegex($regex)
    {
        $this->regex = $regex;
        return $this;
    }

    public function getContext()
    {
        $doc = new DOMDocument();

        libxml_use_internal_errors(true);
        $doc->loadHTML($this->getResponse());
        libxml_use_internal_errors(false);

        $xpath = new DOMXpath($doc);

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

function getCOVI19($url)
{
    try {
        $request = new ManufactureCurl($url);
        $response = $request->getResponse(false);

    } catch (Exception $e) {
        echo $e->getMessage();
    }

    $parse = new DomCrowler($response);
    $parse->setXpath('//*[@id="top"]/div/div/div[1]/div');
    $elements = $parse->getContext();

    $mz = function () use ($elements) {

        if ($elements !== null) {
            foreach ($elements as $element) {
                $nodes = $element->childNodes;
                foreach ($nodes as $node) {
                    //echo $node->nodeValue. "\n";
                    // Броят на починалите е+[^.!?]*[.!?]
                    preg_match('#(?:както следва:)([\S\s]+)(?:\.[^.]?)$#um',
                        $node->nodeValue, $match);
                    if ($match) {
                        return $match[1];
                    }
                }
            }
            return false;
        }
    };

    if ($mz()) {
        $stat = explode(';', $mz());
        $stats = array_map(function ($item) {
            return trim($item);
        }, $stat);

        // $dd = new Crawler($response);
        // $date = $dd->filter('.newsdate li time')->text();
        $parse->setXpath('//*[@id="top"]/div/div/div[1]/ul[1]/li/time');
        $dd = $parse->getContext();
        foreach ($dd as $d) {
            $date = $d->textContent;
        }
     //   return json_encode([$date => $stats], JSON_UNESCAPED_UNICODE);

        return [$stats];
    }

    return false;
}


function parseMzLinks($url)
{
    try {
        $request = new ManufactureCurl($url);
        $response = $request->getResponse(false);

    } catch (Exception $e) {
        echo $e->getMessage();
    }

    $crawler = new Crawler($response, null, $url);

    return $crawler->filter('.news-list li h2 a')->each(function (Crawler $node, $i) {
        $link = $node->link();
        return $link->getUri();
    });

}

$uris = parseMzLinks('https://www.mh.government.bg/bg/novini/aktualno/');

foreach ($uris as $uri) {
    $content = getCOVI19($uri);
    if ($content) {
        var_dump($content);
    }

}

/*$request = new ManufactureCurl('https://www.worldometers.info/coronavirus/');
$response = $request->getResponse(false);

$crawler = new Crawler($response);
$bgCovid19 = $crawler->filterXpath('//*[@id="main_table_countries_today"]/tbody[1]/tr[78]')
    ->each(function ($node, $i){
        $text = $node->text();
        return explode(' ', $text);
    });

var_dump($bgCovid19);

$title = $crawler->filterXPath('//*[@id="main_table_countries_today"]/thead/tr')
    ->each(function (Crawler $node, $i){
        echo $node->text();
    });*/

