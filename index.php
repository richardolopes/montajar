<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Imagens</title>
	<link rel="stylesheet" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
	<script src="/src/sweetalert/sweetalert.min.js"></script>
</head>

<body>
	<div class="jumbotron jumbotron-fluid alert-success">
		<div class="container">
			<h1 class="display-4">Montajar</h1>
			<p class="lead">Criar montagens</p>
			<form method="post" action="montage.php" enctype="multipart/form-data">
				<div class="row">
					<div class="col-sm">
						<div class="form-group">
							<p class="lead">Configurações da montagem</p>
							<label for="imagem">Imagem:</label>
							<input type="file" class="form-control-file" id="imagem" name="imagem">
							<small class="form-text text-muted">Escolha a imagem que deseja</small>
						</div>
						<div class="form-group">
							<label for="height">Altura da montagem:</label>
							<input type="text" class="form-control" id="height" name="height" aria-describedby="height">
							<small class="form-text text-muted">Digite um número em milímetros</small>
						</div>
						<div class="form-group">
							<label for="width">Largura da montagem:</label>
							<input type="text" class="form-control" id="width" name="width" aria-describedby="width">
							<small class="form-text text-muted">Digite um número em milímetros</small>
						</div>
					</div>
					<div class="col-sm">
						<p class="lead">Configurações da folha</p>
						<div class="form-group">
							<label for="pixel">Resolução:</label>
							<input type="text" class="form-control" id="pixel" name="pixel" aria-describedby="pixel"
								disabled value="Em desenvolvimento">
							<small class="form-text text-muted">Digite um número em pixels/polegada</small>
						</div>
						<div class="form-group">
							<label for="heightA4">Altura da folha A4:</label>
							<input type="text" class="form-control" id="heightA4" name="heightA4"
								aria-describedby="heightA4" value="270">
							<small class="form-text text-muted">Digite a altura da folha A4 em milímetros</small>
						</div>
						<div class="form-group">
							<label for="widthA4">Largura da folha A4:</label>
							<input type="text" class="form-control" id="widthA4" name="widthA4"
								aria-describedby="widthA4" value="210">
							<small class="form-text text-muted">Digite a largura da folha A4 em milímetros</small>
						</div>
					</div>
				</div>
				<button type="submit" onclick="wait()" class="btn btn-primary">Enviar</button>
			</form>
		</div>
	</div>

	<script src="/vendor/components/jquery/jquery.slim.min.js"></script>
	<script src="/vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

	<script>
		function wait() {
			swal({
				title: "Aguarde o processamento...",
				text: "A imagem foi enviada ao servidor.\nEm poucos instantes a montagem estará disponível para download.",
				icon: "/src/images/loading.gif",
				button: "Vou aguardar pacientemente!",
				dangerMode: true
			});
		}
	</script>
</body>

</html>
