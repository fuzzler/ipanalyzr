<?php
$pagename="Risultati";

// Requirements
require_once 'functions.php';
require_once 'template.php';

// Variabili
$ip = $_POST['ip'];
$sm = $_POST['sm'];
$ipbin="";
$smbin="";
$netbin="";
$net="";
$class="";

echo "<h3>Dati trasmessi</h3>";
echo "IP:".$ip."<br>";
echo "SM:".$sm."<br>";


$ipbin=ipToBinary($ip);
$smbin=ipToBinary($sm);
$class=whichClass($sm);
$netbin=findNet($ipbin,$smbin);
$net=net2Dec($netbin);

echo "<br><h3>Analisi</h3>";
echo "<br>IP2BIN: ".$ipbin;
echo "<br>SM2BIN: ".$smbin;
echo "<br>AND (Net): ".$netbin." (binary)";
echo "<br> NET (decimal): ".$net;
echo "<br>Class: ".$class;



/*

$t = (int) 10.34/2;
$v = "0"."1";
echo "T:".$t;
echo "V:".$v;

$t = decbin(64);//decbin();
echo "IP2BIN:".decbin($t);
$char = chr(bindec($t));
echo "<br><br>D2B:".$t."->".count($t).gettype($t).strlen($t);
echo "<br><br>char:".$char."->".count($char).gettype($char).strlen($char);
*/

?>
<br><br><br><br><br>
<a style="color:blue" href="index.php">Torna Indietro </a>