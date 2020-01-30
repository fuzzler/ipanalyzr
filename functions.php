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
function whichClass($sm) {

    $expl = explode('.',$sm);

    //var_dump((int) $expl[3]);

    // Verifico che l'ultimo ottetto sia maggiore di 0 -> classless / classful
    if( (int) $expl[3] > 0) {
        return "ClassLess";
    }
    else {
        return "ClassFull";
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


function net2Dec($nb) {

    $expl = explode('.',$nb);

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