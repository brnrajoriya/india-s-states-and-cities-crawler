<?php

$targetUrl = "https://sites.google.com/site/ashishright/collections/Statewise-Cities-In-India";
$results_page = curl($targetUrl);

$mainSection = scrape_between($results_page, '<font face="trebuchet ms,sans-serif" size="2">', '<a name="TOC-Acknowledgment">');
$mainSection = scrape_between($mainSection, '<h3>', 'Sources/External Links ');
$altImgLi = explode("<a name=", $mainSection);

echo "[<br>";
$i = 1;
foreach ($altImgLi as $k => $altImgPath) 
{
    
    if($k>0)
    {

        $state = scrape_between($altImgPath, '"TOC-', ']');
        $state = scrape_between($state, '>', ' [');
        $cities = explode(".", $altImgPath);
        foreach ($cities as $l => $city) {
            if($l>0)
            {
                $cityw = scrape_between($city, ' ', ' ');
                echo "&nbsp;&nbsp;&nbsp;&nbsp;{<br>";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\"id\": \"".$i."\",<br>";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\"name\": \"".$cityw."\",<br>";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\"state\": \"".$state."\"<br>";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;},<br>";
                $i ++;
            }
        }
    }
        
    
}
echo "]";

function curl($url) 
{ 
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, false );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 120 );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 120 );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    $content = curl_exec( $ch );
    $response = curl_getinfo( $ch );
    curl_close ( $ch );    // Closing cURL 
    return $content;   // Returning the data from the function 
} 

// Defining the basic scraping function 
function scrape_between($data, $start, $end)
{ 
    set_time_limit(300);
    $data = stristr($data, $start); // Stripping all data from before $start 
    $data = substr($data, strlen($start));  // Stripping $start 
    $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape 
    $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape 
    return $data;   // Returning the scraped data from the function 
} 