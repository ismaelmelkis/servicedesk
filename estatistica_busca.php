
<html>
<?php
	include "header.php";
	include "config.php";
	include "valida_user.inc";
	include "include/change_color.php";  // o script do lado é responsável pela troca das cores na tabela de listagem.
	date_default_timezone_set('America/Sao_Paulo');
	//$datatime = date('Y-m-d H:i');
	//$data = date('d/m/Y');
?>   
		<div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Estat&iacute;stica de Dados por Per&iacute;odo</h3>
            </div>
            <div class="panel-body">
		<div class="row-fluid">
			<form method="post" action="estatistica_grafico.php" >
				<div class="col-md-2">
					<label label-default="" for="">Data Inicial</label>
					<input type="date" name="data1" value="<?php if (isset($_POST["data1"])) echo $_POST["data1"] ?>" class="form-control">
									
				</div>
				<div class="col-md-2">
					<label label-default="" for="">Data Final</label>
					<input type="date" name="data2" value="<?php if (isset($_POST["data2"])) echo $_POST["data2"] ?>" class="form-control">
				</div>
				<div class="col-md-2">
					<label label-default="" for="">Tipo de Pesquisa: </label>
						<select name="filtro" class="form-control" >
							<option value="avaliacao" class="form-control" >Avalia&ccedil;&atilde;o</option>
							<!-- option value="setor" class="form-control" >Setor</option -->
							<option value="status" class="form-control" >Status</option>
						</select>
				</div>
				<div class="col-md-2">
				<br/>
					<b><input type="submit" class="btn btn-info" value="Buscar Dados">
				</div>
			</form>
		</div>
            </div>
        </div>

<?php
	include "footer.php";
?>

</html>