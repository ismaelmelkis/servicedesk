
<html>

<?php
	ini_set('default_charset', 'UTF-8');
	include "valida_user.inc";
	include "header.php";
	include "config.php";
	include "include/change_color.php";  // o script do lado é responsável pela troca das cores na tabela de listagem.
	date_default_timezone_set('America/Sao_Paulo');
	
	$connect_new = mysqli_connect($Host, $Usuario, $Senha, $Base);
	
	if(isset($_GET["id"])){
		$id = $_GET["id"];
		
		//Chamados
		$cQuery = mysqli_query($connect_new, "SELECT * FROM   chamados WHERE codigo = " . $id);
		$cRow = mysqli_fetch_object($cQuery);
		
		//Setor
		$sQuery = mysqli_query($connect_new, "SELECT *
            FROM setor
            WHERE setor_id = ". $cRow->setor_id);
		$sRow = mysqli_fetch_object($sQuery);
		
			if(isset($sRow->setor_desc)){
				$setor = $sRow->setor_desc;
			}else{
				$setor = "Setor Excluido";
			}

		$nome = $cRow->nome;
		$titulo = $cRow->titulo;
		$descricao = $cRow->descricao;
		$status = $cRow->status;
		$obs = $cRow->obs;
		$solucao = $cRow->solucao;
		$arquivo = $cRow->patch_upload;
		$tecnico = $cRow->tecnico;
		$chamado_remoto = $cRow->chamado_remoto;
		$chamado_producao = $cRow->chamado_producao;
		$chamado_ip = $cRow->chamado_ip;
		$chamado_lido = $cRow->chamado_lido;
		
		$op = "alterar";
	}else{
		$op = "salvar";
		$setor = $nome_usuario;
		$id = "";
		$nome = "";
		$titulo = "";
		$descricao = "";
		$status = "";
		$obs = "";
		$tecnico = "";
		$solucao = "";
		$chamado_producao = "";
		$chamado_remoto = "";
		$chamado_ip = "";
		$chamado_lido = "";
	}
	
	//Alterando a Opção Lido e a Notificação . . .
	if ($chamado_lido == 2){
		if ($_SESSION["nivel_usuario"]== "tecnico"){
		
			$file_patch = "include/notificacao/notificacao.txt";
			$n=0;
			$id_file = $id . " ";
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
			}else{
				echo "<button type=\"button\" class=\"btn btn-lg btn-default\">Este Chamado não consta nas notificações !</button>";
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
		//Alterando o Status Lido
			$lidoQuery = mysqli_query($connect_new, "UPDATE chamados SET
				chamado_lido = 1
                   WHERE codigo = " . $_GET["id"]);
		}
	}
	
	
	//Selecionando os Tipos de Chamado . . .
		$tQuery = mysqli_query($connect_new, "SELECT *
                FROM tipos_chamados
				ORDER BY tipo_titulo ASC");



?>
<script language="JavaScript">
	function excluir(id){
        window.location = 'chamado_save.php?op=excluir&id='+id;
    }
	
	function voltar(id){
        window.location = 'window.history.back()';
    }
	

		function Mudarestado(el) {
			
			var display = document.getElementById(el).style.display;
			if(display == "none")
            document.getElementById(el).style.display = 'block';
			else
            document.getElementById(el).style.display = 'none';
		}
</script>	
<?php
			   if(isset($_FILES['arquivo'])){
	   $ext = strtolower(substr($_FILES['arquivo']['name'],-4)); //Pegando extensão do arquivo
	   $dir = 'uploads/'; //Diretório para uploads
		
		if(isset($_GET["id"])){
			$new_name = $_GET["id"] . "_" . date("dmY-His") . $ext;  //Definindo um novo nome para o arquivo
			move_uploaded_file($_FILES['fileUpload']['tmp_name'], $dir.$new_name); //Fazer upload do arquivo
		}else{
			//Buscando o ID do Chamados
			$c2Query = mysqli_query($connect_new, "SELECT codigo FROM chamados ORDER BY codigo DESC");
			$c2Row = mysqli_fetch_object($c2Query);
			
			$new_name = $c2Row->codigo . "_" . date("dmY-His") . $ext; 
			move_uploaded_file($_FILES['arquivo']['tmp_name'], $dir.$new_name); 
		}
		
		$_SESSION["arquivo"] = $new_name;
   }
?>

</script>
   
   <div id="panel" class="panel panel-default no-padding" >
			<div class="panel-body no-padding">
				<form role="form" class="form-horizontal" action="<?php echo 'chamado_save.php?op=' . $op . '&id=' . $id ?>" method="post" enctype="multipart/form-data">
					<div class="row-fluid">
						<div id="colImg" class="col-md-2 col-sm-2 col-xs-12 col-img">
							<!-- Background image -->
						</div>
						<div class="col-md-5 col-sm-5 col-xs-12  padding border-right">
						<?php if (isset($_GET["id"])) { ?>
							<p class="lead"><b><?php echo "N&uacute;mero do Chamado: " . $id; ?> </b></p>
						<?php }else{  ?>
							<p class="lead"><b>Abrir Chamado</b></p>
						<?php } ?>	
							<label label-default="" for="">Nome</label>
							<div class="row">
								<div class="col-md-12">
									<input type="text" maxlength="50" name="nome" value="<?php echo $nome; ?>" placeholder="* Nome de quem est&aacute; abrindo o chamado" class="form-control">
								</div>
							</div>
							<br/>
							<label label-default="" for="">Setor</label>
							<div class="row">
								<div class="col-md-12">
									<input type="text" name="setor" readonly value="<?php echo $setor ?>" class="form-control">
								</div>
							</div>
							<br/>
					
							<label label-default="" for="">Tipo de Chamado</label>
							<div class="row">
								<div class="col-md-12">
									<select id="" name="tipo" class="form-control">
									
									<?php while($row_t = mysqli_fetch_object($tQuery)) { 
											if ($row_t->tipo_titulo == $cRow->tipo){ ?>
												<option value="<?php echo $row_t->tipo_titulo ?>" selected> <?php echo $row_t->tipo_titulo; ?> </option>
									<?php	}else{  ?>
												<option value="<?php echo $row_t->tipo_titulo ?>"> <?php echo $row_t->tipo_titulo; ?> </option>
									<?php 	}  
										} 
									?>
									
									</select>
								</div>
							</div>
							<br/>
							
							<label label-default="" for="">Assunto</label>
							<div class="row">
								<div class="col-md-12">
									<input type="text" maxlength="43" name="titulo" value="<?php echo $titulo; ?>" placeholder="* Assunto do Chamado" class="form-control">
								</div>
							</div>
							<br/>
							
							<label label-default="" for="">Descri&ccedil;&atilde;o</label>
							<div class="row">
								<div class="col-md-12">
									<textarea class="form-control" name="descricao" id="exampleFormControlTextarea1" placeholder="* Digite aqui mais detalhes sobre o problema encontrado" rows="2"><?php echo $descricao; ?></textarea>
								</div>
							</div>
							<br/>
					<?php if (isset($arquivo)){ 
							$msganexar = "Anexar outro Arquivo";			?>
							<div class="row">
								<div class="col-md-12" align="height">
									<input class="form-control" type="file" name="arquivo" id="file" accept="image/*;capture=camera" onchange="document.getElementById('file-falso').value = this.value;">
									<input class="form-control" type="text" maxlength="15" size="3" placeholder="Permitido Anexar apenas um Arquivo para cada Chamado, Maximo:30MB" id="file-falso" readonly onclick="document.getElementById('file').click();">
									<b><input type="button" class="btn btn-info" value="<?php echo $msganexar ?>" onclick="document.getElementById('file').click();"></b>
									<h4>Arquivo em Anexo: <a target="_blank" href="uploads\<?php echo $arquivo ?>">Acessar Arquivo</a> </h4>
								</div>
							</div>
					<?php }else { 
							$msganexar = "Anexar Arquivo";			?>
							<div class="row">
								<div class="col-md-12" align="height">
									<b><input class="form-control" type="file" name="arquivo" id="file" accept="image/*;capture=camera" onchange="document.getElementById('file-falso').value = this.value;">
									<input class="form-control" type="text" maxlength="15" size="3" id="file-falso" readonly onclick="document.getElementById('file').click();">
									<input type="button" class="btn btn-info" value="<?php echo $msganexar ?>" onclick="document.getElementById('file').click();"></b>
								</div>
							</div>
					<?php } 
						if ($_SESSION["nivel_usuario"]!= "tecnico"){ ?>
						<div class="row-fluid">
						<br/>
						<?php if (isset($_GET["id"])) { ?>
							<?php if ($status != "Aberto"){ ?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<!--input type="submit" value="Alterar Chamado Aberto" class="btn btn-primary enviar" onclick="Mudarestado('loading')" -->
								<a href="JavaScript: window.history.back();" class="btn btn-default limpar">Voltar</a>
							<?php }else{ ?>
							
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="submit" value="Alterar" class="btn btn-primary enviar" onclick="Mudarestado('loading')">
								<input type="button" value="Excluir" class="btn btn-danger excluir" onclick="excluir('<?php echo $id ?>')">
								<a href="JavaScript: window.history.back();" class="btn btn-default limpar">Voltar</a>
							
						<?php  }
						}else{ ?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="submit" value="Abrir Chamado" class="btn btn-primary enviar" onclick="Mudarestado('loading')">	
								<a href="JavaScript: window.history.back();" class="btn btn-default limpar">Voltar</a><br/><br/>
						<?php } ?>
								
						<?php } ?>
							
						</div>
						
					</div>
						<?php 
							if ($status == "Finalizado"){
								$bloqueia_select = "background: #eee; pointer-events: none; touch-action: none;"; 
								$selected00="selected";
							}
							if ($status == "Aberto") $selected1="selected"; 
							if ($status == "Em Andamento") $selected2="selected";
							if ($status == "Aguardando Finalizacao") $selected3="selected"; $disabled="disabled";
							
							if ($_SESSION["nivel_usuario"]!= "tecnico"){
								$bloqueia_select = "background: #eee; pointer-events: none; touch-action: none;";
							}
						?>
						
						<div class="col-md-5 col-sm-5 col-xs-12  padding">
							<p class="lead">&Aacute;rea T&eacute;cnica </p>
							<label label-default="" for="">Finalizado por:</label>
							<div class="row">
								<div class="col-md-12">
								<select style="<?php if (isset($bloqueia_select)) echo $bloqueia_select; ?>" name="tecnico" id="tecnico" class="form-control" >
									<?php
										$arquivo = fopen ('tecnicos.txt', 'r');
										while(!feof($arquivo)){
											$linha = fgets($arquivo, 1024);
											if($tecnico == $linha){
												echo "<option selected value=\"$linha\">$linha</option>"; 
											}else{
												echo "<option value=\"$linha\">$linha</option>"; 
											}
										}
										fclose($arquivo);
									?>
								</select>
								</div>
							</div>
							<br/>
							
							<label label-default="" for="">Solu&ccedil;&atilde;o</label>
							<div class="row">
								<div class="col-md-12">
					<?php if ($_SESSION["nivel_usuario"]!= "tecnico") { $bloqueio_tecnico = "disabled"; ?>
									<textarea class="form-control" name="solucao" style="background: #eee; pointer-events: none; touch-action: none;" id="exampleFormControlTextarea1" placeholder=" Solu&ccedil;&atilde;o para o Problema" rows="3"><?php if($solucao!=""){ echo $solucao; }?></textarea>
					<?php } else {?>
									<textarea class="form-control" name="solucao"  id="exampleFormControlTextarea1" placeholder=" Solu&ccedil;&atilde;o para o Problema" rows="1"><?php if($solucao!=""){ echo $solucao; }?></textarea>
					<?php } ?>
								</div>
							</div>
							<br/>

							
							<div class="row">
								<div class="col-md-6">
									<label label-default="" for="">Status</label>
									<select style="<?php if (isset($bloqueia_select)) echo $bloqueia_select; ?>" name="status" id="status" class="form-control" >
										<option <?php if (isset($selected1)) echo $selected1 ?> value="Aberto" class="form-control" >Aberto</option>
										<option <?php if (isset($selected2)) echo $selected2 ?> value="Em Andamento" class="form-control" >Em Andamento</option>
							<?php if ($status == "Aguardando Finalizacao"){ ?>
										<option  <?php if (isset($selected3)) echo $selected3 ?> value="" name="Aguardando Finalizacao" >Finaliza&ccedil;&atilde;o T&eacute;cnica</option>
							<?php }else{ ?>
										<option  <?php if (isset($selected3)) echo $selected3 ?> value="Aguardando Finalizacao" name="Aguardando Finalizacao" >Finaliza&ccedil;&atilde;o T&eacute;cnica</option>
							<?php }if ($status == "Finalizado"){ ?>
										<option <?php if (isset($selected00)) echo $selected00 ?> value="Finalizado" >Finalizado</option>
							<?php } ?>
									</select>
								</div>
							<?php if ($_SESSION["nivel_usuario"]== "tecnico") { ?>
								<div class="col-md-6">
								<label label-default="" for="">Ip Usuario:</label>
								<input type="text" readonly  value="<?php echo $chamado_ip; ?>" class="form-control">
								</div>
							<?php } ?>
								
							</div>
							<br/>
						<?php if ($_SESSION["nivel_usuario"]== "tecnico") { ?>	
							<div class="row">
								<div class="col-md-6">
								<label label-default="" for="">Defini&ccedil;&atilde;o:</label>
								<select style="<?php if (isset($bloqueia_select)) echo $bloqueia_select; ?>" name="producao" id="producao" class="form-control" >
						<?php 	if ($chamado_producao == 1){		
									echo "<option selected value=\"1\">Improdutivo</option>";
									echo "<option value=\"0\">Produtivo</option>";
								}else{
									echo "<option selected value=\"0\">Produtivo</option>";
									echo "<option value=\"1\">Improdutivo</option>";
								}
						?>	
								</select>
								</div>
								<div class="col-md-6">
								<label label-default="" for="">Tipo de Conex&atilde;o:</label>
								<select style="<?php if (isset($bloqueia_select)) echo $bloqueia_select; ?>" name="remoto" id="remoto" class="form-control" >
						<?php 	if ($chamado_remoto == 1){		
									echo "<option selected value=\"1\">Local</option>";
									echo "<option value=\"0\">Remoto</option>";
								}else{
									echo "<option selected value=\"0\">Remoto</option>";
									echo "<option value=\"1\">Local</option>";
								}
						?>	
								</select>
								</div>
							</div>
						<?php }   ?>
							<br/>
							<label label-default="" for="">Observa&ccedil;&atilde;o</label>
							<div class="row">
								<div class="col-md-12">
									<textarea class="form-control" name="obs" id="exampleFormControlTextarea1" style="background: #eee; pointer-events: none; touch-action: none;" placeholder=" Observa&ccedil;&otilde;es" rows="1"><?php if($obs!=""){ echo $obs; }?></textarea>
								</div>
							</div>
							<br/>
	
						<?php if ($_SESSION["nivel_usuario"]== "tecnico") { ?>						
							<div class="row-fluid">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="submit" value="Gravar" class="btn btn-primary enviar">
								<input type="button" value="Excluir" class="btn btn-danger excluir" onclick="excluir('<?php echo $id ?>')">
								<a href="JavaScript: window.history.back();" class="btn btn-default limpar">Voltar</a>
							</div>
						<?php } ?>
							<div id="loading" style="display: none">
								<h4>
									<img src="img/sistema/load_azul.gif" width="50" height="50">
										Aguarde, Enviando Chamado . . . 
								</h4>
							</div>
						</div>						
					</div>
				</form>
			</div>
		</div>




<?php
	include "footer.php";
?>

</html>