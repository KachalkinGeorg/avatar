<div class="row">  
<table width="100%" border="0" style="z-index: 10;position: relative;">
  <tr>
    <td align="left" valign="top">Аватары из категории {{ cat_avatar }}</td>
    <td align="right" valign="top">Категория: {{category_sel}}</td>
  </tr>
</table>
</div>
<br/>
<form action="ava_ok" method="post">

{% for entry in entries %}
<div style="display: inline-block;">
	{{ entry.list_avatar }}
</div>
{% endfor %}

</form>

{% if (pages.true) %}
<center><div class="dpad basenavi">
	<div class="bnnavi">
		<div class="navigation">
{% if (prevlink.true) %}
{{ prevlink.link }}
{% endif %}

{{ pages.print }}

{% if (nextlink.true) %}
{{ nextlink.link }}
{% endif %}
        </div>
	</div>
</div></center>
{% endif %}