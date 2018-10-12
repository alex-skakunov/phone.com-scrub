<div style="text-align: center; margin-bottom: 40px">
	<h1>History</h1>
</div>

<? if (empty($recordset)) {
  echo '<p class="text-muted">Nothing to see.</small></p>';
  return;
}
?>

<table class="table table-striped">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Filename</th>
      <th scope="col">Total records</th>
      <th scope="col">Unique records</th>
      <th scope="col">Duplicate records</th>
      <th scope="col">Date</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody id="table-body">
      <? foreach ($recordset as $row) : ?>
        <tr id="batch_<?=(int)$row['id']?>">
          <th scope="row"><?=(int)$row['id']?></th>
          <td><?=$row['filename']?></td>
          <td><?=$row['number_total']?></td>
          <td>
            <? if ($row['number_unique'] > 0) : ?>
              <a href="archive/<?=$row['id']?>/unique.csv.zip" title="Click to download a zip archive"><?=$row['number_unique']?></a>
            <? else : ?>
              <?=$row['number_unique']?>
            <? endif; ?>
          </td>
          <td>
            <? if ($row['number_duplicate'] > 0) : ?>
              <a href="archive/<?=$row['id']?>/duplicate.csv.zip" title="Click to download a zip archive"><?=$row['number_duplicate']?></a>
            <? else : ?>
              <?=$row['number_duplicate']?>
            <? endif; ?>
          </td>
          <td><?=$row['created_at']?></td>
          <td><a href="#" onclick="if(confirm('Delete the row #<?=$row['id']?>?')) {deleteBatch(<?=$row['id']?>);} return false;" title="Delete row #<?=$row['id']?>">&times;</a></td>
        </tr>
      <? endforeach; ?>
  </tbody>
</table>

<script type="text/javascript">
  function deleteBatch(batchId){
    $('#loader').show();
    $.getJSON(
      'index.php?page=delete-batch&batch_id=' + batchId,
      function(data, textStatus) {
        if ('fail' == data['status']) {
          return alert('An error has occured!');
        }
        $('#batch_'+data['batch_id']).slideUp('fast');
        $('#loader').hide();
      }
    );
  }
</script>