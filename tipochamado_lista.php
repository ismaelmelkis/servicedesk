
<html>

<?php
	include "header.php";
	include "config.php";
	include "include/change_color.php";  // o script do lado é responsável pela troca das cores na tabela de listagem.
	date_default_timezone_set('America/Sao_Paulo');
	
	$connect_new = mysqli_connect($Host, $Usuario, $Senha, $Base);
	
	if($_SESSION["nivel_usuario"] == "tecnico"){
		$tcQuery = "SELECT *
                FROM   tipos_chamados
                ORDER BY tipo_titulo ASC"; 	
		$tcUsers = mysqli_query($connect_new, $tcQuery);
		$num_registros = mysqli_num_rows($tcUsers);
	}else{
		?>
                <script language="JavaScript">
                <!--
                alert("VOCE NAO TEM PERMISSAO PARA ACESSAR ESTA PAGINA \n Favor entrar em contato com o administrador do Sistema!");
                window.location = 'tipochamado_lista.php';
                //-->
                </script>
        <?php
	}
?>
<script language="JavaScript">
	function visualizar(id){
        window.location = 'tipochamado_novo.php?id='+id;
    }
</script>
     
		<div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Tipos de Chamados Cadastrados</h3>
            </div>
            <div class="panel-body">
              <table class="table">
            <thead>
              <tr style="font-size: 14;">
				<th>ID</th>
                <th>T&iacute;tulo</th>
                <th>Descri&ccedil;&atilde;o do Tipo</th>
              </tr>
            </thead>
            <tbody>
             
    <?php
       while ($tcRow = mysqli_fetch_object($tcUsers)) {
	?>
              <tr ONMOUSEOVER="move_i(this)" ONMOUSEOUT="move_o(this)" onClick="visualizar('<?php echo $tcRow->tipo_id ?>')" style="font-size: 14; color: #0A60D4">
				<th><?php echo $tcRow->tipo_id ?></th>
                <th><?php echo $tcRow->tipo_titulo ?></th>
                <th><?php echo $tcRow->tipo_descricao ?></th>
			  </tr>
	<?php
		}
    ?>            
            </tbody>
          </table>
            </div>
        </div>
	





<?php
	include "footer.php";
?>

</html>