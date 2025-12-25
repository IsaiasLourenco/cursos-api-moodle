<?php

require('../../config.php');
require_login();
/** @var \context $context */
$context = \context_system::instance();
require_capability('local/api_escola:edit', $context);

$id = required_param('id', PARAM_INT);

$PAGE->set_url(new moodle_url('/local/api_escola/delete_aluno.php', ['id' => $id]));
$PAGE->set_context($context);
$PAGE->set_title('Excluir Aluno');
$PAGE->set_heading('Excluir Aluno');

echo $OUTPUT->header();

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

$nomeAluno = $aluno['nome'];

// =============================
// 2. Se o usuário confirmou
// =============================
if (optional_param('confirm', 0, PARAM_BOOL)) {

    $deleteEndpoint = "http://localhost/api-escola/delete_aluno.php?id={$id}";
    $deleteResponse = file_get_contents($deleteEndpoint);

    if ($deleteResponse === false) {
        throw new moodle_exception('Erro ao excluir aluno na API externa.');
    }

    $result = json_decode($deleteResponse, true);

    if (isset($result['error'])) {
        throw new moodle_exception('Erro ao excluir aluno: ' . $result['error']);
    }

    redirect(
        new moodle_url('/local/api_escola/alunos.php'),
        'Aluno excluído com sucesso!',
        2
    );
}

// =============================
// 3. Tela de confirmação
// =============================
$yesurl = new moodle_url('/local/api_escola/delete_aluno.php', ['id' => $id, 'confirm' => 1]);
$cancelurl = new moodle_url('/local/api_escola/alunos.php');

echo '<div class="text-center">';
echo html_writer::tag('p', "Tem certeza que deseja excluir o aluno <strong>{$nomeAluno}</strong>?");
echo '<div>';

echo html_writer::link($yesurl, 'Continuar', [
    'class' => 'btn btn-danger',
    'style' => 'margin-right:10px;'
]);

echo html_writer::link($cancelurl, 'Cancelar', [
    'class' => 'btn btn-secondary'
]);

echo '</div>';
echo '</div>';

echo $OUTPUT->footer();