<?php
session_start(); 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "colegio";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Id = intval($_POST['Id']);
    $Nombre = trim($_POST['Nombre']);
    $Correo = trim($_POST['Correo']);
    $Edad = intval($_POST['Edad']);
    $Grupo = trim($_POST['Grupo']);

    if ($Id <= 0) {
        $message = "<p style='color: red; text-align: center;'>El ID debe ser un número positivo.</p>";
    } elseif (empty($Nombre) || strlen($Nombre) > 100) {
        $message = "<p style='color: red; text-align: center;'>El nombre es obligatorio y no debe exceder 100 caracteres.</p>";
    } elseif (!filter_var($Correo, FILTER_VALIDATE_EMAIL)) {
        $message = "<p style='color: red; text-align: center;'>El correo electrónico no es válido.</p>";
    } elseif ($Edad < 1 || $Edad > 120) {
        $message = "<p style='color: red; text-align: center;'>La edad debe estar entre 1 y 120 años.</p>";
    } elseif (empty($Grupo) || strlen($Grupo) > 50) {
        $message = "<p style='color: red; text-align: center;'>El grupo es obligatorio y no debe exceder 50 caracteres.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO alumnos (Id, Nombre, Correo, Edad, Grupo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issis", $Id, $Nombre, $Correo, $Edad, $Grupo);

        if ($stmt->execute()) {
            $message = "<p style='color: green; text-align: center;'>✅ Registro guardado correctamente.</p>";
        } else {
            $message = "<p style='color: red; text-align: center;'>❌ Error al guardar: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Alumnos</title>
    <style>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #4a4a4a;
            font-size: 2em;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="number"],
        input[type="text"],
        input[type="email"] {
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            outline: none;
        }

        input[type="number"]:focus,
        input[type="text"]:focus,
        input[type="email"]:focus {
            border-color: #667eea;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
            transform: scale(1.02);
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        button {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

        button[type="submit"] {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        button[type="reset"] {
            background: #f44336;
            color: white;
            box-shadow: 0 4px 15px rgba(244, 67, 54, 0.3);
        }

        button[type="reset"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(244, 67, 54, 0.4);
        }

        /* Responsive */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
                margin: 20px;
            }
            h2 {
                font-size: 1.5em;
            }
            .buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registro de Alumnos</h2>
        <?php echo $message; ?>
        <form action="Esc.php" method="post">
            <label for="Id">Id:</label>
            <input type="number" id="Id" name="Id" required>
            
            <label for="Nombre">Nombre:</label>
            <input type="text" id="Nombre" name="Nombre" required>
            
            <label for="Correo">Correo:</label>
            <input type="email" id="Correo" name="Correo" required>
            
            <label for="Edad">Edad:</label>
            <input type="number" id="Edad" name="Edad" min="1" max="120" required>
            
            <label for="Grupo">Grupo:</label>
            <input type="text" id="Grupo" name="Grupo" required>
            
            <div class="buttons">
                <button type="submit">Guardar</button>
                <button type="reset">Resetear</button>
            </div>
        </form>
    </div>
</body>
</html>
