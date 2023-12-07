<?php
include("connection.php");
// Seguraça verificar se usuário está logado para envio de arquivos
//DEBUG
/*
echo"<pre>";
var_dump($_FILES);
echo "</pre>";*/

if(count($_FILES) > 0){
    
}

if(isset($_FILES['upload'])){
    $myFile = $_FILES['upload'];



    if($myFile["error"]){
        die('Falha ao enviar arquivos!');
    };



    if($myFile["size"] > 2097152){
        die('Arquivo gigante! Máximo: 2MB');
    }

    $localsave = "arquivos/";
    $fileName = $myFile['name'];
    $newName = uniqid();

    //$extension = pathinfo($fileName, PATHINFO_EXTENSION);
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if($extension !="jpg" && $extension != "png"){
        die ("Tipo de arquivo não permitido!");
   
    }
    $path = $localsave. $newName .".".$extension;
    $saved = move_uploaded_file($myFile["tmp_name"], $path);



    if($saved){

        $mysqli->query("INSERT INTO upload(path,data,name) VALUES('$path',Now(),'$fileName')")
        or die($mysqli->error);
        echo "Arquivos salvo com sucesso!";

    }else{
        echo "Erro ao enviar arquivo!";
    }
        
    
}


$sql_query = $mysqli->query("SELECT * FROM upload") or die($mysqli->error);

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Single and Multiples files</title>
</head>
<body>

    <form enctype="multipart/form-data" action="" method="post">
        <p><label for="">Selecione o arquivo</label>
        <input multiple type="file" name="upload[]"></p>
        <button type="submit"> Enviar Arquivos</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>Previews</th>
                <th>Arquivo</th>
                <th>Data de envio</th>
            </tr>
            <tbody>
            <?php
                while($row = $sql_query->fetch_assoc()){

                ?>
                <tr>
                    <td><img src="<?php echo $row['path']; ?>" alt="" width="36px"></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo date("d/m/Y H:i", strtotime($row['data'])); ?></td>
                </tr>
                <?php } ?>

            </tbody>
        </thead>
    </table>




</body>
</html>