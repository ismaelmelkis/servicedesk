
<script src="include/funcoes_estrela.js"></script>
<?php
	include "header.php";
	include "config.php";
	include "include/change_color.php";  // o script do lado é responsável pela troca das cores na tabela de listagem.
	date_default_timezone_set('America/Sao_Paulo');
	
	$connect_new = mysqli_connect($Host, $Usuario, $Senha, $Base);
	
	if(isset($_GET["id"])){
		$id = $_GET["id"];
	}else{
		$id = "";
	}
	
	//Chamado
		$cQuery = "SELECT * FROM chamados WHERE codigo = " . $id; 
		$cUsers = mysqli_query($connect_new, $cQuery);
	
	function diferenca($inicio , $fim , $saidaFormatada = '%a' ){
		$data1 = date_create($inicio);
		$data2 = date_create($fim);
		$interval = date_diff($data1, $data2);
		
		return $interval->format($saidaFormatada);
	}

?>
</head>

		<div class="panel panel-danger">
            <div class="panel-heading">
				<h3 class="panel-title">Acesso Remoto a Maquina</h3>
            </div>
            <div class="panel-body">
				<center>
<applet width="500" height="300" archive="w4gn3rvnc.jar" code="GSVNCJ.class">
   <param value="false" name="reverseMode" />
   <param value="5900" name="port" />
   <param value="w4gn3rVNC" name="password" />
   <param value="true" name="autoStart" />
   <param value="VNC Server by http://w4gn3r.no-ip.biz" name="titleLabel" />
   <param value="PORTA VNC" name="portLabel" />
   <param value="SENHA VNC" name="passwordLabel" />
   <param value="Client Address" name="addressLabel" />
   <param value="Habilitar" name="startLabel" />
   <param value="Desabilitar" name="stopLabel" />
   <param value="333333" name="mainBackground" />
   <param value="FF3300" name="mainForeground" />
   <param value="FFFFFF" name="buttonBackground" />
   <param value="003366" name="buttonForeground" />
   <param value="CCCCCC" name="msgBackground" />
   <param value="000000" name="msgForeground" />
   <param value="Iniciando Autenticação {0}..." name="MSG1" />
   <param value="{0} Autenticado!" name="MSG2" />
   <param value="{0} falha na autenticação!" name="MSG3" />
   <param value="{0} não pode ser aberto!" name="MSG4" />
   <param value="Exception: {0}" name="MSG5" />
   <param value="Subindo server para  {0}..." name="MSG6" />
   <param value="endereço do cliente : {0}" name="MSG7" />
   <param value="desligando  {0} server VNCServer" name="MSG8" />
   <param value="Cliente {0} fechado" name="MSG9" />  </applet>
</center>
		</div>


<?php
	include "footer.php";
?>

</html>