<?php
include('conexion.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre'], $_POST['email'], $_POST['edad']) && !isset($_POST['update'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $edad = $_POST['edad'];


    $stmt = $conn->prepare("INSERT INTO personas (nombre, email, edad) 
                           VALUES (:nombre, :email, :edad)");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':edad', $edad);
    $stmt->execute();
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM personas WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}


if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM personas WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $persona = $stmt->fetch();
}


if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $edad = $_POST['edad'];


    $stmt = $conn->prepare("UPDATE personas SET nombre = :nombre, email = :email, edad = :edad WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':edad', $edad);
    $stmt->execute();


    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CDatos a diligenciar:</title>
    

    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Datos a diligenciar: </h1>
    </header>

    <div class="container">
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="number" name="edad" placeholder="Edad" required>
            <button type="submit">Crear Persona</button>
        </form>

        <h2>Lista de Personas</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Edad</th>
                    <th>Estado de Edad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT * FROM personas");
                while ($row = $stmt->fetch()) {

                    $estadoEdad = ($row['edad'] >= 18) ? 'Mayor de Edad' : 'Menor de Edad';
                    
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nombre'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['edad'] . "</td>";
                    echo "<td>" . $estadoEdad . "</td>";  
                    echo "<td class='actions'>
                            <a href='?edit=" . $row['id'] . "'>Modificar</a>
                            <a href='?delete=" . $row['id'] . "'>Eliminar</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <?php if (isset($persona)): ?>
        <h2>Modificar Persona</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $persona['id'] ?>">
            <input type="text" name="nombre" value="<?= $persona['nombre'] ?>" required>
            <input type="email" name="email" value="<?= $persona['email'] ?>" required>
            <input type="number" name="edad" value="<?= $persona['edad'] ?>" required>
            <button type="submit" name="update">Actualizar Persona</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
