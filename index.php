<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$codigo_correcto = "1234";

// LOGIN
if(isset($_POST['codigo'])){
    if($_POST['codigo'] == $codigo_correcto){
        $_SESSION['acceso'] = true;
    } else {
        $error = "Código incorrecto";
    }
}

// CONEXIÓN BD
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

// CERRAR SESIÓN
if(isset($_POST['logout'])){
    session_destroy();
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Sistema PRO</title>

<style>
body {
    font-family: Arial;
    background: linear-gradient(to right, #1db954, #121212);
    color: white;
    text-align: center;
    padding: 40px;
}

.box {
    background: white;
    color: black;
    padding: 25px;
    border-radius: 15px;
    width: 340px;
    margin: auto;
    box-shadow: 0px 0px 15px rgba(0,0,0,0.4);
}

input {
    padding: 10px;
    margin: 5px;
    width: 90%;
    border-radius: 8px;
    border: 1px solid #ccc;
}

button {
    background: #1db954;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

button:hover {
    background: #14833b;
}

.musica button {
    margin: 5px;
}
</style>
</head>

<body>

<div class="box">

<?php if(!isset($_SESSION['acceso'])) { ?>

    <h2>🔐 Acceso</h2>

    <form method="POST">
        <input type="password" name="codigo" placeholder="Ingresa tu PIN" required>
        <button type="submit">Entrar</button>
    </form>

    <br>

    <form method="POST">
        <button name="mostrarRegistro">Registrarse</button>
    </form>

    <?php if(isset($_POST['mostrarRegistro'])) { ?>

        <hr>

        <h2>👤 Crear cuenta</h2>

        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="correo" placeholder="Correo" required>
            <input type="text" name="telefono" placeholder="Teléfono" required>
            <button type="submit">Guardar</button>
        </form>

        <?php if(isset($mensaje)) echo "<p>$mensaje</p>"; ?>

    <?php } ?>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<?php } else { ?>

    <h2>✨ Bienvenida</h2>

    <form method="POST">
        <button name="logout">Cerrar sesión</button>
    </form>

    <hr>

    <h2>🔎 Buscar Usuario</h2>

    <form method="POST">
        <input type="text" name="correo" placeholder="Buscar correo">
        <button type="submit">Buscar</button>
    </form>

    <?php
    if(isset($_POST['correo'])){
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

    <hr>

    <h2>🎧 Música</h2>

    <div class="musica">
        <button onclick="abrir('Karol G')">Karol G</button>
        <button onclick="abrir('Bad Bunny')">Bad Bunny</button>
        <button onclick="abrir('Peso Pluma')">Peso Pluma</button>
        <button onclick="abrir('Natanael Cano')">Natanael Cano</button>
        <button onclick="abrir('Plim Plim')">Plim Plim</button>
        <button onclick="abrir('Calle 24')">Calle 24</button>
    </div>

    <br>

    <input type="text" id="busqueda" placeholder="Buscar canción">
    <button onclick="buscar()">Buscar</button>

<?php } ?>

</div>

<script>
function buscar(){
    let texto = document.getElementById("busqueda").value;
    let url = "https://www.youtube.com/results?search_query=" + encodeURIComponent(texto);
    window.open(url, "_blank");
}

function abrir(artista){
    let url = "https://www.youtube.com/results?search_query=" + encodeURIComponent(artista);
    window.open(url, "_blank");
}
</script>

</body>
</html>

