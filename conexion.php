<?php
// Conexión a la base de datos
$conexion = mysqli_connect('localhost', 'u973804478_personas', 'Reinventa_86140255', 'u973804478_personas');
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Procesamiento del formulario
if (isset($_POST['enviar'])) {
    if (validarEntrada($_POST)) {
        if (insertar($conexion)) {
            enviarCorreo($_POST['nombre'], $_POST['telefono'], $_POST['correo'], $_POST['mensaje']);
            // Redirigir a la página de confirmación
            header("Location: confirmacion-contacto.html");
            exit();
        }
    } else {
        echo "Datos no válidos.";
    }
}

function validarEntrada($datos) {
    // Aquí deberías implementar la lógica de validación de los datos recibidos del formulario
    // Esta es una simplificación. Deberías validar cada campo según tus requisitos.
    return isset($datos['nombre'], $datos['telefono'], $datos['correo'], $datos['mensaje']);
}

function insertar($conexion) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $mensaje = mysqli_real_escape_string($conexion, $_POST['mensaje']);

    // Utiliza sentencias preparadas para una mayor seguridad
    $stmt = $conexion->prepare("INSERT INTO persona (nombre, telefono, correo, mensaje) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $telefono, $correo, $mensaje);

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        echo "Error al insertar datos: " . $stmt->error;
        $stmt->close();
        return false;
    }
}

function enviarCorreo($nombre, $telefono, $correo, $mensaje) {
    $destinatario = "reservas@walletrip.com";
    $asunto = "Nuevo Contacto";
    $cuerpoMensaje = "Te ha contactado una nueva persona:\nNombre: $nombre\nTeléfono: $telefono\nCorreo: $correo\nMensaje: $mensaje";

    // Asegúrate de configurar correctamente tu servidor para enviar correos
    if (mail($destinatario, $asunto, $cuerpoMensaje)) {
        echo "Correo enviado correctamente.";
    } else {
        echo "Error al enviar el correo.";
    }
}
?>
