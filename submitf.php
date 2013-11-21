<!DOCTYPE html>
<html>
	<body>
		<form action="submit.php" method="post" enctype="multipart/form-data">
			<h1>Subir código fuente</h1>
			<p>
				<label for="lang">Lenguaje:</label>
				<select name="lang">
					<option value="c">C</option>
					<option value="cpp">C++</option>
					<option value="java">Java</option>
					<option value="py">Python</option>
				</select>
			</p>
			<p>
				<label for="file">Archivo:</label>
				<input type="file" name="file" id="file"/>
			</p>
			<p>
				<label for="file">...o pega el código aquí:<br/>
				<textarea name="code" id="code"></textarea>
			</p>
			<p>
				<input type="submit" value="Subir código"/>
			</p>
		</form>
	</body>
</html>