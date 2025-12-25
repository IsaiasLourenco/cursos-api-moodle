<?php

require('../../config.php');
require_login();
/** @var \context $context */
$context = \context_system::instance();
require_capability('local/api_escola:edit', $context);

$id = required_param('id', PARAM_INT);

// =============================
// 1. Buscar dados do curso na API externa
// =============================
$endpoint = "http://localhost/api-escola/get.php?id={$id}";
$response = file_get_contents($endpoint);

if ($response === false) {
    throw new moodle_exception('Erro ao acessar API externa.');
}

$curso = json_decode($response, true);

if (isset($curso['error'])) {
    throw new moodle_exception('Curso não encontrado na API externa.');
}

// =============================
// 2. Configuração da página
// =============================
$PAGE->set_url(new moodle_url('/local/api_escola/edit_curso.php', ['id' => $id]));
$PAGE->set_context($context);
$PAGE->set_title('Editar Curso');
$PAGE->set_heading('Editar Curso');

echo $OUTPUT->header();

// Título centralizado
echo html_writer::tag('h4', 'Página para edição do curso', ['class' => 'text-center']);
echo '<br>';

// Botão voltar
$backurl = new moodle_url('/local/api_escola/index.php');

echo '<div class="text-center">';
    echo html_writer::link($backurl, 'Voltar para Cursos', ['class' => 'btn btn-secondary']);
    echo '<br><br>';
echo '</div>';

// =============================
// 3. Se o formulário foi enviado
// =============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'id' => $id,
        'nome' => required_param('nome', PARAM_TEXT),
        'descricao' => required_param('descricao', PARAM_TEXT),
        'carga_horaria' => required_param('carga_horaria', PARAM_INT)
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
    $result = file_get_contents('http://localhost/api-escola/update_curso.php', false, $contexto);

    if ($result === false) {
        echo html_writer::tag('p', 'Erro ao atualizar curso na API.', ['style' => 'color:red;']);
    } else {
        redirect(new moodle_url('/local/api_escola/index.php'), 'Curso atualizado com sucesso!', 2);
    }
}

// =============================
// 4. Formulário de edição
// =============================
?>

<form method="POST">
    <div class="text-center">
        <label>Nome:</label><br>
        <input type="text" name="nome" value="<?php echo $curso['nome']; ?>" required><br><br>

        <label>Descrição:</label><br>
        <textarea name="descricao" required><?php echo $curso['descricao']; ?></textarea><br><br>

        <label>Carga Horária:</label><br>
        <input type="number" name="carga_horaria" value="<?php echo $curso['carga_horaria']; ?>" required><br><br>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </div>
</form>

<?php
echo $OUTPUT->footer();
