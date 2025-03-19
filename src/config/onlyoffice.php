<?php

return [
    'jwt_secret' => env('JWT_SECRET', null),

    'onlyoffice_host' => null, // http://onlyoffice

    'docExtensions' => [
        'doc', 'docm', 'docx', 'dot', 'dotm', 'dotx', 'epub', 'fb2', 'fodt', 'htm', 'html', 'hwp', 'hwpx', 'mht', 'mhtml',
        'odt', 'ott', 'pages', 'rtf', 'stw', 'swx', 'txt', 'wps', 'wpt', 'xml'
    ],

    'cellExtensions' => [
        'csv', 'et', 'ett', 'fods', 'numbers', 'ods', 'ots', 'sxc', 'x;s', 'xlsb', 'xlsm', 'xlsx', 'xlt', 'xltm', 'xltx'
    ],

    'slideExtensions' => [
        'dps', 'dpt', 'fodp', 'key', 'odp', 'otp', 'pot', 'potm', 'potx', 'pps', 'ppsm', 'ppsx', 'ptt', 'pttm', 'pttx', 'sxi'
    ],

    'pdfExtensions' => [
        'djvu', 'docxf', 'oform', 'oxps', 'pdf', 'xps'
    ],
];
