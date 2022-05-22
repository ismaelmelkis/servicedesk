
<html>
<?php
	include "header.php";
	include "config.php";
	include "valida_user.inc";
	include "include/change_color.php";  // o script do lado é responsável pela troca das cores na tabela de listagem.
	include "include/mobile.php"; 
	date_default_timezone_set('America/Sao_Paulo');
	//$datatime = date('Y-m-d H:i');
	$data = date('d/m/Y');
	
	if(isset($_POST["data1"])){
		if(($_POST["data1"] == "") && ($_POST["data2"] == "")){
			?>
                <script language="JavaScript">
                <!--
                alert("ATENCAO! \n Necessario Preencher o Periodo de Datas Incial e Final");
                //-->
                </script>
			<?php 
		}
	}
	
	$connect_new = mysqli_connect($Host, $Usuario, $Senha, $Base);
	//colocar ou se o post valor for igual a "";
	if ( (isset($_POST["valor"])) && ($_POST["valor"] != "") ){ 
		$datai = date('Y-m-d', strtotime($_POST["data1"]));
		$dataf = date('Y-m-d', strtotime($_POST["data2"]));
		
		if($_POST["filtro"]!= "id"){
			$cQuery = "SELECT *
					FROM chamados
					WHERE " . $_POST["filtro"] . " like '" . $_POST["valor"] . "' AND (CAST(data_abertura AS DATE) BETWEEN '" . $datai . "' AND '" . $dataf . "')";
		}else{
			$cQuery = "SELECT *
					FROM chamados
					WHERE codigo = " . $_POST["valor"];
		}
		if($_POST["filtro"]== "setor"){
			$sQuery =  mysqli_query($connect_new,"SELECT * FROM setor WHERE setor_desc like '" . $_POST["valor"] . "%' ORDER BY setor_desc DESC");
				if($sRow = mysqli_fetch_object($sQuery)){
					$setor_desc = $sRow->setor_desc;
					$cQuery = "SELECT * FROM  chamados WHERE setor_id = " . $sRow->setor_id . " AND (CAST(data_abertura AS DATE) BETWEEN '" . $datai . "' AND '" . $dataf . "')"; 
				}else{
					$_POST["valor"] = "";
				}
		}
		
		$cUsers = mysqli_query($connect_new, $cQuery);
		//$num_registros = mysqli_num_rows($cUsers);
		
	}else{
		?>
                <script language="JavaScript">
                <!--
                alert("ATENCAO! \n Necessario Preencher o Campo Valor da Busca");
                //-->
                </script>
		<?php 
	}
	

?>
<script language="JavaScript">
	function print_um(id){
        window.open('chamado_rel_pdf.php?id='+id,'_blank');
    }
	function print_todos(sql){
		alert("ATENCAO! \n Voce esta Tentando Imprimir Varios Chamados \n Isso pode demorar alguns Minutos \n DESEJA CONTINUAR ?");
        window.open('chamado_rel_pdf.php?sql='+sql,'_blank');
    }
	
