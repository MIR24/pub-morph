<?php

return [
    'decoded' => false,
    'regex' => [
        'removeBrackets' => '#\{(\s+|)((<|&lt;).*?(>|&gt;))(\s+|)\}#s',
        'preg_match_number' => '2',
    ],
    'incut' => [
        'tag' => 'div',
        'inactive' => [
            'attr' => 'style',
            'attrContent' => 'display:none;',
            'msg' => 'НЕАКТИВНАЯ ВРЕЗКА ЗАГОЛОВОК: '
        ],
        'delete' => [
            'msg' => 'УДАЛЕНА ВРЕЗКА'
        ]
    ]
];
?>