<?php
include("connection.php");
// Seguraça verificar se usuário está logado para envio de arquivos

if(isset($_GET['delete'])){

    $id = intval($_GET['delete']);
    $sql = $mysqli->query("SELECT * FROM upload WHERE id= '$id'limit 1") or die(mysqli_error($mysqli));
    $row = $sql->fetch_assoc();
    
    if(unlink($row['path'])){
        $success= $mysqli->query("DELETE FROM upload WHERE id= '$id'") or die(mysqli_error($mysqli));
        if($success){
            echo "Arquivo excluido com sucesso!";
        }
    };


}
function sendFile($error,$size,$name,$tmp_Name){

    include("connection.php");


    if($error){
        die('Falha ao enviar arquivos!');
    };



    if($size > 2097152){
        die('Arquivo gigante! Máximo: 2MB');
    }

    $localsave = "arquivos/";
    $fileName = $name;
    $newName = uniqid();

    //$extension = pathinfo($fileName, PATHINFO_EXTENSION);
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if($extension !="jpg" && $extension != "png"){
        die ("Tipo de arquivo não permitido!");
   
    }
    $path = $localsave. $newName .".".$extension;
    $saved = move_uploaded_file($tmp_Name, $path);



    if($saved){
        
        $mysqli->query("INSERT INTO upload(path,data,name) VALUES('$path',Now(),'$fileName')")
        or die($mysqli->error);
        return true;
    }else{
       return false;
    }


}

//var_dump($_FILES);
if(isset($_FILES['upload'])){
    $myFile = $_FILES['upload'];
    $working=true;
    foreach ($myFile['name'] as $index =>$arqs) {
        $itsWorking = sendFile($myFile['error'][$index], $myFile['size'][$index], $myFile['name'][$index], $myFile['tmp_name'][$index]);
        if(!$working){   
            $working=false;
        };
    }
    if($itsWorking){
        echo "Arquivos salvos com sucesso";
    }else{
        echo "Arquivos não salvos error";
    }

    

    

}


/*

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
        
    
}*/


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
                <th>Apagar arquivo</th>
            </tr>
            <tbody>
            <?php
                while($row = $sql_query->fetch_assoc()){

                ?>
                <tr>
                    <td><img src="<?php echo $row['path']; ?>" alt="" width="36px"></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo date("d/m/Y H:i", strtotime($row['data'])); ?></td>
                    <td><a href="index.php?delete=<?php echo $row['id'];?>"><button style="color: yellow; background-color:red; width:60px; height:30px"><strong>Deletar</strong></button></a> </td>
                </tr>
                <?php } ?>

            </tbody>
        </thead>
    </table>




</body>
</html>