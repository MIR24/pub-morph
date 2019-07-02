<?php

return [
    'decoded' => false,
    'emptyBrackets' => '{}',
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
    ],
    'ingrid' => [
        'strlen-pass' => 1200,
        'after-chars-article' => 1200,
        'after-chars-news' => 850,
        'after-p-num' => 1,
    ],
];
?>