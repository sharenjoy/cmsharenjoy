<?php

return array(

    'driver'           => 'file',
    
    'path'             => '/uploads',
    'thumbPath'        => '/uploads/thumbs',
    'cache_path'       => '/cloud_cache',

    'encrypt_filename' => true,

    'allowed_file_ext' => array(
        'a'	=> array('mpga', 'mp2', 'mp3', 'ra', 'rv', 'wav'),
        'v'	=> array('mpeg', 'mpg', 'mpe', 'mp4', 'flv', 'qt', 'mov', 'avi', 'movie'),
        'd'	=> array('pdf', 'xls', 'ppt', 'pptx', 'txt', 'text', 'log', 'rtx', 'rtf', 'xml', 'xsl', 'doc', 'docx', 'xlsx', 'word', 'xl', 'csv', 'pages', 'numbers'),
        'i'	=> array('bmp', 'gif', 'jpeg', 'jpg', 'jpe', 'png', 'tiff', 'tif'),
        'o'	=> array('psd', 'gtar', 'swf', 'tar', 'tgz', 'xhtml', 'zip', 'rar', 'css', 'html', 'htm', 'shtml', 'svg'),
    ),

);