<table class="form-class table-2-colums">
	<tr class="row-2-columns left">
		<th><label for="information_by">Informatie door</label>
		</th>
		<th><label
				for="information_message">Informatie bericht</label>
		</th>
	</tr>
	<tr class="row-2-columns right">
		<td><input type="text" name="information_by" class="regular-text"
		           id="information_by"
		           value="<?php echo $this->model->information_by; ?>"></td>
		<td><input type="text" name="information_message" class="regular-text"
		           id="information_message"
		           value="<?php echo $this->model->information_message; ?>"></td>

	</tr>
</table>