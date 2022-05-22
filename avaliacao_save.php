<?php

    include "config.php";
    include "valida_user.inc";
	date_default_timezone_set('America/Sao_Paulo');
	
	$connect_new = mysqli_connect($Host, $Usuario, $Senha, $Base);

if(isset($_POST["estrela"])){
	$avaliacao = $_POST["estrela"];
	
	if($avaliacao ==1){
		$msg = "Voce Avaliou o Atendimento com a Nota 1 = Pessimo";
	}
	if($avaliacao ==2){
		$msg = "Voce Avaliou o Atendimento com a Nota 2 = Ruim";
	}
	if($avaliacao ==3){
		$msg = "Voce Avaliou o Atendimento com a Nota 3 = Regular";
	}
	if($avaliacao ==4){
		$msg = "Voce Avaliou o Atendimento com a Nota 4 = Bom";
	}
	if($avaliacao ==5){
		$msg = "Voce Avaliou o Atendimento com a Nota 5 = Otimo";
	}
	
	$id = $_GET["id"];
		
			$updQuery = "UPDATE chamados SET
				obs = '" . $_POST["obs"] . "',
				avaliacao = '" . $avaliacao . "',
				status = 'Finalizado'
                   WHERE codigo = " . $id;

	if ( mysqli_query($connect_new, $updQuery) ) {
	  ?>
                <script language="JavaScript">
                <!--
                alert("<?php echo $msg ?> \n Obrigado pela sua Participacao \n CHAMADO FECHADO COM SUCESSO");
                window.location ='chamado_lista.php';
                //-->
                </script>
     <?php
	} else {
      ?>
                <script language="JavaScript">
                <!--
                alert("*** PROBLEMAS A GRAVAR NO BANCO DE DADOS ! ***<?php echo $avaliacao ?>");
                window.location = 'chamado_lista.php';
                //-->
                </script>
     <?php
	}

} else {

      ?>
                <script language="JavaScript">
                <!--
                alert("*** Voce nao avaliou o chamado, \n precisamos da sua ajuda para melhorar o atendimento \n Favor participar da avaliacao e tentar novamente \n Obrigado!");
                window.location = 'chamado_lista.php';
                //-->
                </script>
     <?php
}
?>


