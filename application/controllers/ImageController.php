<?php

require_once 'BaseController.php';

class ImageController extends BaseController
{

    protected function carregarBd()
    {
        return;
    }

    public function redimensionar()
    {
        $imgFilename = str_replace('..', '', $this->input->get('img'));
        $maxWidth    = intval($this->input->get('maxWidth'));
        $maxHeight   = intval($this->input->get('maxHeight'));

//        if (!file_exists($image_file))
//        {
//            $image_file = base64_decode($image_file);
//        }

        ini_set("memory_limit", "100M");

        header('Content-type: image/png');

        $naoDisponivel = APPPATH . '../assets/img/produto_sem_foto.jpg';

        // Habilita o cache
        $cacheEnabled = true;

        // Diret�rio para cache das imagens redimensionadas
        $cacheDir = APPPATH . "cache/imagemdim_cache";

        # Pega onde est� a imagem
        $image_file = APPPATH . '../assets/' . $imgFilename;
        $image_path = $image_file;

        # Carrega a imagem
        $img = null;

        $arrFilepath = explode('.', $image_path);
        $extensao    = strtolower(end($arrFilepath));

        // Ignora tipos de arquivo desconhecidos
        if (!in_array($extensao, array("jpg", "jpeg", "png", "gif")))
        {
            exit;
        }

        if ($cacheEnabled)
        {
            $cache_filename = $cacheDir . "/" . strtr($imgFilename, "/\\.", "___") . "_" . $maxWidth . "x" . $maxHeight . ".png";
        }


        if (file_exists($image_file))
        {
            // Envia a imagem de cache
            if ($cacheEnabled)
            {
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($image_path)) . ' GMT');

                if (file_exists($cache_filename) && filemtime($image_path) <= filemtime($cache_filename))
                {
                    readfile($cache_filename);
                    exit;
                }
            }

            try
            {
                if ($extensao == 'jpg' || $extensao == 'jpeg')
                {
                    $img = @imagecreatefromjpeg($image_path);
                }
                else if ($extensao == 'png')
                {
                    $img = @imagecreatefrompng($image_path);
                    // Se a vers�o do GD incluir suporte a GIF, mostra...
                }
                elseif ($extensao == 'gif')
                {
                    $img = @imagecreatefromgif($image_path);
                }
            }
            catch (Exception $e)
            {
                $img = false;
            }
        }

        if (file_exists($naoDisponivel))
        {
            if (!$img)
            {
                $image_path = $naoDisponivel;
                $arrImg     = explode('.', $image_path);
                $extensao   = strtolower(end($arrImg));

                if ($extensao == 'jpg' || $extensao == 'jpeg')
                {
                    $img = @imagecreatefromjpeg($image_path);
                }
                else if ($extensao == 'png')
                {
                    $img = @imagecreatefrompng($image_path);
                    // Se a vers�o do GD incluir suporte a GIF, mostra...
                }
                elseif ($extensao == 'gif')
                {
                    $img = @imagecreatefromgif($image_path);
                }
            }
        }

        // Se a imagem foi carregada com sucesso, testa o tamanho da mesma
        if ($img)
        {
            //imageantialias($img,true);
            // Pega o tamanho da imagem e propor��o de resize
            $width  = imagesx($img);
            $height = imagesy($img);
            $scale  = min($maxWidth / $width, $maxHeight / $height);

            // Se a imagem � maior que o permitido, encolhe ela!
            if ($scale < 1)
            {
                $new_width  = floor($scale * $width);
                $new_height = floor($scale * $height);
                // Cria uma imagem tempor�ria
                $tmp_img    = imagecreatetruecolor($new_width, $new_height);
                $back       = imagecolorallocate($tmp_img, 255, 255, 255);
                imagefilledrectangle($tmp_img, 0, 0, imagesx($tmp_img), imagesy($tmp_img), $back);
                // Copia e resize a imagem velha na nova
                imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagedestroy($img);
                $img        = $tmp_img;
            }
        }

        // Cria uma imagem de erro se necess�rio
        if (!$img)
        {
            $img = imagecreate($maxWidth, $maxHeight);
            imagecolorallocate($img, 204, 204, 204);
            $c   = imagecolorallocate($img, 153, 153, 153);
            $c1  = imagecolorallocate($img, 0, 0, 0);
            //imageline($img, 0, 0, $maxWidth, $maxHeight, $c);
            //imageline($img, $maxWidth, 0, 0, $maxHeight, $c);
            //imagestring($img, 2, 12, 55, 'erro ao carregar imagem', $c1);
        }

        // Mostra a imagem

        $output_path = null;
        if ($cacheEnabled)
        {
            $output_path = $cache_filename;
        }

        imagepng($img, $output_path, 9);

        if ($cacheEnabled)
        {
            readfile($cache_filename);
        }
    }

}
