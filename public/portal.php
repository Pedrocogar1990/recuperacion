<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location:index.php");
    die();
}
require_once __DIR__ . "./../vendor/autoload.php";

use Src\Usuario;

$usuarios = Usuario::read();

if (isset($_POST['cambiarPerfil'])) {
    $email = $_POST['email'];
    if (!Usuario::isAdmin($_SESSION['usuario'])) {
        $_SESSION['error'] = "solo pueden cambiar el perfil los admin";
        header("Location:portal.php");
        die();
    }
    if ($email== $_SESSION['usuario']) {
        $_SESSION['mensaje'] = "solo pueden cambiar el perfil los admin";
        header("Location:portal.php");
        die();
    }
    Usuario::update($email);
    header("Location:portal.php");
    

    
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CDN Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CDN FONTAWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CDN SeetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Portal</title>
</head>

<body style="background-color:bisque">
    <div class="d-flex flex-row-reverse my-2 mx-4">
        <div>
            <a href="cerrar.php" class="btn btn-danger ">
                <i class="fa-solid fa-right-from-bracket"></i> SALIR
            </a>
        </div>
        <div>
            <input type="text" readonly value="<?php echo $_SESSION['usuario'] ?>" class="bg-info form-control" />
        </div>
    </div>
    <div class="container">
        <h5 class=" text-center my-2">LISTADO DE USUARIOS REGISTRADOS</h5>
        <table class="table table-dark">
            <thead>
                <tr class="text-center">
                    <th scope="col">ID</th>
                    <th scope="col">EMAIL</th>
                    <th scope="col">CIUDAD</th>
                    <th scope="col">PERFIL</th>
                    <th scope="col">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($usuarios as $item) {
                    echo $cad = ($item->email == $_SESSION['usuario']) ? "*" : "";
                    echo $cad1 = ($item->email == $_SESSION['usuario']) ? "disabled" : "";

                    echo <<<TXT
                <tr class="text-center">
                   
                    <th scope="row">{$item->id}</th>
                    <td>{$item->email}$cad </td>
                    <td>{$item->ciudad}</td>
                    <td>{$item->perfil}</td>
                    <td>
                        <form action="portal.php" method="post">

                        <input type="hidden" value='{$item->email}' name="email">

                    
                        <button class="btn btn-danger" type="submit" name="cambiarPerfil" $cad1>
                        <i class="fas fa-trash">Cambiar Perfil</i>
                        </button>


                        </form>
                    </td>
                </tr>
                TXT;
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>