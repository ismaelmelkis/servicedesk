<?php

    include "config.php";
    include "valida_user.inc";
	date_default_timezone_set('America/Sao_Paulo');
	
	$connect_new = mysqli_connect($Host, $Usuario, $Senha, $Base);

	$opcao = $_GET["op"];
	
	//CADASTRO DO ALUNO :.
	
	if ($opcao == "salvar") {
		
		$upd2Query = mysqli_query($connect_new, "SELECT * FROM tipos_chamados WHERE tipo_titulo = '" . $_POST["tipo_titulo"] . "'");
			$numero_linhas = mysqli_num_rows($upd2Query);
			
		if ($numero_linhas != 1){
			
			$tcQuery = "INSERT INTO tipos_chamados (tipo_titulo, tipo_descricao)
                 VALUES ('" . $_POST["tipo_titulo"] . "',
                         '" . $_POST["tipo_descricao"] . "')";
		
		} else {
		?>
                <script language="JavaScript">
                <!--
                alert("Tipo de Chamado ja Cadastrado, Favor utilizar outro Tipo!");
                window.history.back();
                //-->
                </script>
     <?php	
		}
		if ( mysqli_query($connect_new, $tcQuery) ) {
	  ?>
                <script language="JavaScript">
                <!--
                alert("Tipo de Chamado Cadastrado com Sucesso!");
                window.location ='tipochamado_lista.php';
                //-->
                </script>
     <?php
	} else {
      ?>
                <script language="JavaScript">
                <!--
                alert("*** PROBLEMAS A GRAVAR NO BANCO DE DADOS ! ***");
                window.location = 'tipochamado_lista.php';
                //-->
                </script>
     <?php
	}
	
}
	if ($opcao == "alterar") {
		
		//ALTERACAO DE CADASTRO :.	
			
			$tcQuery = "update tipos_chamados set
                tipo_titulo = '" . $_POST["tipo_titulo"] . "',
                tipo_descricao = '" . $_POST["tipo_descricao"] . "'
                   WHERE tipo_id = " . $_GET["id"];

		if ( mysqli_query($connect_new, $tcQuery) ) {
		?>
                <script language="JavaScript">
                <!--
                alert("Alteracao do Tipo de Chamado Efetuada com Sucesso!");
                window.location = 'tipochamado_lista.php';
                //-->
                </script>
         <?php
		} else {
       	 ?>
                <script language="JavaScript">
                <!--
                alert("*** PROBLEMAS A ALTERAR CADASTRO NO BANCO DE DADOS ! *** ENTRAR EM CONTATO COM O ADMINISTRADOR DO SISTEMA!");
                window.location = 'tipochamado_lista.php';
                //-->
                </script>
   <?php
		}
	}
	
		if ($opcao == "excluir") {
		
		$tcQuery = "DELETE FROM tipos_chamados
                WHERE tipo_id = " . $_GET["id"];
   
   			if ( mysqli_query($connect_new, $tcQuery) ) {
			?>
                <script language="JavaScript">
                <!--
                alert("Tipo de Chamado Excluido com Sucesso!");
                window.location = 'tipochamado_lista.php';
                //-->
                </script>
         	<?php
			} else {
       	 	?>
                <script language="JavaScript">
                <!--
                alert("*** PROBLEMAS AO EXCLUIR O CADASTRO NO BANCO DE DADOS ! *** ENTRAR EM CONTATO COM O ADMINISTRADOR DO SISTEMA!");
                window.location = 'tipochamado_lista.php';
                //-->
                </script>
   			<?php
			}
		}

?>


