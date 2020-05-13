<!-- <!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Imagens</title>
    <link rel="stylesheet" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="jumbotron jumbotron-fluid alert-success">
        <div class="container">
            <h1 class="display-4">Montajar</h1> -->

<?php

ini_set('memory_limit', '-1');

require_once "vendor/autoload.php";

use \Montajar\Controller\ImageController;

clearstatcache();

$image = new ImageController($_FILES['imagem'], floatval($_POST["width"]), floatval($_POST["height"]));

$resized = $image->resizedImage($image->getImage());
$images = $image->generateImages($resized);

$zip = new ZipArchive();
$dir = DIRECTORY_SEPARATOR . "img-zip" . DIRECTORY_SEPARATOR . "ZIP-" . time() . '.zip';

if ($zip->open($_SERVER["DOCUMENT_ROOT"] . $dir, ZipArchive::CREATE) == true) {
    foreach ($images as $key) {
        $zip->addFile($_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . $key, $key);
    }

    echo "<p class='lead'>Os arquivos foram compactados com sucesso.</p>";
    echo "<p class='lead'>Total de folhas: " . $zip->count() . "</p>";
    echo "<p class='lead'><a href='$dir'>Clique aqui</a> para baixar as montagens.</p>";

    $zip->close();
} else {
    die("Falha ao compactar os arquivos.");
}

exit;

?>
<!--
</div>
    </div>
	<script src="/vendor/components/jquery/jquery.slim.min.js"></script>
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html> -->