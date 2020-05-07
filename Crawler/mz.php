<?php
set_time_limit(300);
$x = include 'vendor/autoload.php';

use Symfony\Component\DomCrawler\Crawler;
use Manufacture\ManufactureCurl;
use Manufacture\DomCrowler;

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

        return $stats;
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

function normalizeParsedData($data)
{
    //the last in line in not Ok - 23 => 'Шумен – 4, Ямбол – 6'
/*    $normalizedData = [];
    $pop = array_pop($data);
    $pop = explode(",", $pop);
    array_push($data, ...$pop);*/
    $data = normalizeAnomaly($data);
    foreach ($data as $value) {
        $dash = html_entity_decode('&ndash;');
        $key = trim(strstr($value, $dash, true));
        $city = (int)ltrim(substr(strstr($value, $dash), 0), $dash);
        $normalizedData[$key] = $city;
    }
    return $normalizedData;
}

function normalizeAnomaly($arr)
{
    $anomaly = [];
    $normlized = $arr;
    foreach ($arr as $key => $value) {
        if (strpos($value, ',') !== false) {
            $value = explode(',', $value);
            $value = array_map(function ($item){
                return trim($item);
            }, $value);
            array_push($anomaly, ...$value);
            unset($normlized[$key]);
        }
    }
    $normlized = (array_merge(array_values($normlized), $anomaly));
    var_dump($normlized);

    return $normlized;
}

function cityCasesStringify($data)
{
    $toStr = [];
    foreach ($data as $key => $value) {
        $toStr[] = $key . '-' . $value;
    }
    return $toStr;
}

function substractionCases(array $new, array $old)
{
    $diff = array_diff_key($old, $new);
    $diff1 = array_diff_key($new, $old);
    var_dump($diff, $diff1);

  /*  $new = ($diff + $new);
    $old = ($old + $diff1);*/

    $substraction = [];
    $compareKeys1 = (array_keys($new));
    $compareKeys2 = (array_keys($old));
    sort($compareKeys1);
    sort($compareKeys2);
    arsort($new);
    arsort($old);
    //  var_dump($new, $old);

    if ($compareKeys1 !== $compareKeys2) {
        die('Проблем с данните => различни масиви на градовете');
    }
    foreach ($new as $k => $value) {
        $substraction[$k] = $value - $old[$k];

    }
    // var_dump($substraction);
    //  var_dump($new, $old, array_sum($substraction));die;
    return $substraction;
}
//===================================================================================

$uris = parseMzLinks('https://www.mh.government.bg/bg/novini/aktualno/');
//var_dump($uris);die;

$parsedRaWCitiesNew = getCOVI19($uris[0]);
$parsedRaWCitiesOld = getCOVI19($uris[2]);
//var_dump($parsedRaWCitiesNew, normalizeAnomaly($parsedRaWCitiesNew));

if ($parsedRaWCitiesNew) {
    $cities = normalizeParsedData($parsedRaWCitiesNew);
    $citiesOld = normalizeParsedData($parsedRaWCitiesOld);
    $substract = substractionCases($cities, $citiesOld);
    var_dump($cities, $citiesOld);
    arsort($cities);

    $citiesName = array_keys($cities);
    $data['cityCases'] = array_values($cities);
    $data['cityNames'] = $citiesName;
    $data['cities'] = [];
    $data['totalCases'] = array_sum($data['cityCases']);
    $data['totalNewCases'] = array_sum($substract);

    $i = 0;
    foreach ($cities as $k => $v) {
        $data['cities'][$i]['name'] = $k;
        $data['cities'][$i]['cases'] = $v;
        $data['cities'][$i]['newCases'] = $substract[$k];

        $i++;
    }

} else {
    die('No new information yet');
}

//var_dump($data['cities']);
$json = json_encode($data, JSON_UNESCAPED_UNICODE);
echo json_encode($data, JSON_UNESCAPED_UNICODE);

//file_put_contents('../data/bg.json', var_export($json,true));

/*
foreach ($uris as $uri) {
    $content = getCOVI19($uri);
    if ($content) {
        var_dump($content);
    }

}*/
