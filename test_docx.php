<?php
$filePath = __DIR__ . '/public/Contoh_Template_SKTM.docx';

$zip = new ZipArchive;
if ($zip->open($filePath) === TRUE) {
    $xml = $zip->getFromName('word/document.xml');
    $zip->close();
    
    // strip tags to just get text
    $text = strip_tags($xml);
    
    preg_match_all('/\$\{([a-zA-Z0-9_]+)\}/', $text, $matches);
    
    $variables = array_unique($matches[1]);
    
    $standard = ['nama', 'nik', 'jenis_kelamin', 'tanggal_lahir', 'agama', 'pekerjaan', 'alamat', 'telepon', 'tanggal_pengajuan'];
    
    $custom = array_diff($variables, $standard);
    
    echo "All Variables: \n";
    print_r($variables);
    
    echo "\nCustom Variables: \n";
    print_r(array_values($custom));
    
} else {
    echo "Failed to open zip\n";
}
