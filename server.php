<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "escola";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(array('error' => 'Falha na conexão com o banco de dados'));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Autenticação de Usuário
    if (isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password_hash'])) {
                echo json_encode(array('success' => true, 'redirect' => 'dashboard.html'));
                exit();
            } else {
                echo json_encode(array('error' => 'Senha incorreta'));
                exit();
            }
        } else {
            echo json_encode(array('error' => 'Usuário não encontrado'));
            exit();
        }
    }

    // Inserção de Estudante (mantive seu código original para inserção de estudante)
    if (isset($_POST['nome'], $_POST['ano_nascimento'], $_POST['sexo'], $_POST['curso'], $_POST['id_estudante'], $_POST['semestre'])) {
        $nome = $_POST['nome'];
        $anoNascimento = $_POST['ano_nascimento'];
        $sexo = $_POST['sexo'];
        $curso = $_POST['curso'];
        $idEstudante = $_POST['id_estudante'];
        $semestre = $_POST['semestre'];

        $stmt = $conn->prepare("INSERT INTO estudantes (nome, ano_nascimento, sexo, curso, id_estudante, semestre) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissis", $nome, $anoNascimento, $sexo, $curso, $idEstudante, $semestre);

        if ($stmt->execute()) {
            echo json_encode(array('success' => true, 'redirect' => 'success_message.html'));
            exit();
        } else {
            echo json_encode(array('error' => 'Erro ao cadastrar estudante'));
            exit();
        }
    }
}

echo json_encode(array('error' => 'Requisição inválida'));
exit();
?>