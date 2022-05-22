
<html>

<?php
	include "header.php";
	include "config.php";
	//include "include/change_color.php";  // o script do lado é responsável pela troca das cores na tabela de listagem.
	date_default_timezone_set('America/Sao_Paulo');
	
	$connect_new = mysqli_connect($Host, $Usuario, $Senha, $Base);
	
	
	if($_GET["id"] != ""){
		$userQuery = mysqli_query($connect_new, "SELECT *
                FROM   usuarios
				WHERE user_setor_id = " . $_GET["id"]);
		$userRow = mysqli_fetch_object($userQuery);
		$op = "alterar_senha";
	}
	
?>
<script language="JavaScript">
	
	//Validação de Senhas . . .
	function validarSenha(formExemplo){
    	senha =  document.getElementById('password').value;
    	senhaRepetida = document.getElementById('confirm_password').value;
		if (senha != senhaRepetida){
        	alert("Repita a senha corretamente");
        	document.formExemplo.confirm_password.focus();  
    	}
		if (senha.length < 6){
        	alert("A Senha deve Conter no Minimo 6 Caracteres");
        	document.formExemplo.confirm_password.focus();  
    	}
	}

</script>
   
   <div id="panel" class="panel panel-default no-padding" >
			<div class="panel-body no-padding">
				<form role="form" class="form-horizontal" action="<?php echo 'setor_save.php?op=' . $op . '&id=' . $_GET["id"] ?>" method="post">
					<div class="row-fluid">
						<div id="colImg" class="col-md-2 col-sm-2 col-xs-12 col-img">
							<!-- Background image -->
						</div>
						<div class="col-md-5 col-sm-5 col-xs-12  padding border-right">
						<?php if ($_GET["id"]!= "") { ?><label label-default="" for="">Login</label><?php } ?>
							<div class="row">
								<div class="col-md-12">
									<input type="text" name="login" readonly value="<?php echo $userRow->login ?>" placeholder="* Login" class="form-control" required>
								</div>
							</div>
							<br/>
						<?php if ($_GET["id"]!= "") { ?><label label-default="" for="">Senha Antiga:</label><?php } ?>
							<div class="row">
								<div class="col-md-12">
									<input type="password" name="senha_antiga" id="senha_antiga" placeholder="* Senha Antiga" class="form-control" data-minlength="6">
								</div>
							</div>
							<br/>
						<?php if ($_GET["id"]!= "") { ?><label label-default="" for="">Nova Senha:</label><?php } ?>
							<div class="row">
								<div class="col-md-12">
									<input type="password" name="senha" id="password" placeholder="* Senha, no m&iacute;nimo 6 d&iacute;gitos" class="form-control" data-minlength="6" required>
								</div>
							</div>
							<br/>
						<?php if ($_GET["id"]!= "") { ?><label label-default="" for="">Repetir Nova Senha:</label><?php } ?>
							<div class="row">
								<div class="col-md-12">
									<input type="password" name="confirm_password" id="confirm_password" placeholder="* Confirme sua Senha" class="form-control" onMouseOut="validarSenha(this)" required>
								</div>
							</div>
							<br/>
							<div class="row-fluid">
								<input type="submit" value="Salvar" class="btn btn-success salvar">
								<button class="btn btn-default limpar">Limpar</button>
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