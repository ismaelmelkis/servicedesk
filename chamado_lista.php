
<html>
<?php
	include "header.php";
	include "config.php";
	include "valida_user.inc";
	include "include/change_color.php";  // o script do lado é responsável pela troca das cores na tabela de listagem.
	include "include/mobile.php"; 
	date_default_timezone_set('America/Sao_Paulo');
	$datatime = date('Y-m-d H:i');
	$ncount = 0;
	$sla=0;
	
	$connect_new = mysqli_connect($Host, $Usuario, $Senha, $Base);
	
	if ($_SESSION["nivel_usuario"]!= "tecnico"){
		$cQuery = "SELECT *
                FROM  chamados
                WHERE setor_id = '" . $_SESSION["setor_id"] . "' ORDER BY data_abertura DESC LIMIT 150"; 
	}else{
		$cQuery = "SELECT *
                FROM  chamados
                ORDER BY data_abertura DESC"; 
	}
    $oUsers = mysqli_query($connect_new, $cQuery);
    $num_registros = mysqli_num_rows($oUsers);
	
?>
<script language="JavaScript">
	function visualizar(id){
        window.location = 'chamado_novo.php?id='+id;
    }
	function avaliar(id){
        window.location = 'avaliacao.php?id='+id;
    }
</script>
     
		<div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Dashboard</h3>
            </div>
            <div class="panel-body">
              <table class="table">
            <thead>
              <tr style="font-size: 14;">
		<?php if ($mobile == 0){?>	
				<th>ID</th> 
		<?php } ?>
                <th>Aberto em </th>
                <th>Setor</th>
		<?php if ($mobile == 0){?>
				<th>Titulo</th>
		<?php } ?>
				<th>Status</th>
				<th>Tempo</th>
              </tr>
            </thead>
            <tbody>
             
    <?php
       while ($oRow = mysqli_fetch_object($oUsers)) {
		   
		//Diferença de Tempo
		if (isset($oRow->data_fecha)) {
			$dt_fecha = date('Y-m-d', strtotime($oRow->data_fecha));;
			$hs_fecha = date('H:i:s', strtotime($oRow->data_fecha));;
		}else{
			$dt_fecha = date("Y-m-d");
			$hs_fecha = date("H:i:s");
		}
			//Calculando Dias
			$dt_abri = date('Y-m-d', strtotime($oRow->data_abertura));; 
			$dif1 = strtotime($dt_fecha) - strtotime($dt_abri);
			$dias1 = ($dif1 / 86400);
			$dias = round($dias1, 0);//Arredondando
			//echo $dt_abri;
				
			//Calculando Minutos
			$hs_abri = date('H:i:s', strtotime($oRow->data_abertura));;
			$dif = strtotime($hs_fecha) - strtotime($hs_abri);
			$min = ($dif / 60%60);
			$hs = ($dif / 3600%3600);
			$minutos = round($min, 0); //Arredondando
			$horas = round($hs, 0); 
			$horas = abs($horas); //Passando para Positivo
			$minutos = abs($minutos); //Passando para Positivo
		
		if ($dias>=1){
			if (($min<0) || ($hs<0) ){
				$dias = $dias - 1;
				$horas = 23 - $horas;
				$minutos = 59 - $minutos;
			}
		}
			$horas = abs($horas); //Passando para Positivo
			$minutos = abs($minutos); //Passando para Positivo
		
			//$tempo = printf( '%d:%d', $diferenca/3600, $diferenca/60%60 );
			
			//echo "abertura : " . date('H:i:s:m:d:Y', strtotime($oRow->data_abertura));;		
			//echo "id: " . $oRow->codigo . " - dt_fe:" . $dias1 . " - hs " . $hs . "<br/>";		
			//echo "<br/>";		

		
		
		if ($dias>=1){
			$tempo = $dias . " dias e " . $horas . "hs";
		}
		if ($dias==1){
			$tempo = $dias . " dia e " . $horas . " hs";
		}
		if (($dias<=0) && ($horas>0) ){
			$tempo = "Aprox. " . $horas . "hs";
		}
		if (($dias<=0) && ($horas>0) && ($minutos<=60) ){
			$tempo = $horas . "hs e " . $minutos . " min";
		}
		if (($dias<=0) && ($horas<=0)){
			$tempo = $minutos . " min";
		}
		//$tempo = $dias . " dias e " . $horas . "hs e " . $minutos . " min";
		
		
		$sQuery = mysqli_query($connect_new, "SELECT *
            FROM setor
            WHERE setor_id = ". $oRow->setor_id);
		$sRow = mysqli_fetch_object($sQuery);
			if(isset($sRow->setor_desc)){
				$setor_desc = $sRow->setor_desc;
			}else{
				$setor_desc = "Setor Excluido";
			}
			
			if($oRow->status == "Aberto"){ 
				$cor_chamado = "red";
				$status = "Aberto";
				if ($dias>1) $sla = $sla + 1;
			}
			if($oRow->status == "Finalizado"){ 
				$cor_chamado = "#00BB27";
				$status = "Finalizado";				
			}
			if($oRow->status == "Em Andamento"){ 
				$cor_chamado = "#0A60D4";
				$status = "Em Andamento";
				if ($dias>1) $sla = $sla + 1;
			}
			if(($oRow->status == "Aguardando Finalizacao") && ($_SESSION["nivel_usuario"]!= "tecnico")){
				$status = "<button type=\"button\" onClick=\"avaliar('" . $oRow->codigo . "')\" class=\"btn btn-sm btn-danger\">Favor Finalizar</button>";
				$cor_chamado = "black";
			}
			if(($oRow->status == "Aguardando Finalizacao") && ($_SESSION["nivel_usuario"]== "tecnico")){
				if ($mobile == 0){
					$status = "<h5><span class=\"label label-default\">Aguardando Finalizar</span></h5>";
				}else{
					$status = "Aguardando Finalizar";
				}
				$cor_chamado = "black";
			}
			
			if(($_SESSION["nivel_usuario"]== "tecnico") && ($oRow->chamado_lido == 2)){
				$notifica = "red";
				$cor_chamado = "#FFFFFF";
				$ncount++;
			}else{
				$notifica = "#FFFFFF";	
			}
			
			if(($oRow->status == "Aguardando Finalizacao") && ($_SESSION["nivel_usuario"]!= "tecnico")){
	?>
              <tr style="font-size: 12; color: <?php echo $cor_chamado ?>;">
	<?php
			}else{
	?>
				  <tr ONMOUSEOVER="move_i(this)" ONMOUSEOUT="move_o(this)" onClick="visualizar('<?php echo $oRow->codigo ?>')" style="font-size: 12; color: <?php echo $cor_chamado ?>; background:<?php echo $notifica ?> ">
	<?php
			}
	?>
	<?php if ($mobile == 0){?>	
				<th><?php echo $oRow->codigo ?></th>
	<?php } ?>  
                <th><?php echo date('d/m/Y - H:i:s', strtotime($oRow->data_abertura)); ?></th>
                <th><?php echo $setor_desc ?></th>
	<?php if ($mobile == 0){?>
				<th><?php echo $oRow->titulo ?></th>
	<?php } ?>
				<th>
					<?php
						echo $status;
					?>
				</th>
				<th><?php echo $tempo ?></th>
			  </tr>
	<?php
		}
    ?>            
            </tbody>
          </table>
            </div>
        </div>
				<h4 align="right">
					<span  class="label label-primary"> <?php echo $num_registros . " Total de Registros"?></span>
	<?php if( ($_SESSION["nivel_usuario"]== "tecnico") && ($ncount>0) ){ ?>				
					<span  class="label label-info"> <?php echo $ncount . " Chamados Abertos e Nao Lidos";?></span>	
					<span  class="label label-danger"> <?php echo $sla . " SLA Estourado";?></span>				
	<?php } ?>			
				</h4>
<?php
	include "footer.php";
?>

</html>