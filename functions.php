<?php

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
//function findBroad($ip,$sm) {
function findBroad($sn,$net) {

    $fbit = 0; // conta i bit liberi della subnet (free bit)
    $ob = "";

    $oct = explode('.',$net);

    foreach($oct as $o) {
        
        for($i=0; $i < strlen($o); $i++) {

            if($sn > 0) {
                $ob[$i] = $o[$i]; // ottetto broadcast in copia
                $sn--;
            }
            else {
                $ob[$i] = "1";
            }
        }
        $ip.=$ob.".";
    }

    $ip = rtrim($ip, ".");

    /*
    for($i=(strlen($sm)-1); $i>0;$i--) {
        //echo $sm[$i];
        if($sm[$i]=="0") {
            $zbit++;
        }
    }

    // converto IP di rete in broadcast (tutti 1 nel dominio)
    for($i=(strlen($ip)-1); $i>0;$i--) {
        //echo "Prima:".$ip[$i]."<br>Dopo:";
        if($zbit>0) {
            $ip[$i] = "1";
            $zbit--;
        }
        //echo $ip[$i]."<br>";
    }

    //echo "<br>NewIP: $ip<br>";
    */
    return $ip;

}

// Restituisce la lista dei possibili indirizzi dall'indirizzo di rete (NET) e quello di Broadcast
function listNetworkIp($net,$broadcast) {

    $lista4=[]; // lista da ritornare (4 ottetto)

    $no = explode('.', $net); // array con gli ottetti dell'indirizzo NET
    $bo = explode('.', $broadcast); // array con gli ottetti dell'indirizzo NET

    //var_dump($bo);

    // Verifico che i primi ottetti non siano diversi (reti classe A e B) => n_host incalcolabile
    if((levenshtein($no[0],$bo[0]) == 0) && (levenshtein($no[1],$bo[1]) == 0) ) {

        // Calcolo non ancora abilitato
        if(levenshtein($no[2],$bo[2]) > 0) {
            //$diff3 = (int) $bo[2] - (int) $no[2]; // differenza (3 ottetto) per controllo
            $start3 = (int) $no[2]+1;
            $end3 = (int) $bo[2];
            $root3 = $no[0].".".$no[1].".";

            for($i=$start3; $i<$end3; $i++) {

                $lista3[] = $root3.$i;
            
            }
        }

        if(levenshtein($no[3],$bo[3]) > 0) {
            //$diff4 = (int) $bo[3] - (int) $no[3]; // differenza (4 ottetto) per controllo
            $start4 = (int) $no[3]+1;
            $end4 = (int) $bo[3];
            $root4 = $no[0].".".$no[1].".".$no[2].".";

            for($i=$start4; $i<$end4; $i++) {

                $lista4[] = $root4.$i;
            
            }
        }       

    }    

    return $lista4;
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