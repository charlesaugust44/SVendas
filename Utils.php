<?php

namespace App;

class Utils
{
    public static function encrypt($data, $user = '')
    {
        return sha1(md5($data/*.$user*/));
    }

    public static function e404()
    {
        header("location: /404");
    }

    private static function resize($filename, $path = "")
    {
        $path = trim($path, '/');
        $filename = trim($filename, '/');
        $p = strpos($filename, '.');
        $file = substr($filename, 0, $p);
        $filepath = trim($path . '/' . $filename, '/');
        $thumbpath = trim($path . '/r_' . $file, '/');

        // Get new sizes
        list($width, $height) = getimagesize($filepath);

        $newwidth = 750;
        $newheight = $height * $newwidth / $width;

        if ($newheight < 375) {
            $newheight = 375;
            $newwidth = $width * $newheight / $height;
        }

        // Load
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        $source = imagecreatefromjpeg($filepath);

        // Resize
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        // Output
        imagejpeg($thumb, $thumbpath . '.jpg', 100);

        return $file . '.jpg';
    }

    public static function upload($uploadTo)
    {
        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (!isset($_FILES['imagem']['error']) || is_array($_FILES['imagem']['error']))
            return array(false, "paramError");

        // Check $_FILES['imagem']['error'] value.
        switch ($_FILES['imagem']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                return array(false, "noFile");
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return array(false, "size");
            default:
                return array(false, "error");
        }

        // You should also check filesize here.
        if ($_FILES['imagem']['size'] > 3000000)
            return array(false, "size");

        // DO NOT TRUST $_FILES['imagem']['mime'] VALUE !!
        // Check MIME Type by yourself.
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === ($ext = array_search($finfo->file($_FILES['imagem']['tmp_name']), array('jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif',), true)))
            return array(false, "format");

        // You should name it uniquely.
        // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        $fileName = "img" . date("Ymdhis") . rand(0, 100) . "." . $ext;
        $newPath = $GLOBALS['view'] . "Assets/images/" . $uploadTo . '/';

        if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $newPath . $fileName))
            return array(false, "upload");

        $fileName = self::resize($fileName, $newPath);
        unlink($newPath . '/' . $fileName);
        rename($newPath . 'r_' . $fileName, $newPath . $fileName);

        return array(true, $uploadTo . '/' . $fileName);
    }

    public static function checkPdoParam($value, $param)
    {
        switch ($param) {
            case PDO::PARAM_INT;
                return intval($value);
                break;
            case PDO::PARAM_BOOL;
                return intval($value);
                break;
            case PDO::PARAM_STR;
                return htmlspecialchars(addslashes($value));
                break;
        }
    }
}