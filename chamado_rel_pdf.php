<?php

	require_once('include/fpdf/pdf.php');

	include "config.php";
	include "valida_user.inc";
	date_default_timezone_set('America/Sao_Paulo');
	$datatime = date('Y-m-d H:i');
	//$id = $_GET["id"];


//Colocando os Dados no arquivo em PDF :.
	
$pdf= new PDF("P","pt","A4");
$pdf->AddPage();

		$pdf->SetFont('arial','B',14);
		$pdf->Cell(0,20,utf8_decode("Relatório de Chamados"),0,1,'C');
		$pdf->Cell(0,5,"","B",1,'C');
		$pdf->Ln(8);

//Funcoes
	function diferenca($inicio , $fim , $saidaFormatada = '%a' ){
		$data1 = date_create($inicio);
		$data2 = date_create($fim);
		$interval = date_diff($data1, $data2);
		
		return $interval->format($saidaFormatada);
	}

//conectando ao banco
    $connect_new = mysqli_connect($Host, $Usuario, $Senha, $Base);
	
	if (isset($_GET["sql"])){
		$x=0;
		$select = $_SESSION["print_todos"];
		$tQuery =  mysqli_query($connect_new, $_SESSION["print_todos"]);
		while($tRow = mysqli_fetch_object($tQuery)){
			$tCodigo[$x] = $tRow ->codigo;
			$x = $x + 1;
			$codigot =  $tRow ->codigo;
		}
		$x = $x - 1;	
	}else{
		$x = 0;
		$tCodigo[$x] = $_GET["id"];
	}
	
