<div style="text-align: center; margin-bottom: 50px;">
  <h1>
    Settings
  </h1>
</div>

<? if (!empty($errorMessage)): ?>
  <div class="alert alert-danger" role="alert">
    <?=$errorMessage?>
  </div>
  <br/>
<? endif; ?>

<? if (!empty($message)): ?>
  <div class="alert alert-info" role="alert">
    <?=$message?>
  </div>
  <br/>
<? endif; ?>

<form method="post" enctype="multipart/form-data" onsubmit="$('#loader').show();">
   <table border="0" align="center">
    <tr>
      <td><label for="regular_user_password">Regular user password:</label></td>
      <td width="10px">&nbsp;</td>
      <td><input type="password" name="regular_user_password" id="regular_user_password" class="edt" value="<?=$regular_user_password?>" /></td>
      <td>&nbsp;</td>
      <td><input id="regular_user_submit" type="Submit" name="regular_user_submit" value="Save" class="btn btn-primary" style="padding: 3px 15px" /></td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
  </table>
</form>

<form method="post" enctype="multipart/form-data" onsubmit="$('#loader').show();">
   <table border="0" align="center">
    <tr>
      <td><label for="admin_user_password">Admin user password:</label></td>
      <td width="10px">&nbsp;</td>
      <td><input type="password" name="admin_user_password" id="admin_user_password" class="edt" value="<?=$admin_user_password?>" /></td>
      <td>&nbsp;</td>
      <td><input id="admin_user_submit" type="Submit" name="admin_user_submit" value="Save" class="btn btn-primary" style="padding: 3px 15px" /></td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
  </table>
  <hr/>
</form>

<form method="post" enctype="multipart/form-data" onsubmit="$('#loader').show();">
   <table border="0" align="center">
    <tr>
      <td><input type="submit" name="erase_database" id="erase_database" class="btn btn-primary" value="Erase the phones database" /></td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>

    <tr>
      <td>
        <a href="index.php?page=download-db">Download the database archive</a>
        <p class="text-muted"><small>Records available: <?=$numbersCount?></small></p>
      </td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
  </table>
</form>

