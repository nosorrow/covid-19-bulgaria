<?php
/*
 * https://hidemy.name/en/proxy-list/?ports=8080&type=s#list
 */
/**
 * Class ManufactureScraper
 */
namespace Manufacture;

class ManufactureCurl
{
    private $url;

    private $useragent = [
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.140 Safari/537.36 Edge/18.17763",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36",
        "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)",
        "Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)",
        "Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.133 Mobile Safari/535.19",
        "Mozilla/5.0 (Linux; Android 6.0.1; SM-G920V Build/MMB29K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36",
        "Mozilla/5.0 (iPad; CPU OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1",
        "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:52.0) Gecko/20100101 Firefox/52.0"
    ];

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getResponse($useProxy = false)
    {
        $useragent = $this->getUserAgent();
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => $useragent,
            CURLOPT_HTTPHEADER => [
                "referer: http://www.google.com"
            ],
          //  CURLOPT_SSL_VERIFYPEER => 1,
        ]);

        if ($useProxy === true) {
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1 );
         //   curl_setopt($ch, CURLOPT_PROXY, '189.240.124.61:8080');
            curl_setopt($ch, CURLOPT_PROXY, '51.38.71.101:8080');
        }

        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($result === false)
        {
            throw new \Exception("Proxy is not working: " . $err);
        }
        if ($err) {
            throw new \Exception('CURL error#: ' . $err);
        }
//        echo $useragent  . "\n" . $result;
        return $result;
    }

    private function getUserAgent()
    {
        $count = count($this->useragent);
        return $this->useragent[random_int(0, $count - 1)];
    }

}
/*
try {
   // $request = new ManufactureScraper('https://haspel.000webhostapp.com/test.php');
    $request = new ManufactureScraper('https://www.mh.government.bg/bg/novini/aktualno/422-sa-potvrdenite-sluchai-na-covid-19-u-nas');
    $request->getResponse(false);

} catch (Exception $e) {
    echo $e->getMessage();
}*/
