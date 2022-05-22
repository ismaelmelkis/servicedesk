
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
    <link rel="stylesheet" href="demos.css" type="text/css" media="screen" />

    <script src="include/RGraph/libraries/RGraph.common.core.js" ></script>
    <script src="include/RGraph/libraries/RGraph.bar.js" ></script>

<?php 
	if(isset($_POST["data1"])){
		if(($_POST["data1"] == "") && ($_POST["data2"] == "")){
			?>
                <script language="JavaScript">
                <!--
                alert("ATENCAO! \n Necessario Preencher o Periodo de Datas Incial e Final");
				window.location = 'estatistica_busca.php';
                //-->
                </script>
			<?php 
		}
	}
	
	$connect_new = mysqli_connect($Host, $Usuario, $Senha, $Base);
	//colocar ou se o post valor for igual a "";
	if(($_POST["data1"] != "") && ($_POST["data2"] != "")){
		$datai = date('Y-m-d', strtotime($_POST["data1"]));
		$dataf = date('Y-m-d', strtotime($_POST["data2"]));
		
		$a=0;$b=0;$c=0;$d=0;$e=0;$y=4;$w=0;$x=0;
		
		if($_POST["filtro"]== "avaliacao"){
			$a1 = "<img src=\"img/star/star01.png\" width=\"170\" height=\"30\"/>";
			$b1 = "<img src=\"img/star/star2.png\" width=\"170\" height=\"30\"/>";
			$c1 = "<img src=\"img/star/star3.png\" width=\"170\" height=\"30\"/>";
			$d1 = "<img src=\"img/star/star4.png\" width=\"170\" height=\"30\"/>";
			$e1 = "<img src=\"img/star/star5.png\" width=\"170\" height=\"30\"/>";
			$a2="Pessimo"; $b2="Ruim";$c2="Regular";$d2="Bom";$e2="Otimo";
			$cQuery =  mysqli_query($connect_new, "SELECT *
					FROM chamados
					WHERE avaliacao != 0 AND (CAST(data_abertura AS DATE) BETWEEN '" . $datai . "' AND '" . $dataf . "')");
			while($linha = mysqli_fetch_object($cQuery)){
				$avaliacao =  $linha->avaliacao;
				switch ($avaliacao) {
					case 1:
						$a = $a +1;
						break;
					case 2:
						$b = $b +1;
						break;
					case 3:
						$c = $c +1;
						break;
					case 4:
						$d = $d +1;
						break;
					case 5:
						$e = $e +1;
						break;
				}
				$w++;
			}
		} 

		if($_POST["filtro"]== "status"){
			$a1 = "Aberto";$b1 = "Em Andamento";$c1 = "Aguardando Finalizacao";$d1 = "Finalizado";$e1 = "Exluidos";
			$cQuery =  mysqli_query($connect_new, "SELECT *
					FROM chamados
					WHERE status != '' AND (CAST(data_abertura AS DATE) BETWEEN '" . $datai . "' AND '" . $dataf . "')");
			while($linha = mysqli_fetch_object($cQuery)){
				$status =  $linha->status;
				switch ($status) {
					case "Aberto":
						$a = $a +1;						
						break;
					case "Em Andamento":
						$b = $b +1;						
						break;
					case "Aguardando Finalizacao":
						$c = $c +1;						
						break;
					case "Finalizado":
						$d = $d +1;						
						break;
					case "":
						$e = $e +1;						
						break;
				}
				$w++;
			}
		}
	if($_POST["filtro"]== "setor"){
			
		$cQuery = mysqli_query($connect_new, "SELECT *
					FROM chamados
					WHERE setor_id != 0 AND (CAST(data_abertura AS DATE) BETWEEN '" . $datai . "' AND '" . $dataf . "') ORDER BY setor_id ASC");
		
		//$num_registros = mysqli_num_rows($cQuery);
		$id_setor = 0;
		$x=0;
		//Selecionando Dados Diferentes:.
		while($linha = mysqli_fetch_object($cQuery)){
			if ($id_setor == $linha->setor_id){
				$chamados_setor[$x] = $w +1;
				$setor[$x] = $linha->setor_id;
			}else{
				$x++;
				$w = 0;
				$chamados_setor[$x] = $w +1;
				$setor[$x] = $linha->setor_id;
			}
			$id_setor = $linha->setor_id;
		}
		/*	
		$aux = 1;
		$aux2 = 1;
		$aux3 = 0;
		$y=0;
		$z=0;
		$j=0;
		$add = false;
		$add2 = false;
		//$cod_setor[$y] = $dado[$y];
		//$array[$y] = 1;
		
		
		
		for($i = 0; $i < $x; $i++){
			$z = $i -1;
			if($dado[$z]==$dado[$i]){
					$cod_setor[$j] = $dado[$i];
					$y++;
					$array[$j] = $y;
				}else{
					$j++;
					$cod_setor[$j] = $dado[$i];
					$y = 0;
					$array[$j] = $y + 1;
				}
		}
	
		for($i = 1; $i < $x; $i++){		
			for($e = 0; $e < $aux2; $e++){
				if($dado[$e]==$dado[$i]){
					$cod_setor[$aux3] = $dado[$i];
					$array[$aux3] = $z + 1;
					$add2 = false;
					break;
				}else{
					$add2 = true;
				}
			}
			if(($add2)) {
			$aux3++;
			$cod_setor[$aux3] = $dado[$i];
			$z=0;
			$array[$aux3] = $z + 1;
			}//fecha if add
		}
		*/
		
	}
}
	if($_POST["filtro"]== "status"){
		$dados = $a . "," . $b  . "," . $c  . "," . $d  . "," . $e;
		$dados1 = "'" . $a1 . "','" . $b1  . "','" . $c1  . "','" . $d1  . "','" . $e1  . "'";
	}
	if($_POST["filtro"]== "avaliacao"){
		$dados = $a . "," . $b  . "," . $c  . "," . $d  . "," . $e;
		$dados1 = "'" . $a2 . "','" . $b2  . "','" . $c2  . "','" . $d2  . "','" . $e2  . "'";
	}
?>
    <h1>Acompanhamento de Chamados, Grafico por <b><?php echo $_POST["filtro"] ?> de Atendimento</b></h1>

    
    <div style="padding-left: 35px; display: inline-block">
        <canvas id="cvs" width="950" height="250">[No canvas support]</canvas>
    </div>



    <script>
        new RGraph.Bar({
            id: 'cvs',
            data: [<?php echo $dados ?>],
            options: {
                ymax: <?php echo $w ?>, //Percentual maximo para os graficos
                unitsPost: ' ',
                labelsAbove: true,
                labelsAboveDecimals: 0,
                labelsAboveUnitsPost: ' ',
                labelsAboveColor: 'black',
                labelsAboveSize: 10,
                hmargin: 20,
                colors: ['#E30513','#86B5BC','#1C1C1B','#86BC24','#E5007D','#2F8DCD','#F9D900','#F6A200','#BCBCBC'],
                colorsSequential: true,
                labels: [<?php echo $dados1 ?>], // Nome das Barras
                textSize: 10,
                textColor: 'gray',
                backgroundGridVlines: false,
                backgroundGridAutofitNumhlines: 4,
                backgroundGridBorder: false,
                noaxes: true,
                ylabelsCount: 4,
                title: '<?php echo "Dados Colhidos no periodo de " . date('d/m/Y', strtotime($_POST["data1"])) . " a " . date('d/m/Y', strtotime($_POST["data2"])) . " em um total de " . $w . " Atendimentos" ?>',
                titleX: 25,
                titleY: 0,
                titleHalign: 'left',
                titleColor: '#999',
                ylabelsOffsetx: -10
            }
        }).grow({frames: 60});
    </script>



    <div class="col-md-4">
          <table class="table table-striped">
            <thead>
              <tr>
                <th><?php echo strtoupper($_POST["filtro"]) ?></th>
                <th>Total de Atendimentos</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $a1 ?></td>
				<td align="center"><?php echo $a ?></td>
			  </tr>
			  <tr>
                <td><?php echo $b1 ?></td>
                <td align="center"><?php echo $b ?></td>
			  </tr>
			  <tr>			  
                <td><?php echo $c1 ?></td>
                <td align="center"><?php echo $c ?></td>
              </tr>
			  <tr>
                <td><?php echo $d1 ?></td>
                <td align="center"><?php echo $d ?></td>
               </tr>
			   <tr>
                <td><?php echo $e1 ?></td>
                <td align="center"><?php echo $e ?></td>
               </tr>
            </tbody>
          </table>
      
		<br/>
		<p>
			<a href="estatistica_busca.php">&laquo; Voltar</a>
		</p>
		<?php
			for($e = 1; $e < $x; $e++){
				echo "Codigo do Setor " . $setor[$e] . " Quanidade de Chamados: " . $chamados_setor[$e] . "<br/>";
			}
		?>
	</div>
<?php
	include "footer.php";
?>

</html>