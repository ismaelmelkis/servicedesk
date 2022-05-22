<?php

    include "config.php";
    include "valida_user.inc";
	date_default_timezone_set('America/Sao_Paulo');
	
	$connect_new = mysqli_connect($Host, $Usuario, $Senha, $Base);

	$opcao = $_GET["op"];
	
	//CADASTRO DO ALUNO :.
	
	if ($opcao == "salvar") {
		
		$upd2Query = mysqli_query($connect_new, "SELECT * FROM usuarios WHERE login = '" . $_POST["login"] . "'");
			$numero_linhas = mysqli_num_rows($upd2Query);
			
		if ($numero_linhas != 1){
			
			$seQuery = mysqli_query($connect_new, "INSERT INTO setor (setor_desc, setor_email, setor_responsav)
                 VALUES ('" . $_POST["nome"] . "',
			 			 '" . $_POST["email"]  . "',
                         '" . $_POST["responsavel"] . "')");
		
			$updQuery = mysqli_query($connect_new, "SELECT * FROM setor ORDER BY setor_id DESC");
				$setorRow = mysqli_fetch_object($updQuery);
					$id_setor = $setorRow->setor_id;
		
			$usQuery = "INSERT INTO usuarios (nom_usuario, login, pwd_usuario, user_setor_id, nivel)
                 VALUES ('" . $_POST["nome"] . "',
			 			 '" . $_POST["login"]  . "',
						 '" . md5(md5($_POST["senha"])) . "',
						 '" . $id_setor  . "',
                         '" . $_POST["tipo_user"] . "')";
		} else {
		?>
                <script language="JavaScript">
                <!--
                alert("Login já Cadastrado, Favor utilizar outro Login!");
                window.history.back();
                //-->
                </script>
     <?php	
		}
		if ( mysqli_query($connect_new, $usQuery) ) {
	  ?>
                <script language="JavaScript">
                <!--
                alert("Setor Cadastrado com Sucesso!");
                window.location ='setor_lista.php';
                //-->
                </script>
     <?php
	} else {
      ?>
                <script language="JavaScript">
                <!--
                alert("*** PROBLEMAS A GRAVAR NO BANCO DE DADOS ! ***");
                window.location = 'setor_lista.php';
                //-->
                </script>
     <?php
	}
	
}
	if ($opcao == "alterar") {
		
		//ALTERACAO DE CADASTRO :.	
			
			$userQuery = "update usuarios set
                nom_usuario = '" . $_POST["nome"] . "',
                login = '" . $_POST["login"] . "',
                nivel = '" . $_POST["tipo_user"] . "'
                   WHERE user_setor_id = " . $_GET["id"];
		
			$setorQuery = "update setor set
                setor_desc = '" . $_POST["nome"] . "',
			 	setor_responsav = '" . $_POST["responsavel"]  . "',
                setor_email = '" . $_POST["email"] . "'
                   WHERE setor_id = " . $_GET["id"];

		if ( (mysqli_query($connect_new, $setorQuery)) && (mysqli_query($connect_new, $userQuery)) ) {
		?>
                <script language="JavaScript">
                <!--
                alert("Alteracao do Cadastro do Usuario Efetuada com Sucesso!");
                window.location = 'setor_lista.php';
                //-->
                </script>
         <?php
		} else {
       	 ?>
                <script language="JavaScript">
                <!--
                alert("*** PROBLEMAS A ALTERAR CADASTRO NO BANCO DE DADOS ! *** ENTRAR EM CONTATO COM O ADMINISTRADOR DO SISTEMA!");
                window.location = 'setor_lista.php';
                //-->
                </script>
   <?php
		}
	}

	if ($opcao == "alterar_senha") {
		
		//ALTERACAO DE SENHA :.	

	/*			
		$uQuery =  mysqli_query($connect_new, "SELECT *
                FROM   usuarios
				WHERE user_setor_id = " . $_GET["id"]);
				$uRow = mysqli_fetch_object($uQuery);
		
		if ( ($uRow->pwd_usuario) != $_POST["senha_antiga"] ) {
		?>
                <script language="JavaScript">
                <!--
                alert("A Senha antiga nao esta correta!");
                window.location = 'setor_muda_senha.php';
                //-->
                </script>
         <?php
		} else {
	*/	
		$userQuery = "update usuarios set
                pwd_usuario = '" . md5(md5($_POST["senha"])) . "'
                   WHERE user_setor_id = " . $_GET["id"];
	
		if (mysqli_query($connect_new, $userQuery) ) {
		?>
                <script language="JavaScript">
                <!--
                alert("Alteracao do Senha do Usuario Efetuada com Sucesso!");
                window.location = 'setor_lista.php';
                //-->
                </script>
         <?php
		} else {
       	 ?>
                <script language="JavaScript">
                <!--
                alert("*** PROBLEMAS A ALTERAR CADASTRO NO BANCO DE DADOS ! *** ENTRAR EM CONTATO COM O ADMINISTRADOR DO SISTEMA!");
                window.location = 'setor_lista.php';
                //-->
                </script>
   <?php
		}
	}
	
		if ($opcao == "excluir") {
		
		$uQuery = "update usuarios set
				login = '',
				pwd_usuario = '',
				nivel = 'BLOQUEADO'
                   WHERE user_setor_id = " . $_GET["id"];
				
				//Altera??es de Bloqueio Solicitadas Pelo Cliente Comprador do Sistema
				//"DELETE FROM usuarios WHERE user_setor_id = " . $_GET["id"];
		
		$sQuery = "update setor set
			 	setor_responsav = 'Setor BLOQUEADO',
                setor_email = 'Setor BLOQUEADO'
                   WHERE setor_id = " . $_GET["id"];
				
				//"DELETE FROM setor WHERE setor_id = " . $_GET["id"];
   
   			if ( (mysqli_query($connect_new, $uQuery)) && (mysqli_query($connect_new, $sQuery)) ) {
			?>
                <script language="JavaScript">
                <!--
                alert("Usuario BLOQUEADO com Sucesso!");
                window.location = 'setor_lista.php';
                //-->
                </script>
         	<?php
			} else {
       	 	?>
                <script language="JavaScript">
                <!--
                alert("*** PROBLEMAS AO BLOQUEAR O CADASTRO NO BANCO DE DADOS ! *** ENTRAR EM CONTATO COM O ADMINISTRADOR DO SISTEMA!");
                window.location = 'setor_lista.php';
                //-->
                </script>
   			<?php
			}
		}

?>


