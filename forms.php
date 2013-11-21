<?php
	function FileSubmit($FileType)
	{
		if($FileType == 'code')
		{
		?>
			<form action = "submit.php?type=code" method = "post" enctype = "multipart/form-data">
				<p>
					<label for = "lang">Lenguaje:</label>
					<select name = "lang">
						<option value = "c">C</option>
						<option value = "cpp">C++</option>
						<option value = "java">Java</option>
						<option value = "py">Python</option>
					</select>
				</p>
				<p>
					<label for = "file">Súbelo desde un archivo:<br/></label>
					<input type = "file" name = "file" id = "file"/>
				</p>
				<p>
					<label for = "code">Súbelo copiando el código:<br/></label>
					<textarea name = "code" id = "code"></textarea>
				</p>
				<p>
					<input type = "submit" value = "Subir código"/>
				</p>
			</form>
		<?php
		}
		elseif($FileType == 'burn')
		{
		?>
			<form action = "submit.php?type=burn" method = "post" enctype = "multipart/form-data">
				<p>
					<label for = "file">Súbelo desde un archivo:<br/></label>
					<input type = "file" name = "file" id = "file"/>
				</p>
				<p>
					<label for = "burn">Súbelo copiando el burn:<br/></label>
					<textarea name = "burn" id = "burn"></textarea>
				</p>
				<p>
					<input type = "submit" value = "Subir burn"/>
				</p>
			</form>
		<?php
		}
	}
?>