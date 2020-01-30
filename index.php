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
            <span class="txtbolder txtcenter">Inserisci indirizzo IP e Subnet Mask che vuoi analizzare</span>
        </div>
        <br>
        <div class="txtcenter">
            <span class="txtbolder err_mess">
                <?php 

                if(isset($_SESSION['err_no_subnet'])) {
                    echo $_SESSION['err_no_subnet']; echo "<br>"; 
                }
                if(isset($_SESSION['err_invalid_ip'])) {
                    echo $_SESSION['err_invalid_ip']; echo "<br>"; 
                }
                if(isset($_SESSION['err_invalid_sm'])) {
                    echo $_SESSION['err_invalid_sm']; echo "<br>"; 
                }
                
                if(isset($_SESSION['ip'])) {
                    $ip = $_SESSION['ip'];
                }

                if(isset($_SESSION['sm'])) {
                    $sm = $_SESSION['sm'];
                }
                
                
                ?>
            </span>
        </div>

        <div class="form-group">
            <form action="result.php" method="POST">

                (*) IP Address:
                <input class="form-control" type="text" name="ip" placeholder="Inserisci IP Address QUI" value="<?php if(isset($ip)){echo $ip;} ?>" required><br>

                (**) Subnet Mask:
                <input class="form-control" type="text" name="sm" placeholder="Inserisci Subnet Mask QUI" value="<?php if(isset($sm)){echo $sm;} ?>" ><br>                
                
                (**) Subnet Number (1-30):
                <input class="form-control" type="number" name="sn" min="1" max="30" style="width: 100px;"><br>                
                <br> 
                (*) Campi obbligatori <br>
                (**) Obbligatorio uno dei due <br>

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
            <a href="../reset.php">TORNA INDIETRO</a>
        </div>
        <div class="col-8"></div>
        <div class="col-2"></div>
    </div>
  
</div> <!-- fine container -->
