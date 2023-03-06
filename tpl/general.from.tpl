<form method="post" action="">
	<div class="panel-body" style="font-family: Franklin Gothic Medium;text-transform: uppercase;color: #9f9f9f;">Настройки плагина</div>
	<div class="table-responsive">
	<table class="table table-striped">
      <tr>
        <td class="col-xs-6 col-sm-6 col-md-7">
		  <h6 class="media-heading text-semibold">Аватаров на страницу:</h6>
		  <span class="text-muted text-size-small hidden-xs">Укажите количество аватаров, выводимых на страницу (по умолчанию 5)</span>
		</td>
        <td class="col-xs-6 col-sm-6 col-md-5">
			<input name="av_per_page" type="text" size="4" value="{{ av_per_page }}" />
        </td>
      </tr>

      <tr>
        <td class="col-xs-6 col-sm-6 col-md-7">
		  <h6 class="media-heading text-semibold">Удалять аватар из списка</h6>
		  <span class="text-muted text-size-small hidden-xs">При выборе пользователем аватар из списка, можно будет удалить выбранный аватар с сервера:<br>Да - Аватар будет удаляться с сервера и с БД<br>Нет - файлы загруженные на сервер будут нетронутыми<br>ВНИМАНИЕ!<br>Все загруженные аватары лежат в папке <strong>list</strong>, при выборе пользователем одного из аватара, изображение переносётся в папку <strong>avatars</strong></span>
		</td>
        <td class="col-xs-6 col-sm-6 col-md-5">
			<select name="av_serv_del">{{ av_serv_del }}</select>
        </td>
      </tr>	

      <tr>
        <td class="col-xs-6 col-sm-6 col-md-7">
		  <h6 class="media-heading text-semibold">Выберите каталог из которого плагин будет брать шаблоны для отображения</h6>
		  <span class="text-muted text-size-small hidden-xs"><b>Шаблон сайта</b> - плагин будет пытаться взять шаблоны из общего шаблона сайта; в случае недоступности - шаблоны будут взяты из собственного каталога плагина<br /><b>Плагин</b> - шаблоны будут браться из собственного каталога плагина</span>
		</td>
        <td class="col-xs-6 col-sm-6 col-md-5">
		  {{ localsource }}
        </td>
      </tr>
	</table>
	</div>
	<div class="panel-footer" align="center" style="margin-left: -20px;margin-right: -20px;margin-bottom: -20px;">
		<button type="submit" name="submit" class="btn btn-outline-primary">Сохранить</button>
	</div>
</form>