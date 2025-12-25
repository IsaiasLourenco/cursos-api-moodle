<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = [

    // Permissão para visualizar cursos/alunos
    'local/api_escola:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'manager' => CAP_ALLOW
        ]
    ],

    // Permissão para editar cursos/alunos
    'local/api_escola:edit' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'manager' => CAP_ALLOW
        ]
    ]
];