<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$codigo_correcto = "1234";

// Validar código
if(isset($_POST['codigo'])){
    if($_POST['codigo'] == $codigo_correcto){
        $_SESSION['acceso'] = true;
    } else {
        $error = "Código incorrecto";
    }
}
$conn = new mysqli("localhost", "root", "", "app_db");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// REGISTRAR USUARIO
if(isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['telefono'])){
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $sql = "INSERT INTO usuarios (nombre, correo, telefono) 
            VALUES ('$nombre', '$correo', '$telefono')";

    if($conn->query($sql)){
        $mensaje = "Usuario registrado correctamente";
    } else {
        $mensaje = "Error al registrar";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Sistema de Usuarios</title>

<style>
body {
    font-family: Arial;
    background: linear-gradient(to right, #667eea, #764ba2);
    text-align: center;
    padding: 50px;
}

.box {
    background: white;
    padding: 30px;
    border-radius: 15px;
    width: 320px;
    margin: auto;
    box-shadow: 0px 0px 15px rgba(0,0,0,0.3);
}

input {
    padding: 10px;
    margin: 5px;
    width: 90%;
    border-radius: 8px;
    border: 1px solid #ccc;
}

button {
    background: #667eea;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

button:hover {
    background: #764ba2;
}
</style>
</head>

<body>

<div class="box">

<?php if(!isset($_SESSION['acceso'])) { ?>

    <h2>Acceso</h2>
<form method="POST">
        <input type="password" name="codigo" placeholder="Código" required>
        <button type="submit">Entrar</button>
    </form>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<?php } else { ?>

    <h2>Registrar Usuario</h2>

    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="correo" placeholder="Correo" required>
        <input type="text" name="telefono" placeholder="Teléfono" required>
        <button type="submit">Guardar</button>
    </form>

    <?php if(isset($mensaje)) echo "<p>$mensaje</p>"; ?>

    <hr>

    <h2>Buscar Usuario</h2>

    <form method="POST">
        <input type="text" name="correo" placeholder="Buscar correo">
        <button type="submit">Buscar</button>
    </form>

    <?php
    if(isset($_POST['correo']) && !isset($_POST['nombre'])){
        $correo = $_POST['correo'];

        $sql = "SELECT * FROM usuarios WHERE correo='$correo'";
        $result = $conn->query($sql);

        if($result && $result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                echo "<h3>Resultado</h3>";
                echo "Nombre: " . $row['nombre'] . "<br>";
                echo "Correo: " . $row['correo'] . "<br>";
                echo "Teléfono: " . $row['telefono'] . "<br>";
            }
        } else {
            echo "<p>No se encontró</p>";
        }
    }
    ?>

<?php } ?>

</div>

</body>
</html>

