
<html>

<?php
	include "header.php";
	include "config.php";
	include "include/change_color.php";  // o script do lado é responsável pela troca das cores na tabela de listagem.
	date_default_timezone_set('America/Sao_Paulo');
	
	$connect_new = mysqli_connect($Host, $Usuario, $Senha, $Base);
	
	if($_SESSION["nivel_usuario"] == "tecnico"){
		$sQuery = "SELECT *
                FROM   setor
                ORDER BY setor_desc ASC"; 	
		$oUsers = mysqli_query($connect_new, $sQuery);
		$num_registros = mysqli_num_rows($oUsers);
	}else{
		$usQuery = mysqli_query($connect_new, "SELECT *
                FROM   usuarios
                WHERE cod_usuario = " . $_SESSION["cod_usuario"]); //where status like '$filtro_inicial%'
		$usRow = mysqli_fetch_object($usQuery);
		
		$sQuery = "SELECT *
                FROM   setor
                WHERE setor_id = " . $usRow->user_setor_id; 	
		$oUsers = mysqli_query($connect_new, $sQuery);
		$num_registros = mysqli_num_rows($oUsers);
	}
?>
<script language="JavaScript">
	function visualizar(id){
        window.location = 'setor_novo.php?id='+id;
    }
</script>
     
		<div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Setores Cadastrados</h3>
            </div>
            <div class="panel-body">
              <table class="table">
            <thead>
              <tr style="font-size: 14;">
				<th>ID</th>
                <th>Nome do Setor</th>
                <th>Respos&aacute;vel</th>
				<th>Login</th>
				<th>N&iacute;vel</th>
              </tr>
            </thead>
            <tbody>
             
    <?php
       while ($oRow = mysqli_fetch_object($oUsers)) {
			
			$uQuery =  mysqli_query($connect_new, "SELECT user_setor_id , nivel, login
                FROM   usuarios
				WHERE user_setor_id = " . $oRow->setor_id);
				$uRow = mysqli_fetch_object($uQuery);
				
			if($uRow->nivel == "tecnico"){ 
				$cor_chamado = "#00BB27"; 
			}
			if($uRow->nivel == "usuario"){  
				$cor_chamado = "#0A60D4"; 
			}
			if($uRow->nivel == "BLOQUEADO"){  
				$cor_chamado = "red"; 
			}
	?>
              <tr ONMOUSEOVER="move_i(this)" ONMOUSEOUT="move_o(this)" onClick="visualizar('<?php echo $oRow->setor_id ?>')" style="font-size: 12; color: <?php echo $cor_chamado ?>;">
				<th><?php echo $oRow->setor_id ?></th>
                <th><?php echo $oRow->setor_desc ?></th>
                <th><?php echo $oRow->setor_responsav ?></th>
				<th><?php echo $uRow->login ?></th>
				<th><?php echo $uRow->nivel ?></th>
			  </tr>
	<?php
		}
    ?>            
            </tbody>
          </table>
            </div>
			<h6 align="right"><span class="label label-primary">Usuario Padrao</span>
			<span class="label label-success">Tecnico Administrador</span>
			<span class="label label-danger">Usuario Bloqueado</span></h6>
        </div>
	





<?php
	include "footer.php";
?>

</html>