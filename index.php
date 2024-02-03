<?php
//Configuració de la connexió a la db
$servername = "localhost";
$username = "roger";
$password = "roger";
$database = "calculadora_db";

//Creació de la connexió
$conn = new mysqli($servername, $username, $password, $database);

//Verificar que la connexió és correcta
if ($conn->connect_error) {
    die("Connexió a la base de dades fallida.");
}

//Funció per realitzar l'operació i registrar-la a l'historial
function realitzarOperacio($user, $operand1, $operand2, $operand3) {
    global $conn;

    //Comprovar si l'usuari és buit
    if (empty($user)) {
        return "L'usuari no pot estar buit. Si us plau, introdueix un nom d'usuari.";
    }

    //Comprovar si els operands 1 i 2 estan buits
    if (empty($operand1) || empty($operand2)) {
        return "Operand 1 o 2 buits, no es pot realitzar l'operació.";
    }

    //Consultar si aquesta operació ja ha estat realitzada anteriorment
    $consultaAnterior = "SELECT usuari, resultat FROM historial 
                         WHERE operand1='$operand1' AND operand2='$operand2' AND operand3='$operand3'
                         ORDER BY dataOperacio DESC LIMIT 1";

    $resultConsulta = $conn->query($consultaAnterior);

    //Si obtenim una fila, inserim la operació actual a la BD i mostrem el resultat anterior.
    if ($resultConsulta->num_rows > 0) {
        $row = $resultConsulta->fetch_assoc();
        $usuariAnterior = $row["usuari"];
        $resultatAnterior = $row["resultat"];

        $sql = "INSERT INTO historial (usuari, operand1, operand2, operand3, resultat) VALUES ('$user', '$operand1', '$operand2', '$operand3', '$resultatAnterior')";
        if ($conn->query($sql) === TRUE) {
            return "Resultat: $resultatAnterior (Aquesta operació ha estat realitzada anteriorment per: $usuariAnterior)";
        } else {
            return "Error al registrar l'operació a la base de dades.";
        }
    }

    //Calcular el resultat
    //Comprovar si els dos primers operands son numérics
    if (is_numeric($operand1) && is_numeric($operand2)) {
        //Verificar si l'operand 3 és buit o numèric
        if ($operand3 !== '' && is_numeric($operand3)) {
            //Si els 3 són numèrics, se sumen
            $resultat = $operand1 + $operand2 + $operand3;
        } elseif ($operand3 == '') {
            //Si l'operand 3 és buit, es sumen els dos primers
            $resultat = $operand1 + $operand2;
        } else {
            //Si l'operand 3 és alfanumèric, es concatenen tots tres
            $resultat = $operand1 . $operand2 . $operand3;
        }
    } else {
        // Si algun dels dos primers operands no és numèric, es concatenen tots tres
        $resultat = $operand1 . $operand2 . $operand3;
    }
    
    //Registrar l'operació a la base de dades
    $sql = "INSERT INTO historial (usuari, operand1, operand2, operand3, resultat) VALUES ('$user', '$operand1', '$operand2', '$operand3', '$resultat')";
    
    if ($conn->query($sql) === TRUE) {
        return "Resultat: $resultat";
    } else {
        return "Error al registrar l'operació a la base de dades.";
    }
}

//Processar la sol·licitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST["usuari"];
    $operand1 = $_POST["operand1"];
    $operand2 = $_POST["operand2"];
    $operand3 = $_POST["operand3"];

    //Realitzar l'operació i obtenir el resultat
    $resultatOperacio = realitzarOperacio($user, $operand1, $operand2, $operand3);
}
?>
<!-- Petita pàgina web amb un formulari per a la calculadora -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora</title>
</head>
<body>
    <h1>Calculadora</h1>
    <!-- Formulari calculadora -->
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
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
    //Mostrar el resultat de l'operació
    if (isset($resultatOperacio)) {
        echo "<p>$resultatOperacio</p>";
    }
    ?>

</body>
</html>

<?php
//Tancar la connexió a la base de dades
$conn->close();
?>