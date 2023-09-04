<?php
    require 'User.php';

    if($_POST){
        if(isset($_POST['submit'])){
            $user = new User();
            $user->name = $_POST['name'];
            $user->email = $_POST['email'];
            $user->password = $_POST['password'];

            $user->setUser($pdo, $user->name, $user->email, $user->password);
        }else if(isset($_POST['submit-confirm'])){
            echo '<meta http-equiv="refresh" content="0; url=/atividadePDO">';
            $user = new User();
            $user->updateUser($pdo, $_GET['id'], $_POST['name'], $_POST['email']);
            
        }else if(isset($_POST['submit-cancel'])){
            echo '<meta http-equiv="refresh" content="0; url=/atividadePDO">';
        }
    }

    if($_GET){
        if(isset($_GET['id']) && $_GET['type'] == "delete" ){
            $user = new User();
            $user->deleteUser($pdo, $_GET['id']);
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        td{
            border: 1px solid black;
            text-align: center;
            padding: 3px;
        }
        #tb-id{
            width:40px;
        }
        #tb-nm{
            width:120px;
        }
        #tb-em{
            width:200px;
        }
        #tb-ex{
            width:70px;
        }
        #tb-ed{
            width: 70px;
        }
    </style>
</head>
<body>
    <form method="post" name="frmcadastro">
        <h2>Cadastrar cliente</h2>
        <input type="text" name="name" id="name" placeholder="Digite o nome:">
        <br><br>
        <input type="email" name="email" id="email" placeholder="Digite o email:">
        <br><br>
        <input type="password" name="password" id="password" placeholder="Digite a senha:">
        <br><br>
        <button name="submit">Cadastrar</button>
    </form>
    <?php
        if($_GET){
            if(isset($_GET['id']) && $_GET['type'] == "edit" ){    
                $user = new User();
                $stmt = $user->selectUser($pdo, "", "WHERE cd = ".$_GET['id']);
                foreach($stmt as $row){
                    echo '
                        <form method="post">
                            <h2>Editando dados do usuário '.$row['cd'].'</h2>
                            <input hidden type="text" name="cd" value="'.$row['cd'].'">
                            <input type="text" name="name" id="name" value="'.$row['name'].'">
                            
                            <input type="email" name="email" id="email" value="'.$row['email'].'">
                            
                            <button name="submit-confirm">Editar</button>
                            <button name="submit-cancel">Cancelar</button>
                        </form>
                    ';
                }
            }
        }

        $user = new User();
        $stmt = $user->selectUser($pdo, "", "");
        if($stmt->rowcount() == 0){
            echo "<h2>Nenhum usuário cadastrado!</h2>";
        }else{
            echo "
                <h2>Usuários cadastrados:</h2>
                <table>
                    <tr>
                        <td id='tb-id'>ID</td>
                        <td id='tb-nm'>Nome</td>
                        <td id='tb-em'>Email</td>
                        <td id='tb-ex'>Excluir</td>
                        <td id='tb-ed'>Editar</td>
                    </tr>
            ";
            foreach($stmt as $row){
                echo "
                    <tr>
                        <td>".$row['cd']."</td>
                        <td>".$row['name']."</td>
                        <td>".$row['email']."</td>
                        <td><a href='?id=".$row['cd']."&type=delete'>Delete</a></td>
                        <td><a href='?id=".$row['cd']."&type=edit'>Editar</a></td>
                    </tr>
                ";
            }
            echo "</table>";
        }
    ?>
</body>
</html>