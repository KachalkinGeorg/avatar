<form method="post" action="">

<div class="panel-body" style="font-family: Franklin Gothic Medium;text-transform: uppercase;color: #9f9f9f;">Редактирование категории</div>
<div class="table-responsive">
	<table class="table table-striped">
      <tr>
        <td width="50%">Имя
		  <br><span class="text-muted text-size-small hidden-xs">Имя категории (русскими буквами)</span>
		</td>
        <td width="50%">
		  <input class="edit" style="width: 200px;" value="{{name}}" type="text" name="name">
        </td>
      </tr>
      <tr>
        <td width="50%">Alt-name
		  <br><span class="text-muted text-size-small hidden-xs">Это имя будет использоваться в ссылке на категорию и названии папки с аватарами (латиницей со строчной буквы). Если оставить это поле пустым, оно будет заполнено автоматически.</span>
		</td>
        <td width="50%">
		  <input class="edit" style="width: 300px;" value="{{altname}}" type="text" name="altname">
        </td>
      </tr> 
      <tr>
        <td width="50%">Загрузка файлов
		  <br><span class="text-muted text-size-small hidden-xs">Разрешена загрузка файлов в данную категорию</span>
		</td>
        <td width="50%">
		  <input class="radio" type="radio" name="upload" value="1" {{ch_upload_1}} />&nbsp;&nbsp;<label>да</label>&nbsp;&nbsp;<input class="radio" type="radio" name="upload" value="0" {{ch_upload_0}} />&nbsp;&nbsp;<label>нет</label>
        </td>
      </tr>
      <tr>
        <td width="50%">Скрыта
		  <br><span class="text-muted text-size-small hidden-xs">Скрываем категорию от пользователей</span>
		</td>
        <td width="50%">
		  <input class="radio" type="radio" name="hidden" value="1" {{ch_hidden_1}} />&nbsp;&nbsp;<label>да</label>&nbsp;&nbsp;<input class="radio" type="radio" name="hidden" value="0" {{ch_hidden_0}} />&nbsp;&nbsp;<label>нет</label>
        </td>
      </tr>
	</table>
</div>
	<div class="panel-footer" align="center" style="margin-left: -20px;margin-right: -20px;margin-bottom: -20px;">
		<button type="submit" name="submit" class="btn btn-outline-primary">&nbsp;&nbsp;Сохранить&nbsp;&nbsp;</button>
	</div>
	
</form>