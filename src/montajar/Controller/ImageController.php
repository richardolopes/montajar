<?php

namespace Montajar\Controller;

use Montajar\Model\ImageModel;

class ImageController extends ImageModel
{
    const MONTAGE = "img-montage";
    const RESIZED = "img-resized";
    const ZIP = "img-zip";

    public $contents = [];
    public $count = 0;

    public function __construct($file, $width, $height)
    {
        (!file_exists($_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . ImageController::MONTAGE)) ? mkdir($_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . ImageController::MONTAGE) : "";
        (!file_exists($_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . ImageController::RESIZED)) ? mkdir($_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . ImageController::RESIZED) : "";
        (!file_exists($_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . ImageController::ZIP)) ? mkdir($_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . ImageController::ZIP) : "";

        $this->setPixel(3.78);

        $this->setRealHeightA4();
        $this->setRealWidthA4();

        $this->setHeightA4();
        $this->setWidthA4();

        $this->setImage($file);

        $this->setSize($width, $height);
    }

    // define as dimensoes de X ou Y caso o usuario nao digite
    public function setSize(float $width, float $height): void
    {
        $oldWidth = imagesx($this->getImage());
        $oldHeight = imagesy($this->getImage());

        isset($height) ? $newHeight = floatval($height) : $newHeight = 0;
        isset($width) ? $newWidth = floatval($width) : $newWidth = 0;

        if ($newWidth == 0) {
            $dif = $newHeight / $oldHeight;
            $newWidth = $oldWidth * $dif;
        }

        if ($newHeight == 0) {
            $dif = $newWidth / $oldWidth;
            $newHeight = $oldHeight * $dif;
        }

        $this->setWidth($newWidth);
        $this->setHeight($newHeight);
    }

    // redimensiona a imagem de acordo com o tamanho que o usuario deseja
    public function resizedImage($imageReceived)
    {
        $return = imagecreatetruecolor($this->getWidth(), $this->getHeight());
        imagefill($return, 0, 0, imagecolorallocatealpha($return, 0, 0, 0, 127));
        // Tratamento para deixar a imagem transparente.
        imagesavealpha($return, true);

        $sourceWidth = imagesx($imageReceived);
        $sourceHeight = imagesy($imageReceived);

        imagecopyresampled($return, $imageReceived, 0, 0, 0, 0, $this->getWidth(), $this->getHeight(), $sourceWidth, $sourceHeight);
        $this->saveImage($return, ImageController::RESIZED);

        return $return;
    }

    public function saveImage($image, string $dir): bool
    {
        $dir .= DIRECTORY_SEPARATOR . "IMG-" . $this->count . " " . time() . ".png";
        $return = imagepng($image, $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . $dir);
        array_push($this->contents, $dir);
        $this->count++;

        return $return;
    }

    public function createImageTransparent(float $width, float $height)
    {
        $image = imagecreatetruecolor($width, $height);
        imagefill($image, 0, 0, imagecolorallocatealpha($image, 0, 0, 0, 127));
        imagesavealpha($image, true);

        return $image;
    }

    public function cutImage($image, float $src_x, float $src_y)
    {
        $width = imagesx($image);
        $height = imagesy($image);

        if ($src_x <= $width && $src_y <= $height) {
            $tam_x = $src_x + $this->getWidthA4();
            $tam_y = $src_y + $this->getHeightA4();

            // o resto da folha ou a folha inteira
            $src_width = ($tam_x > $width) ? $this->getWidthA4() - ($tam_x - $width) : $this->getWidthA4();
            $src_height = ($tam_y > $height) ? $this->getHeightA4() - ($tam_y - $height) : $this->getHeightA4();

            if ($src_width == $this->getWidthA4()) {
                $sheet = $this->createImageTransparent($this->getRealWidthA4(), $this->getRealHeightA4());
            } else {
                $sheet = $this->createImageTransparent($src_width, $src_height);
            }

            imagecopyresampled($sheet, $image, 0, 0, $src_x, $src_y, $src_width, $src_height, $src_width, $src_height);
            return $sheet;
        } else {
            die("POSICAO X OU Y É MAIOR QUE O TAMANHO DA IMAGEM.");
        }
    }

    // controlar os cortes da montagem e retorna os diretorios das imagens
    public function generateImages($image)
    {
        $aux = 0;
        $images = array();

        $width = imagesx($image);
        $height = imagesy($image);

        $qtd_x = $width / $this->getWidthA4();
        $qtd_y = $height / $this->getHeightA4();

        for ($y = 0; $y < $qtd_y; $y++) {
            for ($x = 0; $x < $qtd_x; $x++) {
                $aux++;
                $cut = $this->cutImage($image, $this->getWidthA4() * $x, $this->getHeightA4() * $y);

                if (imagesx($cut) < ($this->getWidthA4() / 2) || imagesy($cut) < ($this->getHeightA4() / 2)) {
                    array_push($images, array(
                        "IMAGE" => $cut,
                        "PART" => $aux,
                    ));
                } else {
                    imagestring($cut, 5, 10, 280 * $this->getPixel(), "PARTE: $aux", imagecolorallocate($cut, 255, 0, 0));
                    $this->saveImage($cut, ImageController::MONTAGE);
                }
            }
        }

        (count($images) > 0) ? $this->combinationImages($images) : '';

        return $this->contents;
    }

    // responsavel por juntar diversas imagens (pequenas) nas mesma folha A4
    public function combinationImages(array $images)
    {
        $width = 0;

        for ($i = 0; $i < count($images); $i++) {
            if (!isset($a4)) {
                $a4 = $this->createImageTransparent($this->getRealWidthA4(), $this->getRealHeightA4());
                imagecopyresampled($a4, $images[$i]["IMAGE"], $width, 0, 0, 0, imagesx($images[$i]["IMAGE"]), imagesy($images[$i]["IMAGE"]), imagesx($images[$i]["IMAGE"]), imagesy($images[$i]["IMAGE"]));

                imagestring($a4, 5, $width, 280 * $this->getPixel(), "PARTE: " . $images[$i]['PART'], imagecolorallocate($a4, 255, 0, 0));

                $width += imagesx($images[$i]["IMAGE"]);
            }

            if (isset($images[$i + 1]["IMAGE"]) and (imagesx($images[$i]["IMAGE"]) + 10 + imagesx($images[$i + 1]["IMAGE"]) < $this->getWidthA4())) {
                // próxima imagem
                $i++;
                imagecopyresampled($a4, $images[$i]["IMAGE"], $width + 10, 0, 0, 0, imagesx($images[$i]["IMAGE"]), imagesy($images[$i]["IMAGE"]), imagesx($images[$i]["IMAGE"]), imagesy($images[$i]["IMAGE"]));

                imagestring($a4, 5, $width, 280 * $this->getPixel(), "PARTE: " . $images[$i]['PART'], imagecolorallocate($a4, 255, 0, 0));

                $this->saveImage($a4, ImageController::MONTAGE);
            } else {
                $this->saveImage($a4, ImageController::MONTAGE);

                $width = 0;
                unset($a4);
            }
        }

    }
}
