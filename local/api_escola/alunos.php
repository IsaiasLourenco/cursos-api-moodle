<?php

require('../../config.php');
require_login();
/** @var \context $context */
$context = \context_system::instance();
require_capability('local/api_escola:view', $context);

$PAGE->set_url(new moodle_url('/local/api_escola/alunos.php'));
$PAGE->set_context($context);
$PAGE->set_title('API Escola - Alunos');
$PAGE->set_heading('Lista de Alunos da API Escola');

echo $OUTPUT->header();

// Título centralizado
echo html_writer::tag('h4', 'Alunos vindos da API Escola', ['class' => 'text-center']);
echo '<br>';

echo '<div class="text-center">';
    $addurl = new moodle_url('/local/api_escola/add_aluno.php');
    echo html_writer::link($addurl, 'Adicionar Aluno', ['class' => 'btn btn-primary']);
    echo '<br><br>';

    $cursosurl = new moodle_url('/local/api_escola/index.php');
    echo html_writer::link($cursosurl, 'Voltar para Cursos', ['class' => 'btn btn-secondary']);
    echo '<br><br>';
echo '</div>';

// =============================
// 1. Consumindo a API externa
// =============================
$endpoint = 'http://localhost/api-escola/alunos.json';

$response = file_get_contents($endpoint);

if ($response === false) {
    echo html_writer::tag('p', 'Erro ao acessar a API.', ['style' => 'color:red;']);
    echo $OUTPUT->footer();
    exit;
}

$alunos = json_decode($response, true);

$cursos_json = file_get_contents('http://localhost/api-escola/cursos.json');
$cursos = json_decode($cursos_json, true);

// cria um mapa: [id_do_curso => nome_do_curso]
$mapaCursos = [];
foreach ($cursos as $curso) {
    $mapaCursos[$curso['id']] = $curso['nome'];
}

// =============================
// 2. Exibindo os alunos
// =============================
if (!empty($alunos)) {
    $table = new html_table();
    $table->head = ['ID', 'Nome', 'Email', 'Curso', 'Editar', 'Excluir'];

    foreach ($alunos as $aluno) {

        $editurl = new moodle_url('/local/api_escola/edit_aluno.php', ['id' => $aluno['id']]);
        $deleteurl = new moodle_url('/local/api_escola/delete_aluno.php', ['id' => $aluno['id']]);

        $table->data[] = [
            str_pad($aluno['id'], 3, '0', STR_PAD_LEFT),
            $aluno['nome'],
            $aluno['email'],
            $mapaCursos[$aluno['curso_id']] ?? 'Curso não encontrado',
            html_writer::link($editurl, 'Editar', ['class' => 'btn btn-primary']),
            html_writer::link($deleteurl, 'Excluir', ['class' => 'btn btn-danger'])
        ];
    }

    echo html_writer::table($table);
} else {
    echo html_writer::tag('p', 'Nenhum aluno encontrado.');
}

echo $OUTPUT->footer();