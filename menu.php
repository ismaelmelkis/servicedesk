<?php include "valida_user.inc"; 
if ($_SESSION["nivel_usuario"]== "tecnico"){
?>

<script type="text/javascript">
	//Verifica e solicita se o usuario tem permissao para utilizar as notificações do Chrome
        document.addEventListener('DOMContentLoaded', function () {
            if (!Notification) {
                alert('Desktop notifications not available in your browser. Try Chromium.');
                return;
            }

            if (Notification.permission !== "granted")
                Notification.requestPermission();
        });

        function minhaNotificao(data) {
            if (Notification.permission !== "granted") {
                Notification.requestPermission();
            }
            else {
                var notificacao = new Notification("SD - Chamados em Aberto", {
                    icon: 'img/logo150x150.png',
                    body: data
                });

                notificacao.onclick = function () {
                    window.location ='chamado_lista.php';
                };
				
				navigator.vibrate([200, 250, 300]);
            }
        }

	function ajax() {        
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "include/notificacao/cons_notificacao.php",
			//data: {seunome: seunome},
			success: function(data) {
				$('#conteudo').html(data);
				if(data != 0){
					minhaNotificao(data);
				}
			}
		});
	}
     setInterval("ajax()", 22000);//;
		//class="btn btn-lg btn-default"
</script>
	<div id="conteudo" hidden> </div>
<?php
}
?>
<nav class="navbar navbar-fixed-top navbar-inverse">	
	<div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
		  <!--span class="label label-default" style="font-size: 18">ServiceDesk</span-->
          <a class="navbar-brand" href="#">ServiceDesk :.</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Chamados <span class="caret"></span></a>
					<ul class="dropdown-menu">
					<?php if ($_SESSION["nivel_usuario"]!= "tecnico"){ ?>
						<li><a href="chamado_pendencia.php">Abrir Chamado</a></li>
						<li><a href="chamado_lista.php">Listar Chamados</a></li>
					<?php }else{ ?>
						<li><a href="chamado_novo.php">Abrir Chamado</a></li>
						<li><a href="chamado_lista.php">Listar Chamados</a></li>
						<li><a href="chamado_busca_rel.php">Relat&oacute;rio</a></li>
					<?php } ?>
					</ul>
				</li>
				<?php if ($_SESSION["nivel_usuario"]== "tecnico"){ ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Tipo de Chamado <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="tipochamado_novo.php">Cadastrar</a></li>
						<li><a href="tipochamado_lista.php">Listar</a></li>
					</ul>
				</li>
				<?php } ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Setor <span class="caret"></span></a>
					<ul class="dropdown-menu">
			<?php if ($_SESSION["nivel_usuario"]== "tecnico") {  ?>
						<li><a href="acesso_negado.php">Cadastrar</a></li>
						<li><a href="setor_lista.php">Listar</a></li>
			<?php }else{  ?>
						<li><a href="setor_muda_senha.php?id=<?php echo $_SESSION["setor_id"] ?>">Muda Senha</a></li>
			<?php } ?>
					</ul>
				</li>
				<?php if ($_SESSION["nivel_usuario"]== "tecnico"){ ?>
				<li><a href="estatistica_busca.php">Estat&iacute;stica</a></li>	
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ferramentas <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="acesso_negado.php">Atualiza&ccedil;&atilde;o</a></li>
						<li><a href="acesso_negado.php">Backup</a></li>
						<li><a href="acesso_negado.php">Acessos</a></li>
					</ul>
				</li>
				<?php } ?>
							
				<li><a href=""></a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="#"><span class="glyphicon glyphicon-user"></span> <?php echo $nome_usuario ?></a></li>
				<li><a href="<?php echo "logout.php" ?>"><span class="glyphicon glyphicon-log-in"></span> Sair</a></li>
			</ul>
        </div>
      </div>
</nav><!-- /.container --><!-- /.nav-collapse --><!-- /.navbar -->