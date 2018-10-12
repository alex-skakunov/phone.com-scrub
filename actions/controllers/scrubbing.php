<?php
set_time_limit(0);

if(empty($_POST)) {
  return;
}

$errorCode = $_FILES['file_source']['error'];

//file was uploaded successfully
if(!(is_uploaded_file($_FILES['file_source']['tmp_name']) && UPLOAD_ERR_OK == $errorCode )) {
  $error = coalesce( $uploadErrors[$errorCode], 'General upload error. Check <a href="http://php.net/manual/en/features.file-upload.php">file uploads settings</a> of your php.ini' );
  return;
}

$filename = $_FILES['file_source']['name'];
$temp_file = $_FILES['file_source']['tmp_name'];
$our_file  = TEMP_DIR . basename($temp_file);
if ( !move_uploaded_file( $temp_file, $our_file ) ) //copy to our folder
{
  $error = 'Could not copy [' . $temp_file .'] to [' . $our_file . ']';
  return;
}

$zip = new ZipArchive;
if ($zip->open($our_file) === TRUE) {
    $csvFilename = $zip->getNameIndex(0);
    $zip->extractTo(TEMP_DIR, array($csvFilename));
    $zip->close();
    unlink($our_file); //remove zip
    $our_file = TEMP_DIR . $csvFilename;

}
//query('SET CHARACTER SET "latin1"');
//query('SET collation_connection = "utf8_general_ci"');

$fQuickCSV = new Quick_CSV_import($db);

$fQuickCSV->make_temporary = true; //wanted to make it temporary (very handy), but the query "remove duplicates within the data" will not run on temporary table :(
$fQuickCSV->file_name = $our_file;
$fQuickCSV->use_csv_header = false;
$fQuickCSV->table_exists = false;
$fQuickCSV->truncate_table = false;
$fQuickCSV->field_separate_char = ',';
$fQuickCSV->encoding = 'utf8';
$fQuickCSV->field_enclose_char = '"';
$fQuickCSV->field_escape_char = '\\';

$fQuickCSV->import();
unlink($our_file);
if(!empty($fQuickCSV->error) )
{
  $error = $fQuickCSV->error;
  return;
}
elseif( 0 == $fQuickCSV->rows_count )
{
  $error = 'Imported rows count is 0.';
  return;
}

$rowsCount = $fQuickCSV->rows_count;

//kill UTF BOM record
query('UPDATE `'.$fQuickCSV->table_name.'` SET `column1`=TRIM(  REPLACE(`column1`, UNHEX("C2A0"), ""))');
query('UPDATE `'.$fQuickCSV->table_name.'` SET `column1`=TRIM(REPLACE(`column1`, UNHEX("A0"), ""))')  ;
query('UPDATE `'.$fQuickCSV->table_name.'` SET `column1`=TRIM(REPLACE(`column1`, UNHEX("EFBBBF"), ""))');
query('UPDATE `'.$fQuickCSV->table_name.'` SET `column1`=TRIM(REPLACE(`column1`, UNHEX("0D"), ""))');

//change column type from TEXT to something that allows making an index on top of it for faster searches
//query('ALTER TABLE `'.$fQuickCSV->table_name.'` CHANGE `column1` `column1` CHAR(20) NOT NULL;');
query('ALTER TABLE `'.$fQuickCSV->table_name.'` ADD INDEX(`column1`);');

//adding a primary key
query('ALTER TABLE `'.$fQuickCSV->table_name.'` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`)');

