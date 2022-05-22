<?php

    include "config.php";
    include "valida_user.inc";
    //include "layout.php";
	//$periodopg = $_POST["datai"] . " - " . $_POST["dataf"];
	date_default_timezone_set('America/Sao_Paulo');
	 
    $connect_new = mysqli_connect($Host, $Usuario, $Senha, $Base);

	$opcao = $_GET["op"];
	
	//CADASTRO DO Chamado :.
	
if ($opcao == "salvar") {
	
		if( (!$_POST["titulo"]) || (!$_POST["nome"]) ){
			?>
                <script language="JavaScript">
                <!--
                alert("FAVOR PREENCHER TODOS OS CAMPOS \n ANTES DE GRAVAR O CHAMADO");
                window.history.back();
                //-->
                </script>
			<?php
		}else{
		
		$datatime = date('Y-m-d H:i');
		$status = "Aberto";
		$IP=$_SERVER["REMOTE_ADDR"];
		$lido = 2;
		
		$file_patch = "include/notificacao/notificacao.txt";
		$n=1;
		
		//Fazendo Inclusao no Arquivo para Notificação
		if(file_exists($file_patch)){
			$nQuery = mysqli_query($connect_new, "SHOW TABLE STATUS LIKE 'chamados'");
			$nRow = mysqli_fetch_object($nQuery); 
			$nId = $nRow->Auto_increment;
			$linha[0] = $nId . "\r\n";
			
			$arquivo = fopen($file_patch, 'r+');
				while(!feof($arquivo)){
					$linha[$n] = fgets($arquivo, 1024);
					$n++;
				}
				ftruncate($arquivo, 0); 
				fclose($arquivo);
				
				// insere todos os elementos do vetor com o id
				$arquivo = fopen($file_patch, 'r+');
				foreach ($linha as $conteudo){
					fwrite($arquivo, $conteudo);
				}
				fclose($arquivo);
				
			}else{
?>
                <script language="JavaScript">
                <!--
                alert("A Notificacao da Abertura deste Chamado nao foi enviada \n Para o Usuario Administrador \n Falha no Arquivo include/notificao.txt");
                //-->
                </script>
<?php
			}
		
		//Chamados
		$cQuery = "INSERT INTO chamados (data_abertura, setor_id, titulo, descricao, tipo, nome, tecnico, chamado_ip, chamado_lido, status)
                 VALUES ('" . $datatime . "',
						 '" . $_SESSION["setor_id"] . "',
						 '" . $_POST["titulo"] . "',
						 '" . $_POST["descricao"] . "',
						 '" . $_POST["tipo"] . "',
						 '" . $_POST["nome"] . "',
						 '" . $_POST["tecnico"] . "',
						 '" . $IP . "',
						 '" . $lido . "',
                         '" . $status . "')";

		if ( mysqli_query($connect_new, $cQuery) or die(mysqli_error($connect_new)) ) {		
		 
	 //Se existir o Arquivos para Upload
		if(isset($_FILES['arquivo']['name']) && $_FILES["arquivo"]["error"] == 0){
			
			$ext = strtolower(substr($_FILES['arquivo']['name'],-4)); //Pegando extensão do arquivo
			$dir = 'uploads/'; //Diretório para uploads
			$c2Query = mysqli_query($connect_new, "SELECT * FROM chamados ORDER BY codigo DESC LIMIT 1") or die (mysqli_error($connect_new));
			$c2Row = mysqli_fetch_object($c2Query);
			
			$new_name = $c2Row->codigo . "_" . date("dmY-His") . $ext; 
			move_uploaded_file($_FILES['arquivo']['tmp_name'], $dir.$new_name); 
		
			$cQuery = mysqli_query($connect_new, "UPDATE chamados SET				
				 patch_upload = '" . $new_name . "'
                   WHERE codigo = " . $c2Row->codigo) or die (mysqli_error($connect_new));
		}
		
		?>
                <script language="JavaScript">
                <!--
                alert("Chamado Aberto com Sucesso!");
                window.location ='chamado_lista.php';
                //-->
                </script>
     <?php
	 
	} else { 
      ?>
                <script language="JavaScript">
                <!--
                alert("*** PROBLEMAS NO CADASTRO - GRAVAR NO BANCO DE DADOS ! *** ");
                window.location = 'chamado_lista.php';
                //-->
                </script>
     <?php
	}
   }
		
}
	if ($opcao == "alterar") {
		
		if((!$_POST["titulo"])){
			?>
                <script language="JavaScript">
                <!--
                alert("O ARQUIVO ANEXADO NAO PODE SER MAIOR QUE 30MB, \n Favor inserir um arquivo Menor!");
                window.history.back();
                //-->
                </script>
			<?php
		}
		
		//ALTERACAO DE CADASTRO :.
		$datatime = date('Y-m-d H:i');
		
		if($_POST["status"] == ""){
			$status = "Aguardando Finalizacao";
		}else{
			$status = $_POST["status"];
		}
		
		if($_POST["status"] == "Aguardando Finalizacao"){	
			$cQuery = "UPDATE chamados SET
				titulo = '" . $_POST["titulo"] . "', 
				descricao = '" . $_POST["descricao"] . "',
				tipo = '" . $_POST["tipo"] . "', 
				nome = '" . $_POST["nome"] . "',
				status = '" . $status . "',
				tecnico = '" . $_POST["tecnico"] . "',
				data_fecha = '" . $datatime . "',				
				solucao = '" . $_POST["solucao"] . "',
				chamado_producao = '" . $_POST["producao"] . "',
				chamado_remoto = '" . $_POST["remoto"] . "'
                   WHERE codigo = " . $_GET["id"];
			
		}else{		
			$cQuery = "UPDATE chamados SET
				titulo = '" . $_POST["titulo"] . "', 
				descricao = '" . $_POST["descricao"] . "',
				tipo = '" . $_POST["tipo"] . "', 
				nome = '" . $_POST["nome"] . "',
				status = '" . $status . "',
				tecnico = '" . $_POST["tecnico"] . "',
				solucao = '" . $_POST["solucao"] . "',
				chamado_producao = '" . $_POST["producao"] . "',
				chamado_remoto = '" . $_POST["remoto"] . "'
                   WHERE codigo = " . $_GET["id"];
		}
		
		if ( mysqli_query($connect_new, $cQuery) ) {
			
			//Fazendo Upload do Arquivo Enviado 
			if(isset($_FILES['arquivo']['name']) && $_FILES["arquivo"]["error"] == 0){
		
				$ext = strtolower(substr($_FILES['arquivo']['name'],-4)); //Pegando extensão do arquivo
				$dir = 'uploads/'; //Diretório para uploads
			
				$new_name = $_GET["id"] . "_" . date("dmY-His") . $ext; //Definindo um novo nome para o arquivo
				move_uploaded_file($_FILES['arquivo']['tmp_name'], $dir.$new_name); //Fazer upload do arquivo
				
				$cQuery = mysqli_query($connect_new, "UPDATE chamados SET				
				 patch_upload = '" . $new_name . "'
                   WHERE codigo = " . $_GET["id"]) or die (mysqli_error($connect_new));
			}
		?>
                <script language="JavaScript">
                <!--
                alert("Alteracao do Cadastro no CHAMADO Efetuada com Sucesso!");
                window.location = 'chamado_lista.php';
                //-->
                </script>
         <?php
		} else {
       	 ?>
                <script language="JavaScript">
                <!--
                alert("*** PROBLEMAS A ALTERAR CADASTRO NO BANCO DE DADOS ! *** ENTRAR EM CONTATO COM O ADMINISTRADOR DO SISTEMA!");
                window.location = 'chamado_lista.php';
                //-->
                </script>
   <?php
		}
	}
	
		if ($opcao == "excluir") {
			$file_patch = "include/notificacao/notificacao.txt";
			$n=0;
			$id = $_GET["id"];
			$id_integer = (int) $id;
			
		if(file_exists($file_patch)){
			$arquivo = fopen($file_patch, 'r+');
				while(!feof($arquivo)){
					$linha[$n] = fgets($arquivo, 1024);
					if($linha[$n] == $id_integer){
						$nz = $n;
					}
					$n++;
				}
			
			if(isset($nz)){
				unset($linha[$nz]);
				ftruncate($arquivo, 0); 
				fclose($arquivo);
				
				// insere todos os elementos do vetor sem o excluido
				$arquivo = fopen($file_patch, 'r+');
				foreach ($linha as $conteudo){
					fwrite($arquivo, $conteudo);
				}
			}
			fclose($arquivo);
		}else{
?>
                <script language="JavaScript">
                <!--
                alert("A Notificacao da Abertura deste Chamado nao foi enviada \n Para o Usuario Administrador \n Falha no Arquivo include/notificao.txt");
                //-->
                </script>
<?php
		}
	
		$iQuery = "DELETE FROM chamados
                WHERE codigo = " . $_GET["id"];				
   
			if (mysqli_query($connect_new, $iQuery) ) {
			?>
                <script language="JavaScript">
                <!--
                alert("Chamado Excluido com Sucesso!");
                window.location = 'chamado_lista.php';
                //-->
                </script>
         	<?php
			mysqli_close($connect_new);
			} else {
       	 	?>
                <script language="JavaScript">
                <!--
                alert("*** PROBLEMAS AO EXCLUIR O CADASTRO NO BANCO DE DADOS ! *** ENTRAR EM CONTATO COM O ADMINISTRADOR DO SISTEMA!");
                window.location = 'chamado_lista.php';
                //-->
                </script>
   			<?php
			}
		
		}

?>


