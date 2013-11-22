<?php
	function FileSubmit($FileType)
	{
?>
	<form action="submit.php" method="post" enctype="multipart/form-data">
<?php
		if($FileType == 'code')
		{
?>
		<input type="hidden" name="type" value="code"/>
		<p>
			<label for="lang">Lenguaje:</label>
			<select name="lang">
				<option value = "c">C</option>
				<option value = "cpp">C++</option>
				<option value = "java">Java</option>
				<option value = "py">Python</option>
			</select>
		</p>
		<p>
			<label for="file">Súbelo desde un archivo:<br/></label>
			<input type="file" name="file" id="file"/>
		</p>
		<p>
			<label for="dinput">Súbelo copiando el código:<br/></label>
			<textarea name="dinput" id="dinput"></textarea>
		</p>
		<p>
			<input type="submit" value="Subir código"/>
		</p>
<?php
		}
		elseif($FileType == 'burn')
		{
?>
		<input type="hidden" name="type" value="burn"/>
		<p>
			<label for="file">Súbelo desde un archivo:<br/></label>
			<input type="file" name="file" id="file"/>
		</p>
		<p>
			<label for="dinput">Súbelo copiando el burn:<br/></label>
			<textarea name="dinput" id="dinput"></textarea>
		</p>
		<p>
			<input type="submit" value="Subir burn"/>
		</p>
<?php
		}
?>
	</form>
<?php
	}
?>