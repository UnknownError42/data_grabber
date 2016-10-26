<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "include/snoopy.class.php";
function contains($str, array $arr){
    foreach($arr as $a) {
        if (stripos($str,$a) !== false) return true;
    }
    return false;
}
function in_arrayi($needle, $haystack) {
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }
function array_find($needle, array $haystack){
    foreach ($haystack as $i => $value) {
        if (false !== ($pos = stripos($needle, $value))) {
            return array(
                'line' => $i,
                'value' => $value,
                'pos' => $pos
            );
        }
    }
    return false;
}

function getImpressum($domain){
$crawler = new Snoopy;
// need an proxy?:
//$snoopy->proxy_host = "my.proxy.host";
//$snoopy->proxy_port = "8080";
// set browser and referer:
$crawler->agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
$crawler->referer = "http://www.jonasjohn.de/";
// set some cookies:
$crawler->cookies["SessionID"] = '238472834723489';
$crawler->cookies["favoriteColor"] = "blue";
// set an raw-header:
$crawler->rawheaders["Pragma"] = "no-cache";
// set some internal variables:
$crawler->maxredirs = 2;
$crawler->offsiteok = true;
$crawler->expandlinks = false;
// set username and password (optional)
//$snoopy->user = "joe";
//$snoopy->pass = "bloe";
$crawler->fetchlinks('http://'.$domain);
$link_array = $crawler->results;
$needle = "impressum";
foreach ($link_array as $i => $line) {
    //echo "check: " . $line . ".. "; 
    if (false !== mb_strpos(strtolower($line), $needle)) {
        echo $line;
        $link = $domain.$line;
        //var_dump($link_array);
        return $link;
        //exit;
        
    } else {
       //return false; 
    }
}
return $link;


}



//$work_array = array("domainname","impressum_url","kontakt_url","checktime");
$such_array = array("euroweb","iom-websites");


$input_file = "network-91.199.247.0/domains2check.csv";
$input_csv = fopen($input_file,'r');
$output_csv = fopen("domains2linkcheck.csv", 'a');
$output_ignore_csv = fopen("domains_wo_links.csv", 'a');
$counter_i = 0;$counter_w = 0;$counter_all = 0;

// aussortieren aller domains nach filter such_array 
echo " Domains eingelesen und filtern... ";
// wiederhole für alle elemente im array
while (($csv_array = fgetcsv ($input_csv)) !== FALSE ) {
    $impressum = "";
	$work_array = array();
    // wenn impressum url enthalten
    $impressum = getImpressum($csv_array[0]);
    var_dump($impressum);
    if (($impressum !==FALSE) AND (sizeof($impressum)> 0)){
        array_push($work_array, $csv_array[0],$impressum);
        //$work_array[] = '"'.$csv_array[0].'","'.$impressum.'"';
        // adde domain zum domains2linkcheck.csv file
        fputcsv($output_csv, $work_array);
        $counter_w = $counter_w+1;
  } else {
      // adde daten zum work_array
      //array_push($work_array, $csv_array);
      // adde daten zum domains_wo_links.csv
      fputcsv($output_ignore_csv, $csv_array);
      $counter_i = $counter_i+1;
  }
  //$temp = getImpressum($csv_array[0]);
  //echo $temp;
   $counter_all=$counter_all+1;
}

echo $counter_all." Domains eingelesen (".$counter_i." ignoriert / ".$counter_w. "good) <br>";




echo "Links von Domain www.volvo-moll.de : <br>";



exit;

if (false !== ($link_key = array_find($needle, $link_array))) {
    echo "gefunden";
    var_dump($link_key);
}


exit;

if (in_array($needle, $link_array)){
    $link_key=array_find($needle, $link_array);
    var_dump($link_key);
}

echo "$link_array:";
var_dump($link_array);
echo "<br>";
echo "in_array('impressum', $link_array):";
var_dump(in_array("impressum", $link_array));
echo "<br>";

echo "<br>some infos:<br>";
var_dump($needle);
var_dump(contains($needle, $link_array));

