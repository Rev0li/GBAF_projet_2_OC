<?php
//i creat a fonction resize_image with 2 arguments, $fichier and $max_resolution
function crop_image($file,$max_resolution){
//if file exist {}
    if(file_exists($file)){
        $original_image= imagecreatefromjpeg($file);

        //check original size image
        $original_width= imagesx($original_image);
        $original_height= imagesy($original_image);
        
        //here is diffrent "resize" image, because 300px is a minimum for after done 300*300
        //in resize_image 300px is a maximum 
        if($original_height > $original_width){
            
            $ratio = $max_resolution / $original_width;
            $new_width = $max_resolution;
            $new_height = $original_height * $ratio;

            $diff = $new_height - $new_width;

            $x=0;
            $y=round($diff/2);
        }else{
            $ratio = $max_resolution / $original_height;
            $new_height = $max_resolution;
            $new_width = $original_width * $ratio;

            $diff = $new_width - $new_height;

            $x=round($diff/2);
            $y=0;
        }

        if($original_image){

            $new_image = imagecreatetruecolor($new_width,$new_height);
            imagecopyresampled($new_image,$original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
            
            $new_crop_image = imagecreatetruecolor($max_resolution,$max_resolution);
            imagecopyresampled($new_crop_image,$new_image, 0, 0, $x, $y, $max_resolution, $max_resolution, $max_resolution, $max_resolution);

            imagejpeg($new_crop_image,$file,90);
        }
    }

}

