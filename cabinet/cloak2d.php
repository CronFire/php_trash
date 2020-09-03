<?php
if(!defined('DATALIFEENGINE')) { die("Hacking attempt!"); }
error_reporting(0);
class Skin2d {
    private $image = NULL;
 
    function __destructor () {
        if ($this->image != NULL) {
            imagedestroy($this->image);
        }
    }

    function AssignSkinFromFile ($file) {
        if ($this->image != NULL) {
            imagedestroy($this->image);
        }
        if(($this->image = imagecreatefrompng($file)) == False) {
            // Error occured
            throw new Exception("Could not open PNG file.");
        }
        if(!$this->Valid()) {
            throw new Exception("Invalid skin image.");
        }
    }

    function AssignSkinFromString ($data) {
        if ($this->image != NULL) {
            imagedestroy($this->image);
        }
        if(($this->image = imagecreatefromstring($data)) == False) {

            throw new Exception("Could not load image data from string.");
        }
        if(!$this->Valid()) {
            throw new Exception("Invalid skin image.");
        }
    }

    function Width () {
        if($this->image != NULL) {
            return imagesx($this->image);
        } else {
            throw new Exception("No skin loaded.");
        }
    }

    function Height () {
        if($this->image != NULL) {
            return imagesy($this->image);
        } else {
            throw new Exception("No skin loaded.");
        }
    }
 
    function Valid () {
        return ($this->Width() != 64 || $this->Height() != 32) ? False : True;
    }
 
    function FrontImage ($scale = 1, $r = 255, $g = 255, $b = 255) {
        $newWidth = 16 * $scale;
        $newHeight = 32 * $scale;
 
        $newImage = imagecreatetruecolor(16, 32);
        $background = imagecolorallocate($newImage, $r, $g, $b);
        imagefilledrectangle($newImage, 0, 0, 16, 32, $background);
 
        imagecopy($newImage, $this->image, 0, 0, 0, 0, 12, 17);

 

 
        return $newImage;
    }
 

 
    function CombinedImage ($scale = 1, $r = 255, $g = 255, $b = 255) {
        $newWidth = 37 * $scale;
        $newHeight = 32 * $scale;
 
        $newImage = imagecreatetruecolor(21, 15);
        $background = imagecolorallocate($newImage, $r, $g, $b);
        imagefilledrectangle($newImage, 0, 0, 21, 15, $background);
 
        imagecopy($newImage, $this->image, 0, 0, 0, 0, 12, 17);
        $this->imagecopyalpha($newImage, $this->image, 0, 0, 0, 0, 8, 8, imagecolorat($this->image, 0, 0));

 
        imagecopy($newImage, $this->image, 0, 0, 0, 0, 8, 8);
        $this->imagecopyalpha($newImage, $this->image, 0, 0, 0, 0, 8, 8, imagecolorat($this->image, 0, 0));

 
        if($scale != 1) {
            $resize = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresized($resize, $newImage, 0, 0, 0, 0, $newWidth, $newHeight, 21, 15);
            imagedestroy($newImage);
            return $resize;
        }
 
        return $newImage;
    }

    function imagecopyalpha($dst, $src, $dst_x, $dst_y, $src_x, $src_y, $w, $h, $bg) {
        for($i = 0; $i < $w; $i++) {
            for($j = 0; $j < $h; $j++) {
 
                $rgb = imagecolorat($src, $src_x + $i, $src_y + $j);
 
                if(($rgb & 0xFFFFFF) == ($bg & 0xFFFFFF)) {
                    $alpha = 127;
                } else {
                    $colors = imagecolorsforindex($src, $rgb);
                    $alpha = $colors["alpha"];
                }
                imagecopymerge($dst, $src, $dst_x + $i, $dst_y + $j, $src_x + $i, $src_y + $j, 1, 1, 100 - (($alpha / 127) * 100));
            }
        }
    }
}
?><?php
$path = $_GET[skinpath];
$test = new Skin2d();
$test->AssignSkinFromFile($path);
 
header('Content-type: image/png');
$img = $test->CombinedImage(4);
imagepng($img);
imagedestroy($img);
?>