</script>
     
		<div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Relatório de Chamados por Data de Abertura</h3>
            </div>
            <div class="panel-body">
		<div class="row-fluid">
			<form method="post" action="#" >
				<div class="col-md-2">
					<label label-default="" for="">Data Inicial</label>
					<input type="date" name="data1" value="<?php if (isset($_POST["data1"])) echo $_POST["data1"] ?>" class="form-control">
									
				</div>
				<div class="col-md-2">
					<label label-default="" for="">Data Final</label>
					<input type="date" name="data2" value="<?php if (isset($_POST["data2"])) echo $_POST["data2"] ?>" class="form-control">
				</div>
				<div class="col-md-2">
					<label label-default="" for="">Filtrar por: </label>
						<select name="filtro" class="form-control" >
							<option value="id" <?php if ((isset($_POST["filtro"])) && ($_POST["filtro"] == "id")) echo "selected" ?>  class="form-control" >Numero do Chamado</option>
							<option value="nome" <?php if ((isset($_POST["filtro"])) && ($_POST["filtro"] == "nome")) echo "selected" ?> class="form-control" >Nome</option>
							<option value="setor" <?php if ((isset($_POST["filtro"])) && ($_POST["filtro"] == "setor")) echo "selected" ?> class="form-control" >Setor</option>
							<option value="titulo" <?php if ((isset($_POST["filtro"])) && ($_POST["filtro"] == "titulo")) echo "selected" ?> class="form-control" >Assunto</option>
							<option value="tipo" <?php if ((isset($_POST["filtro"])) && ($_POST["filtro"] == "tipo")) echo "selected" ?> class="form-control" >Tipo de Chamado</option>
							<option value="status" <?php if ((isset($_POST["filtro"])) && ($_POST["filtro"] == "status")) echo "selected" ?> class="form-control" >Status</option>
							<option value="avaliacao" <?php if ((isset($_POST["filtro"])) && ($_POST["filtro"] == "avaliacao")) echo "selected" ?> class="form-control" >Nota de Avaliacao</option>
							<option value="tecnico" <?php if ((isset($_POST["filtro"])) && ($_POST["filtro"] == "tecnico")) echo "selected" ?> class="form-control" >Finalizado por</option>
						</select>
				</div>
				<div class="col-md-4">
					<label label-default="" for="">Valor da Busca: </label>
					<input type="text" name="valor" value="<?php if (isset($_POST["valor"])) echo $_POST["valor"] ?>" placeholder="* Entre com o Valor de Acordo com o Filtro Selecionado" class="form-control">
				</div>
				<div class="col-md-2">
				<br/>
					<b><input type="submit" class="btn btn-info" value="Buscar">
			<?php if ( (isset($_POST["valor"])) && ($_POST["valor"] != "") ){ 
						$valor = $_POST["valor"];
						$_SESSION["print_todos"] = $cQuery;
			?>
					<input type="button" class="btn btn-default" onClick="print_todos('<?php echo $valor ?>')" value="Imprimir Todos"></b>
			<?php } ?>
				</div>
			</form>
		</div>
	<?php if ( (isset($_POST["valor"])) && ($_POST["valor"] != "") ){ ?>
		<br/><br/><br/><br/>
		<div class="row-fluid">
			<table class="table">
				<thead>
					<tr style="font-size: 14;">
	<?php if ($mobile == 0){?>
					<th>ID</th>
					<th>Nome</th>
	<?php } ?> 
					<th>Setor</th>
					<th>Assunto</th>
	<?php if ($mobile == 0){?>
					<th>Tipo de Chamado</th>
	<?php } ?>
					<th>Status</th>
					</tr>
				</thead>
				<tbody>
             
    <?php
       while ($cRow = mysqli_fetch_object($cUsers)) {
		 $sQuery =  mysqli_query($connect_new,"SELECT *
			FROM setor
			WHERE setor_id = " . $cRow->setor_id);
			if($sRow = mysqli_fetch_object($sQuery)){
				$setor_desc = $sRow->setor_desc;
			}else{
				$setor_desc = "Setor Excluido";
			}
	?>
	              <tr ONMOUSEOVER="move_i(this)" ONMOUSEOUT="move_o(this)" style="font-size: 12; color: #0A60D4;">
	<?php if ($mobile == 0){?>
				<th><?php echo $cRow->codigo ?></th>
				<th><?php echo $cRow->nome ?></th>
	<?php } ?>
				<th><?php echo $setor_desc ?></th>
				<th><?php echo $cRow->titulo ?></th>
	<?php if ($mobile == 0){?>
				<th><?php echo $cRow->tipo ?></th>
	<?php } ?>
				<th><?php echo $cRow->status ?></th>
				<th><input type="button" class="btn btn-xs btn-primary" value="Imprimir" onClick="print_um('<?php echo $cRow->codigo ?>')" ></b></th>
			  </tr>
	<?php
		}
	}else{
		?>
                <script language="JavaScript">
                <!--
                alert("ATENCAO! \n Valor da Busca nao Encontrado! \n FAVOR ENTRE COM UM OUTRO VALOR");
                //-->
                </script>
		<?php 
	}
    ?>            
				</tbody>
			</table>
		</div>
            </div>
        </div>

<?php
	include "footer.php";
?>

</html>