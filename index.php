<?php

/* Questa applicazione analizza un indirizzo IP e restituisce le informazioni su di esso
 * come ad esempio il tipo di rete, se è un ip di host, broadcast o di rete ...
 *
 * 
 * App by Fuzz
 */

 // Variabili importanti
$pagename = "Home"; // Nome della pagina (richiesto nel template)

// Requirements
require_once 'template.php';

?>
<div class="container-fluid">

<div class="row">
    <div class="col-2"></div>

    <div class="col-8">

        <h1 class="titolo">Pagina Principale dell'App <?php echo NOME_APP?></h1>
    
    </div>

    <div class="col-2"></div>
</div> <!-- fine row1 -->

<div class="row">
    <div class="col-3"></div>

    <div class="col-6">

    <br><br>
        <div class="txtcenter">
            <span class="txtbolder txtcenter">Inserisci qui IP e Subnet Mask che vuoi analizzare</span>
        </div>
        <br>
        <div class="form-group">
            <form action="result.php" method="POST">

                (*) IP:
                <input class="form-control" type="text" name="ip" placeholder="Inserisci IP Address QUI" required><br>

                (*) SM:
                <input class="form-control" type="text" name="sm" placeholder="Inserisci Subnet Mask QUI" required><br>                
                <br> 
                (*) Campi obbligatori
                <br>
                <input type="submit" name="anal" value="Analizza" class="pulsante">

            </form>
        </div>
        <br>
        </div>
       
        <div class="col-3"></div>
    </div> <!-- fine row2 -->

    <div class="row">
        <div class="col-2">
            <br>
            <br>
            <br>
            <a href="../index.php">TORNA INDIETRO</a>
        </div>
        <div class="col-8"></div>
        <div class="col-2"></div>
    </div>
  
</div> <!-- fine container -->
