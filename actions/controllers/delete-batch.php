<?php

header('Content-Type: application/json');

if (empty($_GET['batch_id'])) {
    exit(json_encode(null));
}

$batchId = (int)$_GET['batch_id'];

try {
    query('DELETE FROM batch WHERE id = ' . $batchId);
    exec('rm -rf ' . ARCHIVE_DIR . $batchId);
    exit(json_encode(array('status' => 'success', 'batch_id' => $batchId)));
}
catch(Exception $e) {
    exit(json_encode(array('status' => 'fail', 'batch_id' => $batchId)));
}