//remove duplicates within the data
$stmt = query('SELECT COUNT(DISTINCT column1)
       FROM `'.$fQuickCSV->table_name.'`');
$duplicatesWithinNumbers = $rowsCount - (int)$stmt->fetchColumn();

/*
$stmt = query('SELECT COUNT(DISTINCT `temp`.`column1`)
                FROM `'.$fQuickCSV->table_name.'` temp
                LEFT JOIN `phones` ON `temp`.`column1` = `phones`.`number`
                WHERE `phones`.`number` IS NULL');
$uniqueNumbers = (int)$stmt->fetchColumn();

$stmt = query('SELECT COUNT(*)
               FROM `'.$fQuickCSV->table_name.'` temp
               INNER JOIN `phones` ON `temp`.`column1` = `phones`.`number`');
$duplicateNumbers = (int)$stmt->fetchColumn();


$stmt = query('INSERT INTO batch (created_at, filename, number_total, number_unique, number_duplicate) VALUES (NOW(), "'
  . $filename . '", '
  . $rowsCount . ', '
  . $uniqueNumbers . ', '
  . $duplicateNumbers
  . ')');
*/

$stmt = query('INSERT INTO batch (created_at, filename, number_total) VALUES (NOW(), "'
  . $filename . '", '
  . $rowsCount . ')');
$batchId = $db->lastInsertId();

$path = ARCHIVE_DIR . $batchId . '/';
mkdir($path);
chmod($path, 0777);

/*
$sqlTemplate = 'SELECT DISTINCT `temp`.`column1`
                FROM `'.$fQuickCSV->table_name.'` temp
                LEFT JOIN `phones` ON `temp`.`column1` = `phones`.`number`
                WHERE `phones`.`number` IS NULL
                INTO OUTFILE "%s"';
$sql = sprintf($sqlTemplate, $fullname);
*/
$sqlTemplate = 'SELECT DISTINCT `temp`.`column1` AS "number"
        FROM `'.$fQuickCSV->table_name.'` temp
        LEFT JOIN `phones` ON `temp`.`column1` = `phones`.`number`
        WHERE `phones`.`number` IS NULL';
$uniqueNumbers = 0;
$filename = 'unique.csv';
$fullname = $path . $filename;
$i = 0;
while(true) {
    $sql = $sqlTemplate . ' LIMIT ' . $i . ', ' . DUMP_LINES_LIMIT;
    $stmt = query($sql);
    $recordset = $stmt->fetchAll();
    if (empty($recordset)) {
        break;
    }
    $uniqueNumbers += sizeof($recordset);
    $data = array();
    foreach ($recordset as $row) {
        $data[] = $row['number'];
    }
    file_put_contents($fullname, implode(chr(10), $data) . chr(10), FILE_APPEND);
    $i += DUMP_LINES_LIMIT;
}

if ($uniqueNumbers > 0) {
  $zip = new ZipArchive;
  if(true === ($zip->open($fullname . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE))){
      $zip->addFile($fullname, $filename);
      $zip->close();
      unlink($fullname); //remove old CSV
  }
}

query('UPDATE batch SET number_unique = ' 
  . $uniqueNumbers 
  . ' WHERE id = ' . $batchId); 


/*
$sqlTemplate = 'SELECT DISTINCT `temp`.`column1` AS 'number'
                FROM `'.$fQuickCSV->table_name.'` temp
                INNER JOIN `phones` ON `temp`.`column1` = `phones`.`number`
                INTO OUTFILE "%s"';
$sql = sprintf($sqlTemplate, $fullname);
*/

$filename = 'duplicate.csv';
$fullname = $path . $filename;

$duplicateNumbers = 0;
while(true) {
//    $sql = $sqlTemplate . ' LIMIT ' . $i . ', ' . DUMP_LINES_LIMIT;
    $sql = 'SELECT DISTINCT `temp`.`column1` AS "number"
        FROM `'.$fQuickCSV->table_name.'` temp
        INNER JOIN `phones` ON `temp`.`column1` = `phones`.`number`
        LIMIT ' . DUMP_LINES_LIMIT;
    $stmt = query($sql);
    $recordset = $stmt->fetchAll();
    if (empty($recordset)) {
        break;
    }
    $duplicateNumbers += sizeof($recordset);
    $data = array();
    foreach ($recordset as $row) {
        $data[] = $row['number'];
    }
    file_put_contents($fullname, implode(chr(10), $data) . chr(10), FILE_APPEND);
    query('DELETE FROM `'.$fQuickCSV->table_name.'` WHERE `column1` IN("' . implode('","', $data) . '")');
}

if ($duplicateNumbers > 0) {
  $zip = new ZipArchive;
  if(true === ($zip->open($fullname . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE))){
      $zip->addFile($fullname, $filename);
      $zip->close();
      unlink($fullname); //remove old CSV
  }
}

query('UPDATE batch SET number_duplicate = ' 
  . $duplicateNumbers 
  . ' WHERE id = '
  . $batchId);

/*
//delete duplicates
query('DELETE FROM temp
  USING `'.$fQuickCSV->table_name.'` temp
  INNER JOIN phones ON temp.column1 = phones.number');
*/

//copy uniques to the phones database
query('INSERT IGNORE INTO phones
  SELECT ' . $batchId . ', column1
  FROM `'.$fQuickCSV->table_name.'`');
//query('DROP TABLE `'.$fQuickCSV->table_name.'`');