for($w=0;$w<=$x;$w++){
 	$sql = "SELECT * FROM chamados WHERE codigo = " . $tCodigo[$w];
	//echo $sql . "<br/>";
 	$query = mysqli_query($connect_new, $sql);
	while($cRow = mysqli_fetch_object($query)){
		
		if($cRow->patch_upload != ""){
			$patch_upload = "Sim"; 
		}else{ 
			$patch_upload = "Nao";
		}
		
		$avaliacao = $cRow->avaliacao;
		
		switch ($avaliacao) {
			case 0:
				$msg = "Sem Avaliação";
				break;
			case 1:
				$msg = "Nota 1 = Péssimo";
				break;
			case 2:
				$msg = "Nota 2 = Ruim";
				break;
			case 3:
				$msg = "Nota 3 = Regular";
				break;
			case 4:
				$msg = "Nota 4 = Bom";
				break;
			case 5:
				$msg = "Nota 5 = Ótimo";
				break;
		}
		
		//Diferença de Tempo
		if (isset($cRow->data_fecha)) {
			$dt_fecha = date('Y-m-d', strtotime($cRow->data_fecha));;
			$hs_fecha = date('H:i:s', strtotime($cRow->data_fecha));;
		}else{
			$dt_fecha = date("Y-m-d");
			$hs_fecha = date("H:i:s");
		}
			//Calculando Dias
			$dt_abri = date('Y-m-d', strtotime($cRow->data_abertura));; 
			$dif1 = strtotime($dt_fecha) - strtotime($dt_abri);
			$dias1 = ($dif1 / 86400%86400);
			$dias = round($dias1, 0);//Arredondando
			//echo $dt_abri;
				
			//Calculando Minutos
			$hs_abri = date('H:i:s', strtotime($cRow->data_abertura));;
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
		
		$data_abertura = date('d/m/Y - H:i:s', strtotime($cRow->data_abertura));
		
		if($cRow->data_fecha != ""){ 
			$data_fecha = date('d/m/Y - H:i:s', strtotime($cRow->data_fecha)); 
		}else{ 
			$data_fecha = "Aguardando";
		}
		
		$sQuery = mysqli_query($connect_new, "SELECT * FROM setor WHERE setor_id = ". $cRow->setor_id);
		$sRow = mysqli_fetch_object($sQuery);
		
			if(isset($sRow->setor_desc)){
				$setor = $sRow->setor_desc;
			}else{
				$setor = "Setor Excluido";
			}
			
			if ($cRow->status == "Aguardando Finalizacao"){
				$cRow->status = "Aguard. Avaliar";
			}
				
				$pdf->SetFont('arial','B',11);
				$pdf->Cell(170,20,utf8_decode("Número do Chamado: " . $cRow->codigo),1,1,"L");
				$pdf->Cell(70,15,"",0,1,"L");
				
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(250,15,"Nome",0,0,"L");
				$pdf->Cell(100,15,"Setor",0,1,"L");
				
				$pdf->SetFont('arial','',9);				
				$pdf->Cell(250,15,utf8_decode($cRow->nome),0,0,"L");
				$pdf->Cell(100,15,utf8_decode($setor),0,1,"L");
				$pdf->Cell(70,15,"",0,1,"L");
				
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(70,15,"Status",0,0,"L");
				$pdf->Cell(110,15,"Aberto em",0,0,"L");
				$pdf->Cell(240,15,"Tipo de Chamado",0,0,"L");
				$pdf->Cell(100,15,utf8_decode("Avaliação"),0,1,"L");
				
				$pdf->SetFont('arial','',9);
				$pdf->Cell(70,15,$cRow->status,0,0,"L");
				$pdf->Cell(110,15,$data_abertura,0,0,"L");
				$pdf->Cell(240,15,utf8_decode($cRow->tipo),0,0,"L");
				$pdf->Cell(100,15,utf8_decode($msg),0,1,"L");

				$pdf->SetFont('arial','B',10);
				$pdf->Cell(70,15,"Anexo",0,0,"L");
				$pdf->Cell(110,15,"Fechado em",0,0,"L");
				$pdf->Cell(240,15,utf8_decode("Título"),0,0,"L");
				$pdf->Cell(100,15,"Tempo para Finalizar",0,1,"L");
				
				$pdf->SetFont('arial','',9);
				$pdf->Cell(70,15,$patch_upload,0,0,"L");
				$pdf->Cell(110,15,$data_fecha,0,0,"L");
				$pdf->Cell(240,15,utf8_decode($cRow->titulo),0,0,"L");
				$pdf->Cell(100,15,$tempo,0,1,"L");	
				$pdf->Cell(70,15,"",0,1,"L");
				
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(500,15,utf8_decode("Descrição do Problema"),0,1,"L");
				$pdf->SetFont('arial','',9);
				$pdf->MultiCell(550,12, utf8_decode($cRow->descricao),0,'c');
				$pdf->Cell(70,15,"",0,1,"L");
				
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(500,15,utf8_decode("Finalizado por "),0,1,"L");
				$pdf->SetFont('arial','',9);
				$pdf->MultiCell(200,12, utf8_decode($cRow->tecnico),0,'c');
				$pdf->Cell(70,15,"",0,1,"L");
				
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(500,15,utf8_decode("Solução da T.I"),0,1,"L");
				$pdf->SetFont('arial','',9);
				$pdf->MultiCell(200,12, utf8_decode($cRow->solucao),0,'c');
				
				$pdf->Cell(500,15,"",0,1,"L");
				
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(500,15,utf8_decode("Observações do Usuário"),0,1,"L");
				$pdf->SetFont('arial','',9);
				$pdf->MultiCell(550,12, utf8_decode($cRow->obs),0,'c');
				
				$pdf->Cell(500,15,"",0,1,"L");
				$pdf->Cell(0,5,"","B",1,'C');
				$pdf->Ln(8);
				$pdf->Cell(500,15,"",0,1,"L");
	}
}
/*		
		$sql2 = "SELECT * FROM estoque WHERE est_data = '" . $estoque_data[$x] . "' AND est_lanc='" .   $tipo_lancamento[$y] . "'";
 		$query2 = mysqli_query($sql2);		
				
		while($Row2 = mysqli_fetch_object($query2)){
		
		
		
			$query_sql3 = mysqli_query("SELECT pr_cod, pr_desc FROM produto WHERE pr_cod = " . $Row2->est_cod_pr);	
			
			while($Row3 = mysqli_fetch_object($query_sql3)){	
				
				$pdf->SetFont('arial','',9);
				$pdf->Cell(60,15,$Row2->est_cod_pr,0,0,"L");
				$pdf->Cell(240,15,$Row3->pr_desc,0,0,"L");
				$pdf->Cell(60,15,$Row2->est_quant,0,0,"L");
				$pdf->Cell(70,15,"R$ " . $Row2->est_custo,0,0,"L");
				$pdf->Cell(110,15,$Row2->est_fornecedor,0,1,"L");
				
				$total_estoque += $Row2->est_custo;
				$total_quant += $Row2->est_quant;
				$total_geral += $Row2->est_custo * $Row2->est_quant;
				
				if($tipo_lancamento[$y] == "Entrada")
					$total_liquido_entrada += $Row2->est_custo * $Row2->est_quant;
				
				if($tipo_lancamento[$y] == "Producao")
					$total_liquido_producao += $Row2->est_custo * $Row2->est_quant;
				
				if($tipo_lancamento[$y] == "Consumo")
					$total_liquido_consumo += $Row2->est_custo * $Row2->est_quant;
			}			
			
		}
				$total_estoque = $total_geral; 
				$total_liquido_periodo[$y] += $total_estoque;
				$total_estoque=number_format($total_estoque,2,',','.');

				
				$pdf->SetFont('arial','B',10);
				$pdf->Cell(380,15,"",0,0,"C");
				$pdf->Cell(150,15,"Total R$ " . $total_estoque,1,1,"R");
				$pdf->Cell(150,15,"",0,1,"C");
				 
				$total_estoque = 0;
				$total_geral = 0;
	
		}
	
	}
}
		
	$total_liquido_periodo=number_format($total_liquido_periodo,2,',','.');
	$total_liquido_entrada=number_format($total_liquido_entrada,2,',','.');
	$total_liquido_producao=number_format($total_liquido_producao,2,',','.');
	$total_liquido_consumo=number_format($total_liquido_consumo,2,',','.');
	
	$pdf->Cell(190,15,"",0,1,"L");
	$pdf->SetFont('arial','B',12);
	$pdf->Cell(0,25," Durante o periodo de " . mask_data($data_dbi) . " a " . mask_data($data_dbf),1,1,"C");
	$pdf->Cell(170,15," ",0,1,"L");
	$pdf->Cell(170,10,"Total Estoque Entrada: R$" . $total_liquido_entrada ,0,1,"L");
	$pdf->Cell(170,10," ",0,1,"L");
	$pdf->Cell(170,10,"Total Estoque Producao: R$" . $total_liquido_producao ,0,1,"L");
	$pdf->Cell(170,10," ",0,1,"L");
	$pdf->Cell(170,10,"Total Estoque Consumo: R$" . $total_liquido_consumo ,0,1,"L");
	$pdf->Cell(170,10," ",0,1,"L");
	
	$pdf->SetFont('arial','B',12);
	
	for($w=0;$w<=2;$w++){
		$total_liquido_periodo[$w]=number_format($total_liquido_periodo[$w],2,',','.');
		$pdf->Cell(550,15,"Valor Total do Lancamento de " . $est_lanc[$w] . ": R$ " . $total_liquido_periodo[$w] ,1,1,"C");
		$pdf->Cell(150,15," ",0,1,"L");
	}
	*/
	
$pdf->Output();

?>