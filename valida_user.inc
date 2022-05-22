<?php

   @session_start(); // Inicializa a sessão

   if (IsSet($_SESSION["cod_usuario"]))
      $id_usuario = $_SESSION["cod_usuario"];

   if (IsSet($_SESSION["nom_usuario"]))
      $nome_usuario = $_SESSION["nom_usuario"];
      
   if (IsSet($_SESSION["pwd_usuario"]))
      $senha_usuario = $_SESSION["pwd_usuario"];

   if (IsSet($_SESSION["nivel_usuario"]))
      $nivel_usuario = $_SESSION["nivel_usuario"];

   if (IsSet($_SESSION["setor_id"]))
      $setor_id = $_SESSION["setor_id"]; 
	  
   if (IsSet($_SESSION["print_todos"]))
      $print_todos = $_SESSION["print_todos"];
      
   if (empty($nome_usuario) OR empty($senha_usuario)) {
      echo "<script>window.location='index.php'</script>";
   }

?>
