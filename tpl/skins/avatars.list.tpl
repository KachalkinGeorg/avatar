<form action="" method="post">
<table width="100%" border="0" style="z-index: 10;position: relative;">
  <tr>
    <td align="left" valign="top">Выбран аватар {{avaname}}</td>
  </tr>
</table>
<br><br>
<div style="float:left;padding: 10px;">
	<img src="{{avatar}}">
</div>
<div style="width:100%;padding-left:7px">
	<div>Нажмите кнопку "Применить", чтобы установить этот аватар или нажмите "Выбрать другой", чтобы вернуться к списку других аватаров. </div>
</div>
<br>
<input type="submit" value="Применить" name="ava_ok" class="btn">
<a title="Вернуться назад" class="btn" href="javascript:history.go(-1)" style="color: rgb(255, 183, 55);">Выбрать другой</a>
</form>