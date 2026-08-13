<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

$phpWord = new PhpWord();
$section = $phpWord->addSection();

// KOP SURAT
$headerStyle = ['bold' => true, 'size' => 14];
$subHeaderStyle = ['bold' => true, 'size' => 12];
$center = ['alignment' => Jc::CENTER];

$section->addText('PEMERINTAH KABUPATEN CONTOH', $headerStyle, $center);
$section->addText('KECAMATAN CONTOH', $headerStyle, $center);
$section->addText('KANTOR KEPALA DESA MAJU JAYA', $headerStyle, $center);
$section->addText('Jl. Raya Pembangunan No. 123, Desa Maju Jaya, Kode Pos 45678', ['size' => 10], $center);
$section->addText('========================================================================================', [], $center);

$section->addTextBreak(1);

// JUDUL SURAT
$section->addText('SURAT KETERANGAN TIDAK MAMPU (SKTM)', ['bold' => true, 'size' => 12, 'underline' => 'single'], $center);
$section->addText('Nomor: 400 /      / Kades / 2024', [], $center);

$section->addTextBreak(1);

// ISI SURAT
$section->addText('Yang bertanda tangan di bawah ini Kepala Desa Maju Jaya, Kecamatan Contoh, menerangkan dengan sebenarnya bahwa:');

$section->addTextBreak(1);

$fontStyle = ['size' => 11];

// TABLE FOR DATA
$tableStyle = ['borderSize' => 0, 'borderColor' => 'FFFFFF', 'cellMargin' => 50];
$phpWord->addTableStyle('User Table', $tableStyle);
$table = $section->addTable('User Table');

$table->addRow();
$table->addCell(3000)->addText('Nama Lengkap', $fontStyle);
$table->addCell(200)->addText(':', $fontStyle);
$table->addCell(6000)->addText('${nama}', ['bold' => true, 'size' => 11]);

$table->addRow();
$table->addCell(3000)->addText('NIK', $fontStyle);
$table->addCell(200)->addText(':', $fontStyle);
$table->addCell(6000)->addText('${nik}', $fontStyle);

$table->addRow();
$table->addCell(3000)->addText('Jenis Kelamin', $fontStyle);
$table->addCell(200)->addText(':', $fontStyle);
$table->addCell(6000)->addText('${jenis_kelamin}', $fontStyle);

$table->addRow();
$table->addCell(3000)->addText('Tanggal Lahir', $fontStyle);
$table->addCell(200)->addText(':', $fontStyle);
$table->addCell(6000)->addText('${tanggal_lahir}', $fontStyle);

$table->addRow();
$table->addCell(3000)->addText('Agama', $fontStyle);
$table->addCell(200)->addText(':', $fontStyle);
$table->addCell(6000)->addText('${agama}', $fontStyle);

$table->addRow();
$table->addCell(3000)->addText('Pekerjaan', $fontStyle);
$table->addCell(200)->addText(':', $fontStyle);
$table->addCell(6000)->addText('${pekerjaan}', $fontStyle);

$table->addRow();
$table->addCell(3000)->addText('Alamat', $fontStyle);
$table->addCell(200)->addText(':', $fontStyle);
$table->addCell(6000)->addText('${alamat}', $fontStyle);

// CUSTOM FIELD
$table->addRow();
$table->addCell(3000)->addText('Keperluan', $fontStyle);
$table->addCell(200)->addText(':', $fontStyle);
$table->addCell(6000)->addText('${keperluan}', ['italic' => true]);

$section->addTextBreak(1);

$section->addText('Orang tersebut di atas adalah benar-benar warga Desa Maju Jaya yang berdomisili di alamat tersebut. Berdasarkan pengamatan kami dan data yang ada, yang bersangkutan termasuk dalam keluarga yang kurang mampu / prasejahtera.', $fontStyle);
$section->addText('Surat keterangan ini dibuat untuk keperluan: ', $fontStyle);
$section->addText('${keperluan}', ['bold' => true, 'size' => 11], $center);

$section->addTextBreak(1);
$section->addText('Demikian surat keterangan ini dibuat agar dapat dipergunakan sebagaimana mestinya.', $fontStyle);

$section->addTextBreak(2);

// TTD TABLE
$ttdTable = $section->addTable('User Table');
$ttdTable->addRow();
$ttdTable->addCell(5000)->addText('', $fontStyle); // Empty left cell
$ttdTable->addCell(4000)->addText('Maju Jaya, ${tanggal_pengajuan}', $fontStyle, ['alignment' => Jc::CENTER]);
$ttdTable->addRow();
$ttdTable->addCell(5000)->addText('', $fontStyle);
$ttdTable->addCell(4000)->addText('Kepala Desa Maju Jaya,', $fontStyle, ['alignment' => Jc::CENTER]);

$ttdTable->addRow();
$ttdTable->addCell(5000)->addTextBreak(3);
$ttdTable->addCell(4000)->addTextBreak(3);

$ttdTable->addRow();
$ttdTable->addCell(5000)->addText('', $fontStyle);
$ttdTable->addCell(4000)->addText('Budi Santoso', ['bold' => true, 'underline' => 'single', 'size' => 11], ['alignment' => Jc::CENTER]);


$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save(__DIR__ . '/public/Contoh_Template_SKTM.docx');

echo "Template successfully created at public/Contoh_Template_SKTM.docx\n";
