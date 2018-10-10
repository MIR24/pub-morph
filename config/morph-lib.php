<?php

return [
    'regex' => [
        'removeBrackets' => '#\{(\s+|)((<|&lt;).*?(>|&gt;))(\s+|)\}#s',
        'preg_match_number' => '2',
    ],
    'incut' => [
        'tag' => 'div',
        'attr' => 'data-mir-incut-id',
        'inactive' => [
            'attr' => 'style',
            'attrContent' => 'display:none;',
            'msg' => 'НЕАКТИВНАЯ ВРЕЗКА ЗАГОЛОВОК: '
        ]
    ],
];
