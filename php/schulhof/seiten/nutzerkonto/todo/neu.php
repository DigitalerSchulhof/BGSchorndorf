<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<h1>Neues ToDo</h1>
<table class="cms_formular">
	<tr><td>Bezeichnung</td><td><input type="text" id="cms_todo_bezeichnung"></td></tr>
	<tr><td>Beschreibung</td><td><textarea id="cms_todo_beschreibung"></textarea></td></tr>
	<tr><td colspan="2"><span class="cms_button_ja" onclick="cms_eigenes_todo_speichern()">Neues ToDo anlegen</td></tr>
</table>
<input type="hidden" id="cms_todo_id" value="-">
<div class="cms_clear"></div>
