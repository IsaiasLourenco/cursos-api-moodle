<?php

require('../../config.php');
require_login();
/** @var \context $context */
$context = \context_system::instance();
require_capability('local/api_escola:edit', $context);

$id = required_param('id', PARAM_INT);

$PAGE->set_url(new moodle_url('/local/api_escola/delete_curso.php', ['id' => $id]));
$PAGE->set_context($context);
$PAGE->set_title('Excluir Curso');
$PAGE->set_heading('Excluir Curso');

echo $OUTPUT->header();

// Se o usuário confirmou a exclusão
if (optional_param('confirm', 0, PARAM_BOOL)) {

    $endpoint = "http://localhost/api-escola/delete_curso.php?id={$id}";
    $response = file_get_contents($endpoint);

    if ($response === false) {
        throw new moodle_exception('Erro ao acessar API externa.');
    }

    $result = json_decode($response, true);

    if (isset($result['error'])) {
        throw new moodle_exception('Erro ao excluir curso: ' . $result['error']);
    }

    redirect(
        new moodle_url('/local/api_escola/index.php'),
        'Curso excluído com sucesso!',
        2
    );
}

// Buscar nome do curso na API externa
$endpoint = "http://localhost/api-escola/get.php?id={$id}";
$response = file_get_contents($endpoint);

if ($response === false) {
    throw new moodle_exception('Erro ao acessar API externa.');
}

$curso = json_decode($response, true);

if (isset($curso['error'])) {
    throw new moodle_exception('Curso não encontrado na API externa.');
}

$nomeCurso = $curso['nome'];

// Caso contrário, mostrar a confirmação
$yesurl = new moodle_url('/local/api_escola/delete_curso.php', ['id' => $id, 'confirm' => 1]);
$cancelurl = new moodle_url('/local/api_escola/index.php');

echo '<div class="text-center">';
echo html_writer::tag('p', "Tem certeza que deseja excluir o curso <strong>{$nomeCurso}</strong>?");
echo html_writer::start_tag('div');

echo html_writer::link($yesurl, 'Continuar', ['class' => 'btn btn-danger', 'style' => 'margin-right:10px;']);
echo html_writer::link($cancelurl, 'Cancelar', ['class' => 'btn btn-primary']);

echo html_writer::end_tag('div');
echo '</div>';

echo $OUTPUT->footer();
