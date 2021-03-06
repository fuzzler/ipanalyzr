<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//ini_set('memory_limit', '-1');
error_reporting(E_ALL);

function ipToBinary($ip) {

    $ipbin="";

    $ip2bconv = explode('.',$ip);

    for($i=0; $i<count($ip2bconv); $i++){

        $tmp = intval($ip2bconv[$i]);

        // per evitare di aggiungere il punto alla fine
        if($i<3) {
            $dot=".";
        }
        else {
            $dot="";
        }

        $ipbin.=decToBinary($tmp).$dot;
        //echo "<br>".gettype($tmp).$ip2bconv[$i]."<br>";
    }

    return $ipbin;
}

function decToBinary($n) {

    $r = "";
    $t = "";
    $diff = "";

    // Calcolo IP (da ribaltare)
    while($n >= 1) {
        //$i++;echo "Bit: $i ) R: $r ==> N: $n <br>";        
        $r.= (string)($n%2==0)?"0":"1";//genero binario con algoritmo classico
        (int) $n=(int)$n/2;        
    }
    //DEBUG: echo "FINAL **** Bit: $i ) R: $r ==> N: $n <br>";

    // devo ribaltare il binario (proprio come nell'algoritmo)
    for($i=strlen($r)-1; $i>=0; $i--) {
        $t.=$r[$i];
        //DEBUG: echo "R: $r ==> Rpos: ".$r[$i]." ==> T: $t<br>";
    }
    
    $r = $t; // salvo il binarion ribaltato
    
    //DEBUG: echo "R.length: ".strlen($r)."<br>";
    
    // Aggiungo gli zeri in cima nel caso il binario non sia lungo 8 (ottetto IP)
    if(strlen($r)<8) {

        $diff = 8-strlen($r);
        for($i=0; $i<$diff; $i++) {
            $r = "0".$r;
        }
    }
    //DEBUG: echo "<br>(After)R.length: ".strlen($r)."<br>";

    return $r;
    
}

// Stabilisce se l'indirizzo è classful o classless
function whichClass($sn,$ip) {

    $add = ""; // Aggiunge la lettera della classe
    switch($sn) {
        case 8:
            if($ip[0]=="0") {
                $add="A";
            }
            return "$add ClassFull";
        break;
        case 16:
            if($ip[0]=="1" && $ip[1] == "0") {
                $add="B";
            }
            return "$add ClassFull";
        break;
        case 24:
            if($ip[0]=="1" && $ip[1] == "1" && $ip[2] == "0") {
                $add="C";
            }
            return "$add ClassFull";
        break;
        default:
            return "Classless";
        break;
    }
}

// Trova l'indirizzo IP della rete
function findNet($ip,$sm) {

    $res = "";

    // divido gli ottetti
    $exip = explode('.',$ip);
    $exsm = explode('.',$sm);

    for($i=0; $i<4; $i++) {

        $subip=$exip[$i]; //  ottetto del IP
        $subsm=$exsm[$i]; //  ottetto della SM

        for($k=0; $k<8; $k++) {
            
            if($subip[$k] & $subsm[$k]) {
                $res.="1";
            }
            else {
                $res.="0";
            }
        }

        // rimetto i punti (tranne alla fine)
        if($i<3) {
            $res.=".";
        }
        
    }

    return $res;
}

// converte un IP di rete da BIN a DEC
function ipToDec($ipbin) {

    $expl = explode('.',$ipbin);

    $n = "";

    for($i=0; $i<4; $i++) {

        $n.= (string) bindec($expl[$i]);

        // rimetto i punti (tranne alla fine)
        if($i<3) {
            $n.=".";
        }
        
    }

    return $n;

}


// Calcola l'indirizzo di Broadcast in Binario
function findBroad($sn,$net) {

    $ob = "";
    $ip = "";

    $oct = explode('.',$net);

    foreach($oct as $o) {
        
        for($i=0; $i < strlen($o); $i++) {

            if($sn > 0) {
                $ob[$i]= $o[$i]; // ottetto broadcast in copia
                $sn--;
            }
            else {
                $ob[$i]= "1";
            }
        }
        $ip.= $ob.".";
    }

    $ip = rtrim($ip, ".");

    return $ip;

}

