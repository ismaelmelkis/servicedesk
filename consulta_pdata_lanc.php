<?php
    $date = date("d/m/20y");
    $hora = date("H:i");

     include "config.php";
     include "valida_user.inc";
     include "layout.php";
	 include "include/calendario.js";
?>


<html>

<head>
<title><?php echo $Title ?></title>
<base target="principal">
</head>

 	<style>
    .dia {font-family: helvetica, arial; font-size: 8pt; color: #FFFFFF}
    .data {font-family: helvetica, arial; font-size: 8pt; text-decoration:none; color:#191970}
    .mes {font-family: helvetica, arial; font-size: 8pt}
    .Cabecalho_Calendario {font-family: helvetica, arial; font-size: 10pt; color: #000000; text-decoration:none; font-weight:	bold}
	</style>

<script>
function validaForm() {
	d=document.form1;
	//Campo Data:
	//Validando o campo data com os parametros aproriados;		        
	d.data.value="";
	erro=0;
    hoje = new Date();
    diaAtual = hoje.getDay();
	mesAtual = hoje.getMonth();
	anoAtual = hoje.getFullYear();
    barras = d.data.value.split("/");
    if (barras.length == 3){
     	dia = barras[0];
        mes = barras[1];
        ano = barras[2];
    resultado = (!isNaN(dia) && (dia > 0) && (dia < 32)) && (!isNaN(mes) && (mes > 0) && (mes < 13)) && (!isNaN(ano) && (ano.length == 4) && (ano <= anoAtual && ano >= 1999));
     if (!resultado) {
         alert("Formato de data invalido!");
         d.data.focus();
         return false;
         }
     } else {
         alert("Formato de data invalido!");
         d.data.focus();
         return false;
         }
  	
 return true;
}

      function mudacor(ref,cor){
        ref.style.backgroundColor=cor;
      }

</script>
		<div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Meus Chamados</h3>
            </div>
            <div class="panel-body">

<form name="form1" method="POST" action="tipo_relatorio.php" onSubmit="return validaForm()">
<p align="center">
<b><font size="4" face="Arial" color="<?php echo $cor_outros_textos ?>">Tipo de Relatorio: 
		<select style="font-size:16px" name="tipo_relatorio" title="Tipo de Ralatorio">
					<option value="mesa">Vendas por Pedidos</option>
                    <option value="estoque">Movimentacao de Estoque</option>
                    <option value="financeiro">Lancamentos Financeiro</option>
       			</select>
</font></b>
</p>
<p align="center">
  <b><font size="2" face="Arial" color="<?php echo $cor_outros_textos ?>">Consultar a data </b>
  								<div class="col-md-6">
									<label label-default="" for="">Label 8</label>
									<input type="date" name="date" class="form-control">
								</div>a
<input type="text" readonly maxlength="50" name="dataf" size="10" value="<?php echo $date ?>">
<input TYPE="button" NAME="btnData1" VALUE="data" Onclick="javascript:popdate('document.form1.dataf','pop1','150',document.form1.dataf.value)">
			
 
 <input type="submit" value="OK" name="B1"> <br/>
</form>

 </center>
<p align="right">&nbsp;</p>

</div>
</div>

</html>
