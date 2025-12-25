<?php

require('../../config.php');
require_login();

/** @var \context $context */
$context = \context_system::instance();

require_capability('local/api_escola:view', $context);

$PAGE->set_url(new moodle_url('/local/api-escola/index.php'));
$PAGE->set_context($context);
$PAGE->set_title('API Escola');
$PAGE->set_heading('Integração com API Escola');

echo $OUTPUT->header();

// Título centralizado
echo html_writer::tag('h4', 'Cursos vindos da API Escola', ['class' => 'text-center']);
echo '<br>';

echo '<div class="text-center">';
    $addurl = new moodle_url('/local/api_escola/add_curso.php');
    echo html_writer::link($addurl, 'Adicionar Curso', ['class' => 'btn btn-primary']);
    echo '<br><br>';

    $alunosurl = new moodle_url('/local/api_escola/alunos.php');
    echo html_writer::link($alunosurl, 'Listar Alunos', ['class' => 'btn btn-secondary']);
    echo '<br><br>';
echo '</div>';
// =============================
// 1. Consumindo a API externa
// =============================
$endpoint = 'http://localhost/api-escola/cursos.json';

$response = file_get_contents($endpoint);

if ($response === false) {
    echo html_writer::tag('p', 'Erro ao acessar a API.', ['style' => 'color:red;']);
    echo $OUTPUT->footer();
    exit;
}

$cursos = json_decode($response, true);

// =============================
// 2. Exibindo os cursos
// =============================
if (!empty($cursos)) {
    $table = new html_table();
    $table->head = ['ID', 'Nome', 'Descrição', 'Carga Horária', 'Editar', 'Excluir'];

    foreach ($cursos as $curso) {
        $editurl = new moodle_url('/local/api_escola/edit_curso.php', ['id' => $curso['id']]);
        $deleteurl = new moodle_url('/local/api_escola/delete_curso.php', ['id' => $curso['id']]);

        $table->data[] = [
            str_pad($curso['id'], 3, '0', STR_PAD_LEFT),
            $curso['nome'],
            $curso['descricao'],
            $curso['carga_horaria'],
            html_writer::link($editurl, 'Editar', ['class' => 'btn btn-primary']),
            html_writer::link($deleteurl, 'Excluir', ['class' => 'btn btn-danger'])
        ];
    }

    echo html_writer::table($table);
} else {
    echo html_writer::tag('p', 'Nenhum curso encontrado.');
}

echo $OUTPUT->footer();
