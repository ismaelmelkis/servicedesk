<?php

    include "config.php";
    include "valida_user.inc";
	date_default_timezone_set('America/Sao_Paulo');
	
	$connect_new = mysqli_connect($Host, $Usuario, $Senha, $Base);

	if ($_SESSION["nivel_usuario"]!= "tecnico"){
		$cQuery = mysqli_query($connect_new,"SELECT *
                FROM  chamados
                WHERE setor_id = " . $_SESSION["setor_id"] . " AND status = 'Aguardando Finalizacao'");  
		
		$num_registros = mysqli_num_rows($cQuery);
		
		if($num_registros >= 1){
	?>
                <script language="JavaScript">
                <!--
                alert("VOCE TEM <?php echo $num_registros ?> CHAMADO QUE DEVE SER FINALIZADO \n Favor Finalizar este, \n para que possa abrir  um novo chamado!");
                window.location ='chamado_lista.php';
                //-->
                </script>
    <?php
		}else{
	?>
                <script language="JavaScript">
                <!--
                window.location ='chamado_novo.php';
                //-->
                </script>
    <?php	
		}
	
	}
?>


