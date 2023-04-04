<?php



session_start();
require_once __DIR__ . "./../vendor/autoload.php";

use Src\Usuario;

$ciudades = Usuario::devolverCiudades();

if (isset($_POST['registro'])) {
    //proceso
    $errores = false;

    $email = trim($_POST['email']);
    $pass = trim($_POST['pass']);
    $ciudad = trim($_POST['ciudad']);
    //validaciones
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores = true;
        $_SESSION['err_email'] = "debes introducir un email valido";
    } else {
        if (Usuario::existeEmail($email)) {
            $errores = true;
            $_SESSION['err_email'] = "el email esta ya registrado";
        }
    }
    if (strlen($pass) < 6) {
        $errores = true;
        $_SESSION['err_pass'] = "la contraseña debe tener al menos 6 caracteres";
    }
    if (!in_array($ciudad, $ciudades)) {
        $errores = true;
        $_SESSION['err_ciudad'] = "la ciudad no esta registrada";
    }
    //si hay errores
    if ($errores) {
        header("Location:registrar.php");
        die();
    }
    //si no hay errores resgitramos usuario y logueamos
    (new Usuario)->setEmail($email)
        ->setPass($pass)
        ->setCiudad($ciudad)
        ->setPerfil("Usuario")
        ->create();

        $_SESSION['usuario'] = $email;
        header("Location:portal.php");
} else {
    //renderizo pagina
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

        <title>Register</title>
    </head>

    <body style="background-color:bisque">
        <section class="vh-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-dark text-white" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">

                                <div class="mb-md-5 mt-md-4 pb-5">
                                    <form name="login" action="registrar.php" method="POST">

                                        <h2 class="fw-bold mb-2 text-uppercase">REGISTRO</h2>


                                        <div class="form-outline form-white mb-4">

                                            <?php
                                            if (isset($_SESSION['err_email'])) {
                                                echo ("<p class='mb-2 text-danger text-sm italic'>{$_SESSION['err_email']}</p>");
                                                unset($_SESSION['err_email']);
                                            }
                                            ?>

                                            <input type="text" id="typeEmailX" class="form-control form-control-lg" name="email" />
                                            <label class="form-label" for="typeEmailX">Email</label>
                                        </div>

                                        <div class="form-outline form-white mb-4">
                                            <?php
                                            if (isset($_SESSION['err_pass'])) {
                                                echo ("<p class='mb-2 text-danger text-sm italic'>{$_SESSION['err_pass']}</p>");
                                                unset($_SESSION['err_pass']);
                                            }
                                            ?>

                                            <input type="password" id="typePasswordX" class="form-control form-control-lg" name="pass" />
                                            <label class="form-label" for="typePasswordX">Password</label>
                                        </div>
                                        <div class="form-outline form-white mb-4">
                                        
                                            <?php
                                            if (isset($_SESSION['err_ciudad'])) {
                                                echo ("<p class='mb-2 text-danger text-sm italic'>{$_SESSION['err_ciudad']}</p>");
                                                unset($_SESSION['err_ciudad']);
                                            }
                                            ?>
                                            <select name="ciudad" class="form-control form-control-lg">
                                                

                                                <label class="form-label" for="ciudad">Ciudad</label>

                                                <option value="-1">Selecciona una Ciudad</option>
                                                <?php
                                                foreach ($ciudades as $item) {
                                                    echo ("<option>$item </option>");
                                                }
                                                ?>

                                            </select>
                                            <label class="form-label" for="typePasswordX">Ciudad</label>
                                        </div>



                                        <button class="btn btn-outline-light btn-lg px-5" type="submit" name="registro">Registrar</button>

                                    </form>

                                </div>

                                <div>
                                    <p class="mb-0">¿Ya tienes cuenta? <a href="index.php" class="text-white-50 fw-bold">Logueate</a>
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>

    </html>

<?php } ?>