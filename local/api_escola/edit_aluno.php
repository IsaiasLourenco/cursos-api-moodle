<?php

require('../../config.php');
require_login();
/** @var \context $context */
$context = \context_system::instance();
require_capability('local/api_escola:edit', $context);

$id = required_param('id', PARAM_INT);

// =============================
// 1. Buscar dados do aluno
// =============================
$endpoint = "http://localhost/api-escola/get_aluno.php?id={$id}";
$response = file_get_contents($endpoint);

if ($response === false) {
    throw new moodle_exception('Erro ao acessar API externa.');
}

$aluno = json_decode($response, true);

if (isset($aluno['error'])) {
    throw new moodle_exception('Aluno não encontrado na API externa.');
}

// =============================
// 2. Buscar lista de cursos
// =============================
$cursosResponse = file_get_contents("http://localhost/api-escola/cursos.json");

if ($cursosResponse === false) {
    throw new moodle_exception('Erro ao carregar lista de cursos.');
}

$cursos = json_decode($cursosResponse, true);

// =============================
// 3. Configuração da página
// =============================
$PAGE->set_url(new moodle_url('/local/api_escola/edit_aluno.php', ['id' => $id]));
$PAGE->set_context($context);
$PAGE->set_title('Editar Aluno');
$PAGE->set_heading('Editar Aluno');

echo $OUTPUT->header();

// Botão voltar
$backurl = new moodle_url('/local/api_escola/alunos.php');

echo '<div class="text-center">';
echo html_writer::link($backurl, 'Voltar para Alunos', ['class' => 'btn btn-secondary']);
echo '<br><br>';
echo '</div>';

// =============================
// 4. Se o formulário foi enviado
// =============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'id' => $id,
        'nome' => required_param('nome', PARAM_TEXT),
        'email' => required_param('email', PARAM_TEXT),
        'curso_id' => required_param('curso_id', PARAM_INT)
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data)
        ]
    ];

    $contexto = stream_context_create($options);
    $result = file_get_contents('http://localhost/api-escola/update_aluno.php', false, $contexto);

    if ($result === false) {
        echo html_writer::tag('p', 'Erro ao atualizar aluno na API.', ['style' => 'color:red;']);
    } else {
        redirect(new moodle_url('/local/api_escola/alunos.php'), 'Aluno atualizado com sucesso!', 2);
    }
}

?>

<form method="POST">
    <div class="text-center">

        <label>Nome do Aluno:</label><br>
        <input type="text" name="nome" value="<?php echo $aluno['nome']; ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo $aluno['email']; ?>" required><br><br>

        <label>Curso:</label><br>
        <select name="curso_id" required>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?php echo $curso['id']; ?>"
                    <?php echo ($curso['id'] == $aluno['curso_id']) ? 'selected' : ''; ?>>
                    <?php echo $curso['nome']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </div>
</form>

<?php
echo $OUTPUT->footer();