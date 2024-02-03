<?php
// Configuració de la connexió a la base de dades
$servername = "localhost";
$username = "roger";
$password = "roger";
$database = "calculadora_db";

// Creació de la connexió
$conn = new mysqli($servername, $username, $password, $database);

// Verificar que la connexió és correcta
if ($conn->connect_error) {
    die("Connexió a la base de dades fallida: " . $conn->connect_error);
}

// Funció per realitzar l'operació i registrar-la a l'historial
function realitzarOperacio($usuari, $operand1, $operand2, $operand3) {
    global $conn;

    // Comprovar si l'usuari ha estat emplenat
    if (empty($usuari)) {
        return "L'usuari no pot estar buit. Si us plau, introdueix un nom d'usuari.";
    }

    // Comprovar si Operand_1 i Operand_2 no estan buits
    if (empty($operand1) || empty($operand2)) {
        return "Operand_1 i Operand_2 buits, no es pot realitzar l'operació.";
    }

    // Consultar si aquesta operació ja ha estat realitzada anteriorment
    $consultaAnterior = "SELECT usuari, resultat FROM historial 
                         WHERE operand1='$operand1' AND operand2='$operand2' AND operand3='$operand3'
                         ORDER BY dataOperacio DESC LIMIT 1";

    $resultatConsulta = $conn->query($consultaAnterior);

    if ($resultatConsulta->num_rows > 0) {
        $row = $resultatConsulta->fetch_assoc();
        $usuariAnterior = $row["usuari"];
        $resultatAnterior = $row["resultat"];

        $sql = "INSERT INTO historial (usuari, operand1, operand2, operand3, resultat) 
                VALUES ('$usuari', '$operand1', '$operand2', '$operand3', '$resultatAnterior')";
        if ($conn->query($sql) === TRUE) {
            return "Resultat: $resultatAnterior (Aquesta operació ha estat realitzada anteriorment per: $usuariAnterior)";
        } else {
            return "Error al registrar l'operació a la base de dades: " . $conn->error;
        }
    }

    // Calcular el resultat
    if (is_numeric($operand1) && is_numeric($operand2)) {
        // Verificar si Operand_3 és numeric o buit
        if ($operand3 !== '' && is_numeric($operand3)) {
            $resultat = $operand1 + $operand2 + $operand3;
        } else {
            $resultat = $operand1 + $operand2;
            if ($operand3 !== '') {
                $resultat .= $operand3;
            }
        }
    } else {
        $resultat = $operand1 . $operand2 . $operand3;
    }

    // Registrar l'operació a la base de dades
    $sql = "INSERT INTO historial (usuari, operand1, operand2, operand3, resultat) 
            VALUES ('$usuari', '$operand1', '$operand2', '$operand3', '$resultat')";
    
    if ($conn->query($sql) === TRUE) {
        return "Resultat: $resultat";
    } else {
        return "Error al registrar l'operació a la base de dades: " . $conn->error;
    }
}

// Processar la sol·licitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuari = $_POST["usuari"];
    $operand1 = $_POST["operand1"];
    $operand2 = $_POST["operand2"];
    $operand3 = $_POST["operand3"];

    // Realitzar l'operació i obtenir el resultat
    $resultatOperacio = realitzarOperacio($usuari, $operand1, $operand2, $operand3);
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora</title>
</head>
<body>
    <h1>Calculadora</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="usuari">Nom d'usuari:</label>
        <input type="text" name="usuari" required><br>

        <label for="operand1">Operand_1:</label>
        <input type="text" name="operand1" required><br>

        <label for="operand2">Operand_2:</label>
        <input type="text" name="operand2" required><br>

        <label for="operand3">Operand_3:</label>
        <input type="text" name="operand3"><br>

        <button type="submit">Realitzar Operació</button>
    </form>

    <?php
    // Mostrar el resultat de l'operació
    if (isset($resultatOperacio)) {
        echo "<p>$resultatOperacio</p>";
    }
    ?>

</body>
</html>

<?php
// Tancar la connexió a la base de dades
$conn->close();
?>