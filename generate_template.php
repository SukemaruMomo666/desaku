<?php
require __DIR__ . '/vendor/autoload.php';

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();

$fontStyleName = 'rStyle';
$phpWord->addFontStyle($fontStyleName, array('name' => 'Arial', 'size' => 12));
$boldFontStyleName = 'bStyle';
$phpWord->addFontStyle($boldFontStyleName, array('name' => 'Arial', 'size' => 12, 'bold' => true));
$italicFontStyleName = 'iStyle';
$phpWord->addFontStyle($italicFontStyleName, array('name' => 'Arial', 'size' => 12, 'italic' => true));

$paragraphStyleName = 'pStyle';
$phpWord->addParagraphStyle($paragraphStyleName, array('align' => 'center', 'spaceAfter' => 100));

// TITLE
$section->addText('SURAT KETERANGAN', array('name' => 'Arial', 'size' => 14, 'bold' => true, 'underline' => 'single'), $paragraphStyleName);
$section->addText('Nomor : ${nomor_surat}', $fontStyleName, $paragraphStyleName);
$section->addTextBreak(1);

// BODY
$section->addText('Yang bertanda tangan dibawah ini Lurah Sukapada Kecamatan Cibeunying Kidul Kota Bandung dengan ini menerangkan bahwa:', $fontStyleName);
$section->addTextBreak(1);

$table = $section->addTable(array('cellMargin' => 50));
$table->addRow();
$table->addCell(3000)->addText('Nama', $fontStyleName);
$table->addCell(500)->addText(':', $fontStyleName);
$table->addCell(6000)->addText('${nama}', $boldFontStyleName); // Example of Bold

$table->addRow();
$table->addCell(3000)->addText('No. KTP/NIK', $fontStyleName);
$table->addCell(500)->addText(':', $fontStyleName);
$table->addCell(6000)->addText('${nik}', $fontStyleName);

$table->addRow();
$table->addCell(3000)->addText('Tempat/Tanggal Lahir', $fontStyleName);
$table->addCell(500)->addText(':', $fontStyleName);
$table->addCell(6000)->addText('${tempat_lahir}, ${tanggal_lahir}', $fontStyleName);

$table->addRow();
$table->addCell(3000)->addText('Jenis Kelamin', $fontStyleName);
$table->addCell(500)->addText(':', $fontStyleName);
$table->addCell(6000)->addText('${jenis_kelamin}', $fontStyleName);

$table->addRow();
$table->addCell(3000)->addText('Status Perkawinan', $fontStyleName);
$table->addCell(500)->addText(':', $fontStyleName);
$table->addCell(6000)->addText('${status_perkawinan}', $fontStyleName);

$table->addRow();
$table->addCell(3000)->addText('Agama', $fontStyleName);
$table->addCell(500)->addText(':', $fontStyleName);
$table->addCell(6000)->addText('${agama}', $fontStyleName);

$table->addRow();
$table->addCell(3000)->addText('Pekerjaan', $fontStyleName);
$table->addCell(500)->addText(':', $fontStyleName);
$table->addCell(6000)->addText('${pekerjaan}', $fontStyleName);

$table->addRow();
$table->addCell(3000)->addText('Alamat', $fontStyleName);
$table->addCell(500)->addText(':', $fontStyleName);
$table->addCell(6000)->addText('${alamat_lengkap}', $fontStyleName);

$section->addTextBreak(1);
$section->addText('Berdasarkan pengajuan yang bersangkutan dan data yang ada pada kami, bahwa warga tersebut benar adanya adalah warga kami dan saat ini ${keterangan_tambahan}.', $fontStyleName, array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH));
$section->addTextBreak(1);

$textRun = $section->addTextRun(array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH));
$textRun->addText('Demikian surat keterangan ini kami buat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya. (Contoh jika ingin miring: ', $fontStyleName);
$textRun->addText('${contoh_miring}', $italicFontStyleName);
$textRun->addText(').', $fontStyleName);

$section->addTextBreak(2);

// SIGNATURES
$tableSig = $section->addTable(array('width' => 100 * 50, 'unit' => 'pct'));
$tableSig->addRow();
$tableSig->addCell(5000)->addText('Pemohon,', array('name' => 'Arial', 'size' => 12), array('align' => 'center'));
$tableSig->addCell(5000)->addText('Bandung, ${tanggal_pengajuan}', array('name' => 'Arial', 'size' => 12), array('align' => 'center'));

$tableSig->addRow();
$tableSig->addCell(5000)->addText('', array('name' => 'Arial', 'size' => 12), array('align' => 'center'));
$tableSig->addCell(5000)->addText('${ttd_jabatan} Sukapada', array('name' => 'Arial', 'size' => 12), array('align' => 'center'));

$tableSig->addRow(2000);
$tableSig->addCell(5000)->addText('', array('name' => 'Arial', 'size' => 12), array('align' => 'center'));
$tableSig->addCell(5000)->addText('', array('name' => 'Arial', 'size' => 12), array('align' => 'center'));

$tableSig->addRow();
$tableSig->addCell(5000)->addText('${nama}', array('name' => 'Arial', 'size' => 12, 'bold' => true), array('align' => 'center'));
$tableSig->addCell(5000)->addText('${ttd_nama}', array('name' => 'Arial', 'size' => 12, 'bold' => true), array('align' => 'center'));

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save(__DIR__ . '/public/Template_Contoh_Variabel.docx');

echo "Done\n";
?>
