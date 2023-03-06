<form action="" enctype="multipart/form-data" method="post">

<div class="panel-body" style="font-family: Franklin Gothic Medium;text-transform: uppercase;color: #9f9f9f;">Загрузка аватаров на сервер</div>
<div class="table-responsive">
	<table class="table table-striped">
      <tr>
        <td width="50%">С жесткого диска:
		  <br><span class="text-muted text-size-small hidden-xs">Выбрать картинку</span>
		</td>
        <td width="50%">
		  <input type="file" size="41" name="uphard">
        </td>
      </tr>
      <tr>
        <td width="50%">ZIP-архив:
		  <br><span class="text-muted text-size-small hidden-xs">Вы можете выбрать упакованные в архив изображение, чтобы загрузить целую кучу файлов сразу.<br>ВНИМАНИЕ<br>при загрузке файлов создается запрос к БД, чем больше файлов одновременно загружается тем больше запросов к БД поступают.<br>ВАЖНО ЗНАТЬ<br>архив с изображением не должен содержать папки</span>
		</td>
        <td width="50%">
		  <input type="file" size="41" name="zip_archive">
        </td>
      </tr>
      <tr>
        <td width="50%">С сервера (URL):
		  <br><span class="text-muted text-size-small hidden-xs">Введите ссылку на картинку</span>
		</td>
        <td width="50%">
		  <input type="text" size="41" name="upurl">
        </td>
      </tr>
      <tr>
        <td width="50%">ZIP-архив с сервера (URL):
		  <br><span class="text-muted text-size-small hidden-xs">Введите ссылку на архив с картинками</span>
		</td>
        <td width="50%">
		  <input type="text" size="41" name="upzipurl">
        </td>
      </tr>
      <tr>
        <td width="50%">Выберите категорию для загрузки:
		  <br><span class="text-muted text-size-small hidden-xs">Куда загруженные файлы размечать</span>
		</td>
        <td width="50%">
		  {{ category_sel1 }}
        </td>
      </tr>	
	</table>
</div>
	<div class="panel-footer" align="center" style="margin-left: -20px;margin-right: -20px;margin-bottom: -20px;">
		<input type="submit" name="submit" class="btn btn-outline-primary" value="&nbsp;&nbsp;Загрузить&nbsp;&nbsp;">
	</div>


</form>