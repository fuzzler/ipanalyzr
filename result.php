<br>
<a style="color:blue" href="reset.php">Torna Indietro </a>
<br><br>
<?php
$pagename="Risultati";

// Requirements
require_once 'functions.php';
require_once 'template.php';

// Variabili 

$sm = "Non impostata";
$sn = "Non impostato";

$ipbin="";
$smbin="";
$netbin="";
$net="";
$class="";
$tipo="";
$bbin=""; // brouadcast binario
$broadcast="";
$n_host=0;
$listaHost = [];

// Verifico la validità dell'ip
if (filter_var($_POST['ip'], FILTER_VALIDATE_IP)) {
    $ip = $_POST['ip'];
    $_SESSION['ip'] = $ip;
} else {
    $_SESSION['err_invalid_ip'] = "L'indirizzo IP inserito NON è valido!";
    header("Location: index.php");
}

// verifico che sia stato passato almeno un parametro per subnettare
if($_POST['sn'] == "" && $_POST['sm'] == "") {
    $_SESSION['err_no_subnet'] = "Devi indicare una Subnet Mask o un numero per il subnetting almeno";
    header("Location: index.php");
}
else {
    if($_POST['sn'] == "") {
        // In assenza di Subnet Number -> Verifico la validità della sm //$_POST['sm'] != "" && 
        if (filter_var($_POST['sm'], FILTER_VALIDATE_IP)) {
            $sm = $_POST['sm'];
            $_SESSION['sm'] = $sm;
        }
        else {
            $_SESSION['err_invalid_sm'] = "La Subnet Mask inserita NON è valida!";
            header("Location: index.php");        
        }
    }
    else {
        $sn = $_POST['sn'];
    }
}

// Debug: var_dump($_POST);



echo "<h3>Dati trasmessi</h3>";
echo "IP:".$ip."<br>";
echo "SM:".$sm."<br>";


$ipbin=ipToBinary($ip);
$smbin=ipToBinary($sm);
$class=whichClass($sm);
$netbin=findNet($ipbin,$smbin);
$net=ip2Dec($netbin); 
$bbin=findBroad($ipbin,$smbin); // Broadcast in formato binario
$broadcast=ip2Dec($bbin);
$listaHost=listNetworkIp($net,$broadcast);
$n_host = count($listaHost);

// Calcolo il Tipo con la distanza di Levenshtein
if(levenshtein($ip,$net) == 0) {
    $tipo = "Rete";
}
elseif(levenshtein($ip,$broadcast) == 0) {
    $tipo = "Broadcast";
}
else {
    $tipo = "Host";
}


echo "<br><u><h2>Analisi</h2></u>";
echo "<br>IP Binario: <b>".$ipbin;
echo "</b><br>SM Binario: <b>".$smbin;
echo "</b><br>Indirizzo di rete (binario): <b>".$netbin."</b> ";
echo "<br>Indirizzo di Broadcast (binario): <b>".$bbin;

echo "</b><br><br> Indirizzo di Rete (decimal): <b>".$net;
echo "</b><br>Indirizzo di Broadcast: <b>".$broadcast;

echo "</b><br>Classe di rete: <b>".$class;
echo "</b><br>Tipo di indirizzo: <b>".$tipo;
echo "</b><br>N° IP Host disponibili: <b>".$n_host;

echo "</b><br><br><u><b><h3>Lista Indirizzi Disponibili:</h3></b></u>";

if(count($listaHost) == 0) {
    echo "Impossibile calcolare Numero di Host e indirizzi (prob rete di classe A o B => troppi host da listare)";
}
else {
    foreach($listaHost as $lh) {
        echo "<br>$lh";
    }
}



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
<a style="color:blue" href="reset.php">Torna Indietro </a>