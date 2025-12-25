<?php

require('../../config.php');
require_login();
/** @var \context $context */
$context = \context_system::instance();
require_capability('local/api_escola:edit', $context);

$PAGE->set_url(new moodle_url('/local/api_escola/add_curso.php'));
$PAGE->set_context($context);
$PAGE->set_title('Adicionar Curso');
$PAGE->set_heading('Adicionar Curso');

echo $OUTPUT->header();

// Botão voltar
$backurl = new moodle_url('/local/api_escola/index.php');

echo '<div class="text-center">';
echo html_writer::link($backurl, 'Voltar para Cursos', ['class' => 'btn btn-secondary']);
echo '<br><br>';
echo '</div>';

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
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
    $result = file_get_contents('http://localhost/api-escola/add_curso.php', false, $contexto);

    if ($result === false) {
        echo html_writer::tag('p', 'Erro ao adicionar curso na API.', ['style' => 'color:red;']);
    } else {
        redirect(new moodle_url('/local/api_escola/index.php'), 'Curso adicionado com sucesso!', 2);
    }
}

?>

<form method="POST">
    <div class="text-center">

        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Descrição:</label><br>
        <textarea name="descricao" required></textarea><br><br>

        <label>Carga Horária:</label><br>
        <input type="number" name="carga_horaria" required><br><br>

        <button type="submit" class="btn btn-primary">Adicionar Curso</button>
    </div>
</form>

<?php
echo $OUTPUT->footer();