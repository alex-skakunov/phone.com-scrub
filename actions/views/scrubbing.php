<div style="text-align: center; margin-bottom: 50px;">
  <h1>
    Upload the new file
  </h1>
</div>

<form method="post" enctype="multipart/form-data" onsubmit="$('#submit').attr('disabled', 'disabled'); $('#loader').show();">
   <input type="hidden" name="version" value="1.0" />
   <table border="0" align="center">
    <tr>
      <td>CSV file to upload:</td>
      <td rowspan="30" width="10px">&nbsp;</td>
      <td><input type="file" name="file_source" id="file_source" class="edt" value="<?=$file_source?>" accept=".csv, .txt, .zip, application/zip, text/csv, text/plain" /></td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center">
        <input id="submit" type="Submit" name="Go" value="Upload" class="btn btn-primary" style="padding: 10px 15px" onclick="var s = document.getElementById('file_source'); if(null != s && '' == s.value) {alert('Please pick a file'); s.focus(); return false;} var s = document.getElementById('table'); if(null != s && 0 == s.selectedIndex) {alert('Define table name'); s.focus(); return false;}">
    </td>
    </tr>
  </table>
</form>

<? if(!empty($rowsCount)): ?>
<br/>
<table class="table" style="width: 500px" align="center">
  <tbody>
    <tr>
      <th scope="row">Total rows imported</th>
      <td><?=$rowsCount?></td>
    </tr>
    <tr>
      <th scope="row">Duplicates within the file</th>
      <td><?=$duplicatesWithinNumbers?></td>
    </tr>
    <tr>
      <th scope="row">Unique records</th>
      <td>
        <? if ($uniqueNumbers > 0) : ?>
          <a href="archive/<?=$batchId?>/unique.csv.zip" title="Click to download a zip archive"><?=$uniqueNumbers?></a>
        <? else : ?>
          <?=$uniqueNumbers?>
        <? endif; ?>
      </td>
    </tr>
    <tr>
      <th scope="row">Duplicate records</th>
      <td>
        <? if ($duplicateNumbers > 0) : ?>
          <a href="archive/<?=$batchId?>/duplicate.csv.zip" title="Click to download a zip archive"><?=$duplicateNumbers?></a>
        <? else : ?>
          <?=$duplicateNumbers?>
        <? endif; ?>
      </td>
    </tr>
  </tbody>
</table>
<? endif; ?>
