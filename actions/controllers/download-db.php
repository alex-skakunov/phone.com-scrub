<?php


/* same as query in scrubbing.php, but 'number' instead of 'COUNT(*) */
/*
$sqlTemplate = 'SELECT number
        FROM phones
        INTO OUTFILE "%s"';
$sql = sprintf($sqlTemplate, $fullname);
*/

$path = TEMP_DIR . '/';
$filename = 'export-' . date('Y-m-d-H-i-s-') . rand(1, 1000) . '.csv';
$fullname = $path . $filename;
$i = 0;
while(true) {
    $sql = 'SELECT number FROM phones LIMIT ' . $i . ', ' . DUMP_LINES_LIMIT;
    $stmt = query($sql);
    $recordset = $stmt->fetchAll();
    if (empty($recordset)) {
        break;
    }
    $data = array();
    foreach ($recordset as $row) {
        $data[] = $row['number'];
    }
    file_put_contents($fullname, implode(chr(10), $data) . chr(10), FILE_APPEND);
    $i += DUMP_LINES_LIMIT;
}

$zip = new ZipArchive;
if(true === ($zip->open($fullname . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE))){
    $zip->addFile($fullname, $filename);
    $zip->close();
    unlink($fullname); //remove old CSV
    $filename .= '.zip';
    $fullname .= '.zip';
    header('Content-Type: application/zip');
}

header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($fullname));

ob_clean();
flush();
readfile($fullname);
unlink($fullname);
exit;