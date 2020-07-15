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
            'msg' => 'НЕАКТИВНАЯ ВРЕЗКА'
        ],
        'delete' => [
            'msg' => 'УДАЛЕНА ВРЕЗКА'
        ],
        'noTemplate' => [
            'msg' => 'ТЕМПЛЕЙТ ВРЕЗКИ НЕ СУЩЕСТВУЕТ'
        ],
        'uniquePrefix' => 'uniq-mir24-incut-',
    ],
    'photoIncut' => [
        'tag' => 'div',
        'class' => 'own-photos-incuts',
        'attr' => 'data-index',
    ],
    'incutTemplate' => [
        'regex_extract_pattern' => '#({(.*)})#',
        'dataAttrName' => 'data-attribute-replace',
        'dataAttrPatternName' => 'data-attribute-replace-pattern',
    ],
    'ingrid' => [
        'strlen-pass' => 1200,
        'after-chars-article' => 1200,
        'after-chars-news' => 850,
        'after-p-num' => 1,
    ],
    'amp' => [
        'regex_match_brackets' => '#({.*?})#',
        'remove_by_tag_name' => [
            'style',
            'script',
            'base',
            'img',
            'video',
            'audio',
            'iframe',
            'frame',
            'frameset',
            'object',
            'param',
            'applet',
            'embed',
            'form',
            'embeddedobject',
            'spot',
            'itemspot',
        ],
        'remove_tag_by_name' => [
            'justify',
            'commentfortimingandtpe',
            'commentfortimingandtps',
        ],
        'remove_attr_by_name' => [
            'style',
            'onclick',
            'onmouseover',
        ],
        'add_header_scripts_by_tag_name' => [
            'amp-lightbox' => '<script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>',
        ],
        'blocks' => [
            [
                'type' => 'iframe',
                'regex_match_src' => '#www.youtube.com/embed/(.*)#',
                'exit_tag' => '<amp-youtube
                                    width="870"
                                    height="489"
                                    layout="responsive"
                                    data-videoid="{MATCH_ONE}">
                                </amp-youtube>',
                'header_include' => '<script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>',
                'header_include_match' => 'amp-youtube',
            ],
            [
                'type' => 'blockquote',
                'class' => 'instagram-media',
                'regex_match_href' => '#www.instagram.com/p/(.*)/#',
                'exit_tag' => '<amp-instagram
                                    width="870"
                                    height="489"
                                    data-captioned
                                    layout="responsive"
                                    data-shortcode="{MATCH_ONE}">
                                </amp-instagram>',
                'header_include' => '<script async custom-element="amp-instagram" src="https://cdn.ampproject.org/v0/amp-instagram-0.1.js"></script>',
                'header_include_match' => 'amp-instagram',
            ],
            [
                'type' => 'blockquote',
                'class' => 'twitter-tweet',
                'regex_match_href' => '#twitter.com/.*?/status/(.*)\?#',
                'exit_tag' => '<amp-twitter
                                    width="870"
                                    height="489"
                                    layout="responsive"
                                    data-tweetid="{MATCH_ONE}">
                                </amp-twitter>',
                'header_include' => '<script async custom-element="amp-twitter" src="https://cdn.ampproject.org/v0/amp-twitter-0.1.js"></script>',
                'header_include_match' => 'amp-twitter',
            ],
            [
                'type' => 'div',
                'class' => 'pb_feed',
                'div_attribute' => 'data-item',
                'exit_tag' => '<amp-playbuzz
                                    height="489"
                                    data-item="{MATCH_ONE}"
                                    data-item-info="true"
                                    data-share-buttons="true"
                                    data-comments="true">
                                </amp-playbuzz>',
                'header_include' => '<script async custom-element="amp-playbuzz" src="https://cdn.ampproject.org/v0/amp-playbuzz-0.1.js"></script>',
                'header_include_match' => 'amp-playbuzz',
            ],
            [
                'type' => 'div',
                'class' => 'playbuzz',
                'div_attribute' => 'data-id',
                'exit_tag' => '<amp-playbuzz
                                    height="489"
                                    data-item="{MATCH_ONE}"
                                    data-item-info="true"
                                    data-share-buttons="true"
                                    data-comments="true">
                                </amp-playbuzz>',
                'header_include' => '<script async custom-element="amp-playbuzz" src="https://cdn.ampproject.org/v0/amp-playbuzz-0.1.js"></script>',
                'header_include_match' => 'amp-playbuzz',
            ],
            [
                'type' => 'div',
                'class' => 'apester-media',
                'div_attribute' => 'data-media-id',
                'exit_tag' => '<amp-apester-media
                                    height="489"
                                    data-apester-media-id="{MATCH_ONE}">
                                </amp-apester-media>',
                'header_include' => '<script async custom-element="amp-apester-media" src="https://cdn.ampproject.org/v0/amp-apester-media-0.1.js"></script>',
                'header_include_match' => 'amp-apester-media',
            ],
        ],
    ],
    'image' => [
        'figure-class' => 'img-figure',
        'attrImageIdName' => 'data-attribute-mir24-image-id',
        'attrImageCaptionName' => 'data-attribute-mir24-image-caption',
        'regex-img-style-keep' => '#(height|boarder).+?;#',
        'amp' => [
            'type' => 'img',
            'exit_tag' => '<amp-img
                                layout="responsive"
                                height="{height}"
                                width="{width}"
                                src="{src}">
                            </amp-img>',
            'style' => [
                'height' => '#height:(.*?)([;|,|\s]|$)#',
                'width' => '#width:(.*?)([;|,|\s]|$)#',
            ],
            'replace' => [
                'height' => '{height}',
                'width' => '{width}',
                'src' => '{src}',
            ],
            'default' => [
                'height' => '1',
                'width' => '1.5',
            ],
        ],
        'processByAttribute' => 'data-attribute-image-process',
    ],
];
?>