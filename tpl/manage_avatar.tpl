
			<table class="table table-sm mb-0">
				<tr>				
					<td><label style="padding: .375rem 0rem .75rem 0rem;">Выберите категорию:</label></td>
					<td>
						{{category_sel}}
					</td>
					<td>
						{{upload_link}}
					</td>
				</tr>
				<tr>				
					<td><label style="padding: .375rem 0rem .75rem 0rem;">Размер всех файлов:</label></td>
					<td>
						{{total_size}}
					</td>
					<td><label style="padding: .375rem 0rem .75rem 0rem;">{{avat_num}}</label></td>
				</tr>			
				
			</table>

<div class="table-responsive table-sm mb-0"></div>
<form action="" method="post">
<table width="100%">
	<tr>
		<td style="padding:4px;" class="navigation"><strong>#</strong></td>
		<td style="padding:4px;" class="navigation"><strong>Имя</strong></td>
		<td style="padding:4px;" class="navigation"><strong>Картинка</strong></td>
		<td style="padding:4px;" class="navigation"><strong>Размер</strong></td>
		<td style="padding:4px;" class="navigation"><strong>Разрешение</strong></td>
		<td style="padding:4px;" class="navigation"><center><strong>Действие</strong></center></td>
	</tr>
{{entries}}
<tr>
	<td align="center" colspan="8" class="contentHead">{{pagesss}}</td>
</tr>

</table>

<div class="text-right">
	<input class="edit" type="submit" name="submit" class="btn btn-sm btn-danger" value="&nbsp;&nbsp;Удалить выбранное&nbsp;&nbsp;">
</div>

</form>