<?php

require('../../config.php');
require_login();
/** @var \context $context */
$context = \context_system::instance();
require_capability('local/api_escola:edit', $context);

$PAGE->set_url(new moodle_url('/local/api_escola/add_aluno.php'));
$PAGE->set_context($context);
$PAGE->set_title('Adicionar Aluno');
$PAGE->set_heading('Adicionar Aluno');

echo $OUTPUT->header();

// Botão voltar
$backurl = new moodle_url('/local/api_escola/alunos.php');

echo '<div class="text-center">';
echo html_writer::link($backurl, 'Voltar para Alunos', ['class' => 'btn btn-secondary']);
echo '<br><br>';
echo '</div>';

// =============================
// 1. Buscar cursos para o select
// =============================
$cursosEndpoint = "http://localhost/api-escola/cursos.json";
$cursosResponse = file_get_contents($cursosEndpoint);

if ($cursosResponse === false) {
    echo html_writer::tag('p', 'Erro ao carregar lista de cursos.', ['style' => 'color:red;']);
    echo $OUTPUT->footer();
    exit;
}

$cursos = json_decode($cursosResponse, true);

// =============================
// 2. Se o formulário foi enviado
// =============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'nome' => required_param('nome', PARAM_TEXT),
        'email' => required_param('email', PARAM_TEXT),
        'curso_id' => required_param('curso_id', PARAM_INT)
    ];

    // Enviar para API externa
    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data)
        ]
    ];

    $contexto = stream_context_create($options);
    $result = file_get_contents('http://localhost/api-escola/add_aluno.php', false, $contexto);

    if ($result === false) {
        echo html_writer::tag('p', 'Erro ao adicionar aluno na API.', ['style' => 'color:red;']);
    } else {
        redirect(new moodle_url('/local/api_escola/alunos.php'), 'Aluno adicionado com sucesso!', 2);
    }
}

?>

<form method="POST">
    <div class="text-center">

        <label>Nome do Aluno:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Curso:</label><br>
        <select name="curso_id" required>
            <option value="">Selecione um curso</option>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?php echo $curso['id']; ?>">
                    <?php echo $curso['nome']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <button type="submit" class="btn btn-primary">Adicionar Aluno</button>
    </div>
</form>

<?php
echo $OUTPUT->footer();