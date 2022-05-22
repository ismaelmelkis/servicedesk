
<html>

<?php
	include "header.php";
	include "config.php";
	//include "include/change_color.php";  // o script do lado é responsável pela troca das cores na tabela de listagem.
	date_default_timezone_set('America/Sao_Paulo');
	
	$connect_new = mysqli_connect($Host, $Usuario, $Senha, $Base);
	
	if(isset($_GET["id"]))
		$id = $_GET["id"];
	else
		$id = "";
	
	if($id != ""){
		$tcQuery = mysqli_query($connect_new, "SELECT *
                FROM  tipos_chamados
				WHERE tipo_id = " . $id);
	
		$tcRow = mysqli_fetch_object($tcQuery);
		
		$tipo_titulo = $tcRow->tipo_titulo;
		$tipo_descricao = $tcRow->tipo_descricao;
		
		$op = "alterar";
	}else{
		$op = "salvar";
		
		$tipo_titulo = "";
		$tipo_descricao = "";
	}
	
?>
<script language="JavaScript">

	function excluir(id){
        window.location = 'tipochamado_save.php?op=excluir&id='+id;
    }

</script>
   
   <div id="panel" class="panel panel-default no-padding" >
			<div class="panel-body no-padding">
				<form role="form" class="form-horizontal" action="<?php echo 'tipochamado_save.php?op=' . $op . '&id=' . $id ?>" method="post">
					<div class="row-fluid">
						<div id="colImg" class="col-md-2 col-sm-2 col-xs-12 col-img">
							<!-- Background image -->
						</div>
						<div class="col-md-5 col-sm-5 col-xs-12  padding border-right">
						<?php if ($id!= "") { ?>
							<p class="lead"><b>Aletar Tipo de Chamado ID: <?php echo $id; ?></b></p>
						<?php }else{ ?>
							<p class="lead"><b>Cadastrar Novo Tipo de Chamado</b></p>
						<?php } ?>
						<label label-default="" for="">Tipo</label>
							<div class="row">
								<div class="col-md-12">
									<input type="text" name="tipo_titulo" value="<?php echo $tipo_titulo ?>" placeholder="* Nome do Tipo de Chamado" class="form-control" required>
								</div>
							</div>
							<br/>
						<label label-default="" for="">Descri&ccedil;&atilde;o</label>
							<div class="row">
								<div class="col-md-12">
									<input type="text" name="tipo_descricao" value="<?php echo $tipo_descricao ?>" placeholder="* Descri&ccedil;&atilde;o do Tipo de Chamado" class="form-control" required>
								</div>
							</div>
							<br/>
						<?php if ($id!= "") { ?>
							<div class="row-fluid">
								<!--input type="submit" value="Salvar Alteracoes" class="btn btn-primary alterar" >
								<input type="button" value="Excluir" class="btn btn-danger excluir" onclick="excluir('<?php echo $id ?>')"-->	
								<a href="JavaScript: window.history.back();" class="btn btn-default limpar">Voltar</a>
							</div>
						<?php }else{ ?>
							<div class="row-fluid">
								<!-- input type="submit" value="Incluir" class="btn btn-primary alterar" -->
								<a href="JavaScript: window.history.back();" class="btn btn-default limpar">Voltar</a>
							</div>
						<?php } ?>
						</div>
					</div>
				</form>
			</div>
		</div>




<?php
	include "footer.php";
?>

</html>