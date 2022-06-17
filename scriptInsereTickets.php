<?php

$createdByUser = "";
$ligacao = mysqli_connect("database_url", "username", "password", "database_name");
$dados = file_get_contents("ticket_export.json");
$dados = json_decode($dados, true);
$dadosUsers = file_get_contents("users.json");
$dadosUsers = json_decode($dadosUsers, true);
$cont = 0;
//foreach($dados as )

for ($i = 4; $i < 3173; $i++) {
  $titulo = $dados['tickets'][$i]['summary'];
  $descricao = $dados['tickets'][$i]['description'];
  $dataCriacao = $dados['tickets'][$i]['created_at'];
  $timestamp = strtotime($dataCriacao);
  $new_date = date("Y-m-d H:m:s", $timestamp);
  $query_inserir = "INSERT INTO glpi_tickets (id,name, date,content,status,solvedate,date_mod) VALUES ($i,'$titulo','$new_date','$descricao',6,'$new_date','$new_date')";
  $createdByUser = $dados['tickets'][$i]['created_by'];
  mysqli_query($ligacao, $query_inserir);

  for ($j = 0; $j < 161; $j++) {

    $userID = $dadosUsers['users'][$j]['import_id'];

    if (empty($createdByUser)) {
      echo "fail empty<br>";
    } else {
      if (strcmp($userID, $createdByUser) == 0) {
        $cont++;
        $email = $dadosUsers['users'][$j]['email'];

        $query_inserir_users = "INSERT INTO glpi_tickets_users (tickets_id,alternative_email) VALUES ($i,'$email')";
        $ver = mysqli_query($ligacao, $query_inserir_users);
        //echo $email;
        if (!$ver) {
          echo mysqli_error($ligacao);
          die();
        } else {
          echo "Query succesfully executed!";
        }
      }
    }
  }
}

?>

