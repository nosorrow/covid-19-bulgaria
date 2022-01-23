<?php
set_time_limit(300);
$x = include 'vendor/autoload.php';

use Symfony\Component\DomCrawler\Crawler;
use Manufacture\ManufactureCurl;
use Manufacture\DomCrowler;

//$uris = parseMzLinks('https://www.mh.government.bg/bg/novini/aktualno/');
//var_dump($uris);die;

//=====================================
class Mz
{

    private $url;

    public function __construct($url)
    {

        $this->url = $url;
    }

    public function getCOVI19($url)
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
                        preg_match("~(?:както следва:)([\S\s]+)(?:\.[^.]?)$~mU",
                            $node->nodeValue, $match);
                        if ($match) {
                            return $match[1];
                        }
                    }
                }
            }
            return false;

        };

        if ($mz()) {
            $stat = explode(';', $mz());
            $stats = array_map(static function ($item) {
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

            return $stats;
        }

        return false;
    }

    protected function parseMzLinks($url)
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

    protected function normalizeParsedData($data)
    {
        //the last in line in not Ok - 23 => 'Шумен – 4, Ямбол – 6'
        /*    $normalizedData = [];
            $pop = array_pop($data);
            $pop = explode(",", $pop);
            array_push($data, ...$pop);*/
        $data = $this->normalizeAnomaly($data);
        foreach ($data as $value) {
            $dash = "-";//html_entity_decode('&ndash;');

            $key = trim(strstr($value, $dash, true));
            $city = (int)ltrim(substr(strstr($value, $dash), 0), $dash);
            $normalizedData[$key] = $city;
        }
        return $normalizedData;
    }

    protected function normalizeAnomaly($arr)
    {
        $anomaly = [];
        $normlized = $arr;
        foreach ($arr as $key => $value) {
            if (strpos($value, ',') !== false) {
                $value = explode(',', $value);
                $value = array_map(function ($item) {
                    return trim($item);
                }, $value);
                array_push($anomaly, ...$value);
                unset($normlized[$key]);
            }
        }
        $normlized = (array_merge(array_values($normlized), $anomaly));

        return $normlized;
    }

    public function getUrls()
    {
        return $this->parseMzLinks($this->url);
    }

    public function printCovid19($url){

        $parsedRaWCitiesNew = $this->getCOVI19($url);

        $cities = [];
        if ($parsedRaWCitiesNew !== false) {
            foreach ($parsedRaWCitiesNew as $valuecity) {
                $cities[]['name'] = $valuecity;
            }
        } else {
           // trigger_error("TRIGGER ERROR: Nothing to show! Possible wrong URL!", E_USER_ERROR);
            echo "Not found: Possible wrong URL!\n";
            return false;
        }

        $json = (json_encode(['cities'=>$cities], 256));

        $data =  trim(var_export($json, true), "'");
        file_put_contents('../data/mun.json', $data);

        echo $data;

    }
}
