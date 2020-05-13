<?php

namespace Montajar\Model;

class ImageModel
{
    // definicao do pixel por polegada (melhoria ainda n feita)
    public $pixel;

    // divisao de tamanhos ('real' e 'ficticio') por causa de um problema na minha impressora
    public $realHeightA4;
    public $realWidthA4;
    public $heightA4;
    public $widthA4;

    // tamanho da imagem desejada
    public $height;
    public $width;

    // imagem do usuario
    public $image;

    public function getPixel()
    {
        return $this->pixel;
    }

    public function setPixel(float $value)
    {
        $this->pixel = $value;
        return $this;
    }

    public function getRealWidthA4()
    {
        return $this->realWidthA4;
    }

    public function setRealWidthA4(float $value = 210)
    {
        $this->realWidthA4 = $value * $this->pixel;
        return $this;
    }

    public function getRealHeightA4()
    {
        return $this->realHeightA4;
    }

    public function setRealHeightA4(float $value = 297)
    {
        $this->realHeightA4 = $value * $this->pixel;
        return $this;
    }

    public function getHeightA4()
    {
        return $this->heightA4;
    }

    public function setHeightA4(float $value = 270)
    {
        $this->heightA4 = $value * $this->pixel;
        return $this;
    }

    public function getWidthA4()
    {
        return $this->widthA4;
    }

    public function setWidthA4(float $value = 210)
    {
        $this->widthA4 = $value * $this->pixel;
        return $this;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight(float $value)
    {
        $this->height = $value * $this->pixel;
        return $this;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth(float $value)
    {
        $this->width = $value * $this->pixel;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage(array $file)
    {
        $extension = explode('.', $file["name"]);
        $extension = end($extension);

        switch ($extension) {
            case 'png':
                $img = imagecreatefrompng($file["tmp_name"]);
                break;
            case 'jpeg' || 'jpg':
                $img = imagecreatefromjpeg($file["tmp_name"]);
                break;
            default:
                die("Extensão não programada");
                break;
        }

        $this->image = $img;
        return $this;
    }
}
