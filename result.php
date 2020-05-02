<br>
<div class="row">
    <div class="col-2">
        <a style="color:blue" href="reset.php">Torna Indietro </a>
    </div>
</div>
<div class="row">
    <div class="col-2"></div>
    <div class="col-8 txtcenter">
        <h1 class="titolog">Stampa dei Risultati</h1>
    </div>
    <div class="col-2"></div>
</div>

<div class="row">
    <div class="col-2"></div>
    <div class="col-8 txtcenter">

<?php
$pagename="Risultati";

// Requirements
require_once 'functions.php';
require_once 'template.php';

// Variabili 

$sm = "";
$sn = "";
$ipbin="";
$smbin="";
$netbin="";
$net="";
$class="";
$tipo="";
$bbin=""; // broadcast binario
$broadcast="";
$n_host=0;
$listaHost = [];
$otherNet = []; // altre reti nel subnetting (applicando la regola del numero magico)

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
            $smbin = ipToBinary($sm);
            $sn = nSmFromIp($smbin);
        }
        else {
            $_SESSION['err_invalid_sm'] = "La Subnet Mask inserita NON è valida!";
            header("Location: index.php");        
        }
    }
    else {
        $sn = $_POST['sn'];
        $smbin = nSmToBinIp($sn);
        $sm = ipToDec($smbin);
    }
}

// Debug: var_dump($_POST);


?>

<br>
<table class="marginzero table table-striped" style="color:white;">
    <thead class="thead-light"><u><h2 class="txtcenter">Indirizzi inseriti</h2></u></thead>
    <tr>
        <td>IP Address: </td><td><b><?php echo "$ip / $sn" ?></b></td>
    </tr>
    <tr>
        <td>Subnet Mask:<td><b><?php echo $sm;?></b></td>
    </tr>
</table>

<?php


//echo "SM (number format):".$sn."<br>";

//if(filter_var($_POST['sm'], FILTER_VALIDATE_IP)) {}else {$smbin=ipToBinary($sm);}


$ipbin=ipToBinary($ip);
$class=whichClass($sn,$ipbin);
$netbin=findNet($ipbin,$smbin);
$net=ipToDec($netbin); 
//$bbin=findBroad($ipbin,$smbin); // Broadcast in formato binario
$bbin=findBroad($sn,$netbin); // Broadcast in formato binario
$broadcast=ipToDec($bbin);
$listaHost=listNetworkIp($net,$broadcast,$sn);
$n_host = $listaHost['nhost'];
$primo=$listaHost['primo'];
$ultimo=$listaHost['ultimo']; // [count($listaHost)-1]; -> nell'altro metodo
if($class=="Classless")
    $allSubnets=calcSubnets($net, $smbin); // calcola le altre subnet


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
?>
<br>
<table class="marginzero table table-striped" style="color:white; letter-spacing: 2px;">
    <thead><u><h2 class="txtcenter">Analisi</h2></u></thead>
    <tr>
        <td colspan="2" class="txtcenter altbgcolor"><h5>Indirizzi in formato binario</h5></td>
    </tr>
        <td>IP Address</td>   <td class="txtright"><b><?=$ipbin?></b></td>
    </tr>    
        <td>Subnet Mask</td>   <td class="txtright"><b><?=$smbin?></b></td>
    </tr>
        <td>Network </td>   <td class="txtright"><b><?=$netbin?></b></td>
    </tr>
        <td>Broadcast</td>   <td class="txtright"><b><?=$bbin?></b></td>
    </tr>
        <td colspan="2" class="txtcenter altbgcolor"><h5>Indirizzi in formato decimale</h5></td>
    </tr>
        <td>Network</td>   <td class="txtright"><b><?=$net?></b></td>
    </tr>
        <td>Broadcast</td>   <td class="txtright"><b><?=$broadcast?></b></td>
    </tr>
        <td colspan="2" class="txtcenter altbgcolor"><h5>Altre Info</h5></td>
    </tr>
        <td>Classe</td>   <td class="txtright"><b><?=$class?></b></td>
    </tr>
        <td>Indirizzo di</td>   <td class="txtright"><b><?=$tipo?></b></td>
    </tr>
        <td>Numero IP disponibili</td>   <td class="txtright"><b><?=$n_host-2?></b></td>
    </tr>
    </tr>
        <td>Primo IP disponibile</td>   <td class="txtright"><b><?=$primo?></b></td>
    </tr>
    </tr>
        <td>Ultimo IP disponibile</td>   <td class="txtright"><b><?=$ultimo?></b></td>
    </tr>

</table>
</b><br><br>

<?php
// SUBNETS (Solo per indirizzi classless)

if(!empty($allSubnets)) {
    $cr = 1; // conta le righe
    $count = 0; // conta le celle da mettere in fila
    $countTot = 0; // conta il totale delle celle (confronto con array)

?>
<h3>LISTA DELLE POSSIBILI SUBNET</h3>
<h4>Totale Subnet creabili: <?=count($allSubnets)?></h4>

<table class="table table-bordered txtcenter" style="color:white;">
<tr>

<th>$cr</th>

<?php
    foreach($allSubnets as $sub) {
        echo "<td>$sub<td>";
        $count++;
        $countTot++;
        if($count == 5) {
            $count=0;
            echo "</tr><tr>";
            $cr++;
            if($countTot<count($allSubnets))
                echo "<th>$cr</th>";
        }
    }
?>

</tr>
</table> <!-- fine tabella subnets -->
<br>
<hr>
<?php
} // fine IF (esistono subnet -> indirizzo classless)
?>


<table class="table table-bordered txtcenter" style="color:white;">
    <thead><b><h3>Lista Indirizzi IP Disponibili nella rete <?=$net?></h3></b></thead>
    <tr>

<?php


$cr = 1; // conta le righe
$count = 0; // conta le celle da mettere in fila
$countTot = 0; // conta il totale delle celle (confronto con array)

if($listaHost['nhost'] > 2000) {
    echo "<h3>Il numero degli host è troppo elevato per elencarli tutti </h3>";
    echo "<h3>Primo IP: $primo &nbsp&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp&nbsp Ultimo IP: $ultimo</h3>";
}
else {
    echo "<th>$cr</th>";
    foreach($listaHost['lista'] as $lh) {
        
        echo "<td>$lh<td>";
        $count++;
        $countTot++;
        if($count == 5) {
            $count=0;
            echo "</tr><tr>";
            $cr++;
            if($countTot<count($listaHost['lista']))
                echo "<th>$cr</th>";
        }
    }
}
?>
    </tr>
</table>

<?php

$showthis = false; // mostra o meno il contenuto seguente

// AL MOMENTO IL CONTENUTO È DISABILITATO (ESPERIMENTI IN CORSO)
if($showthis) {
?>
    <br><br>
    <u><b><h3>Lista dei Nomi Host salvati in rete:</h3></u></b><br>
    <fieldset class="fs scheduler-border">


<?php

    // NON ANCORA IMPLEMENTATO -------------------------------------------------
    // restituisce la lista dei nomi host effettivamente salvati nella rete
    // ==> aggiungere la funzionalità che verifica quelli connessi...
    foreach($listaHost['hostnames'] as $hn) {

        echo "<span class=\"txt20\">$hn</span>";
    }
?>

    </fieldset>

        </div>
        <div class="col-2"></div>
    </div>

    <div class="row">
        <div class="col-2"></div>
        <div class="col-8 txtcenter"></div>
        <div class="col-2">
            <br><br><br><br><br>
            <a style="color:blue" href="reset.php">Torna Indietro </a>
        </div>
    </div>
<?php
} // fine IF showthis 

