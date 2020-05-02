<?php
session_start();

define('NOME_APP','IP-Anlyzr');

error_reporting(1); // per debug
ini_set('display_errors', 2); // per il debug (potrebbe essere deprecata in php7)

?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="description" content="ip,address,analize,statistics,ipcalc,calculate,
    calculon,calculator,binary,decimal,dot,network,networking,nat,broadcast,tcp,tcpip,osi,iso,
    stack,full,classless,classful,natting,dhcp,addressing">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-type" content="text/html">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="icon" type="image/ico" href="../favicon.ico">

    <title><?php echo $pagename.' - '.NOME_APP ?></title>

    <script
        src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous">    
    </script>

    <script 
        src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" 
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" 
        crossorigin="anonymous">
    </script>


    <!-- BOOTSTRAP STYLE -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- CUSTOM STYLE -->
	<link rel="stylesheet" href="style.css?v=1.1">
	
	<style>
        
	</style>
    
</head>