// Restituisce la lista dei possibili indirizzi dall'indirizzo di rete (NET) e quello di Broadcast
function listNetworkIp($net,$broadcast,$sn) {

    $lista=[]; // lista da ritornare 

    $no = explode('.', $net); // array con gli ottetti dell'indirizzo NET
    $bo = explode('.', $broadcast); // array con gli ottetti dell'indirizzo NET

    $nbit = 32-$sn; // numero di bit assegnati agli host
     
    $nhost = pow(2,$nbit); // numero totale degli host

    $start = (int) $no[3]+1;
    $end = (int) $bo[3]-1;
    $roots = $no[0].".".$no[1].".".$no[2].".";
    $roote = $bo[0].".".$bo[1].".".$bo[2].".";
    $primo = $roots.$start;
    $ultimo = $roote.$end;
    $hosts = [];

    //echo $start." - ".$end."<br>";

    // verifica che gli IP disponibili non superino le 5000 unità -> stila la lista
    if($nhost > 35000) {
        $lista[0] = $primo;
        $lista[1] = $ultimo;
    }
    else {
        for($i=ip2long($primo); $i<=ip2long($ultimo); $i++) {

            $ip2conv = $i;
            $ip2write = long2ip($ip2conv);
            $lista[] = $ip2write;
            
            $host=gethostbyaddr($ip2write); // prelevo il nome del host (se disponibile)
            //echo $host." | ";

            // se il nome host NON è uguale all'IP -> salvo il nome Host in una lista
            if(!filter_var($host, FILTER_VALIDATE_IP)) {
                $hosts[] = "IP: $ip2write ==> Nome Host:<b> $host </b><br>";
            }

        }
    }

    $ret['hostnames'] = $hosts;
    $ret['primo'] = $primo;
    $ret['ultimo'] = $ultimo;
    $ret['lista'] = $lista;
    $ret['nhost'] = $nhost;  

    return $ret;
}

/* ==================== Number Subnet Mask To Binary IP ========================
 * Questa funzione riceve un numero intero e restittuisce la 
 * Subnet Mask in formato binario (come stringa)
 */
function nSmToBinIp($sn) {

    $snb = "";

    for($i=1;$i<=32;$i++) {

        if($i<=$sn) {
            $snb.="1";
        }
        else {
            $snb.="0";
        }

        if($i%8==0 && $i<32){
            $snb.=".";
        }
    }

    return $snb;
}

/* ==================== Number Subnet Mask From IP ========================
 * Questa funzione riceve la Subnet Mask come numero binario e restituisce
 * il corrispondente numero di subnetting
 */
function nSmFromIp($sm) {

    $count = 0;

    for($i=0;$i<32;$i++) {

        if($sm[$i]=="1") {
            $count++;
        }
    }
    
    return $count;
}


/* ==================== Calculate Subnets ========================
 * Questa funzione calcola le altre subnet adiacenti 
 * (se si tratta di indirizzo classless)
 * Utilizza il metodo 2^n (dove n è l'ultimo bit di rete)
 */

 function calcSubnets($net,$smbin) {

    $posiz = [1,2,4,8,16,32,64,128];
    $oct = 0; // ottetto da modificare

    $octects = explode('.',$smbin); // divido gli ottetti in un array
    $netoct = explode('.', $net); // ottetti della rete

    //var_dump($octects); echo "<hr>";
    //var_dump($netoct); echo "<hr>";

    $countzero = 0; // conto il numero di zeri (per determinare la posizione dell'ultimo bit di rete)
    $octnum = 0; // numero dell'ottetto (per successivi calcoli)

    for($i=0; $i<4; $i++) {
        // DEBUG:echo "Esamino ottetto: ".$octects[$i]."<br>";
        for($j=0; $j<8; $j++) {
            // DEBUG: echo $octects[$i][$j]." , ";
            if($octects[$i][$j] == "0") {                
                $countzero++;
            }
        }
        // se ho trovato degli zeri esco (il prossimo ottetto saranno tutti zeri)
        if($countzero>0) {
            $octnum=$i;
            break; // esco (ho trovato l'ottetto con l'inizio della porzione host)
        }
    }

    // DEBUG:
    /*
    echo "<br>Conto zeri: ".$countzero."<br>";
    echo "Numero ottetto: ".$octnum."<br>";
    echo "POSIZ:".$posiz[$countzero]."<br>";
    */

    // modifico l'ottetto (countzero è proprio della posizione giusta -> array offset)
    for($i=0; $i<255; $i+=$posiz[$countzero]) {
        // in base all'ottetto creo le reti
        switch($octnum) {
            case 0:
                $subnets[] = "$i.".$netoct[1].".".$netoct[2].".".$netoct[3];
            break;

            case 1:
                $subnets[] = $netoct[0].".$i.".$netoct[2].".".$netoct[3];
            break;

            case 2:
                $subnets[] = $netoct[0].".".$netoct[1].".$i.".$netoct[3];
            break;

            case 3:
                $subnets[] = $netoct[0].".".$netoct[1].".".$netoct[2].".$i";
            break;
        }
    }

    return $subnets;

 }