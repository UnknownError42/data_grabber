<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// Datei öffnen, $handle ist der Dateizeiger

$such_array = array("euroweb","iom-websites");
$offline_array = array("Euroweb","Internet","Kurze","technische","Pause");
$ignore_array = array("domainname");
$work_array = array("domainname");

//$input_file ="91.199.247.0.csv";
$input_file ="network-91.199.247.0.csv";
$localpath=getenv("SCRIPT_NAME");
// a fix for Windows slashes



$base_path = realpath(basename(getenv("SCRIPT_NAME")));
$absolutepath=str_replace("\\","/",$base_path);


$docroot = basename($absolutepath,"csv_tools.csv");
$network_dir = dirname($base_path)."\\".basename($input_file,".csv");
$input_csv = fopen ($input_file,'r');
$output_csv = fopen($network_dir."/domains2check.csv", 'a');
$output_euroweb_csv = fopen($network_dir.'/euroweb_domains.csv', 'a');
$output_offline_csv = fopen($network_dir.'/offline_domains.csv', 'a');

echo "output_csv: ".$network_dir."/domains2check.csv <br>";
echo "output_euroweb_csv: ".$network_dir."/euroweb_domains.csv <br>";

echo "Verzeichnis ".$network_dir." erstellen...<br>";
// Prueft ob das Hauptverzeichnis bereits existiert,
    if(is_dir($network_dir)) {
    
        /* if(is_dir($network_dir."\\domains")) {
        echo 'Verzeichnis ist bereits vorhanden <br>';
        }  
        else {
          if ( mkdir ($network_dir, 0700 ) )
          {
            echo 'Verzeichnis '.$network_dir." erstellt!<br>";
          }else{ 
            echo 'Error: kann das Verzeichnis '.$network_dir.' nicht erstellen!<br> ';
          }
        }*/             
    
    }else{
// Erstellt das Verzeichnis
        if ( mkdir ($network_dir, 0700 ) )
        {
          echo 'Verzeichnis '.$network_dir.' erstellt! <br>';
        }else{ 
            echo 'Error: kann das Verzeichnis '.$network_dir.' nicht erstellen! <br>';
            return false; // Funktion verlassen 
        }
    }    


function contains($str, array $arr)
{
    foreach($arr as $a) {
        if (stripos($str,$a) !== false) return true;
    }
    return false;
}

echo "Filterung ... <br>";
// Datei zeilenweise auslesen, fgetcsv() anwenden, im Array $csv_array speichern
while (($csv_array = fgetcsv ($input_csv)) !== FALSE ) {
    //var_dump($csv_array);
   if ((contains($csv_array[0], $such_array)) OR (contains($csv_array[1], $offline_array))) {
      array_push($ignore_array, $csv_array);
      fputcsv($output_euroweb_csv, $csv_array);
  } else {
      array_push($work_array, $csv_array);
      fputcsv($output_csv, $csv_array);
  }
}

// Dateien schließen
fclose($output_csv);
fclose($input_csv);
fclose($output_euroweb_csv);
echo "Anzahl euroweb Domains: ".sizeof($ignore_array)."<br>";
echo "Anzahl Domains: ".sizeof($work_array)."<br>";



