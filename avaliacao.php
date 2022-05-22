
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
?>
</head>

		<div class="panel panel-danger">
            <div class="panel-heading">
				<h3 class="panel-title">Favor Avaliar o Atendimento para Concluir o Chamado!</h3>
            </div>
            <div class="panel-body">
				
              <table class="table">
            <thead>
              <tr style="font-size: 14; color: #0A60D4">
				<th>ID</th>
                <th>Aberto em </th>
                <th>Setor</th>
				<th>Titulo</th>
              </tr>
            </thead>
            <tbody>
             
    <?php 
       while ($cRow = mysqli_fetch_object($cUsers)) {
		
		//Calculando Dias
			$dt_fecha = date('Y-m-d', strtotime($cRow->data_fecha));;
			$dt_abri = date('Y-m-d', strtotime($cRow->data_abertura));; 
			$dif = strtotime($dt_fecha) - strtotime($dt_abri);
			$dias = ($dif / 86400);
			$dias = round($dias, 0);//Arredondando
			//echo $dt_abri;
				
			//Calculando Minutos
			$hs_fecha = date('H:i:s', strtotime($cRow->data_fecha));;
			$hs_abri = date('H:i:s', strtotime($cRow->data_abertura));;
			$dif = strtotime($hs_fecha) - strtotime($hs_abri);
			$min = ($dif / 60);
			$hs = ($dif / 3600);
			$minutos = round($min, 0); //Arredondando
			$horas = round($hs, 0); 		

		if ($dias>0){
			$tempo = $dias . " dias e " . $horas . "hs";
		}
		if (($dias<=0) && ($horas>0)){
			$tempo = $horas . "hs e " . $minutos . " min";
		}
		if (($horas<=0) && ($dias<=0)){
			$tempo = $minutos . " min";
		}
		
		//data e hora formatada
		$datahora_abriu = date('d/m/Y - H:i:s', strtotime($cRow->data_abertura));;
		$datahora_fechou = date('d/m/Y - H:i', strtotime($cRow->data_fecha));;
		  
		//Setor
		$sQuery = mysqli_query($connect_new, "SELECT *
            FROM setor
            WHERE setor_id = ". $cRow->setor_id);
		$sRow = mysqli_fetch_object($sQuery);		
	?>
             <tr style="font-size: 14;">
				<th><?php echo $cRow->codigo ?></th>
                <th><?php echo $datahora_abriu ?></th>
                <th><?php echo $sRow->setor_desc ?></th>
				<th><?php echo $cRow->titulo ?></th>
			</tr>
			<tr style="font-size: 14; color: #0A60D4">
				<th>Descricao</th>
				<th>Finalizado em</th>
				<th>Solucao</th>
				<th>Tempo Decorrido</th>
              </tr>
			<tr style="font-size: 14;">
				<th><?php echo $cRow->descricao ?></th>
				<th><?php echo $datahora_fechou ?></th>
				<th><?php echo $cRow->solucao ?></th>
				<th><?php echo $tempo ?></th>				
			 </tr>
	<?php
			$obs = $cRow->obs;
		}
    ?>            
            </tbody>
          </table>
        </div>
      </div>
	<form role="form" class="form-horizontal" action="<?php echo 'avaliacao_save.php?&id=' . $id ?>" method="post">
	 <label label-default="" for="" style="font-size: 14; color: #0A60D4">Observa&ccedil;&atilde;o Sobre o Chamado:</label>
		<div class="row">
			<div class="col-md-12">
				<textarea class="form-control" name="obs" id="exampleFormControlTextarea1" placeholder=" Deixe Aqui sua Observa&ccedil;&atilde;o, Elogio ou Critica sobre o atendimento" rows="2">
					<?php echo $obs ?>
				</textarea>
			</div>
		</div><br/>
	<h6 align="center"><b><i>
		Avalie o Atendimento escolhendo de 1 a 5 Estrelas, onde 1 = Atendimento Pessimo e 5 = Atendimento Otimo.
	</b></i></h6>
	<br/>
	<p align="center">	
		<a href="javascript:void(0)" onclick="Avaliar(1)">
		<img src="img/star0.png" id="s1"></a>

		<a href="javascript:void(0)" onclick="Avaliar(2)">
		<img src="img/star0.png" id="s2"></a>

		<a href="javascript:void(0)" onclick="Avaliar(3)">
		<img src="img/star0.png" id="s3"></a>

		<a href="javascript:void(0)" onclick="Avaliar(4)">
		<img src="img/star0.png" id="s4"></a>

		<a href="javascript:void(0)" onclick="Avaliar(5)">
		<img src="img/star0.png" id="s5"></a>
	
		<br/><br/>
		<div id="rating">
		</div>
		<div align="center">
		<h4><input type="submit" align="center" value="Fechar o Chamado" class="btn btn-warning"></h4>
		</div>
	</p>
	<br/>


<?php
	include "footer.php";
?>

</html>