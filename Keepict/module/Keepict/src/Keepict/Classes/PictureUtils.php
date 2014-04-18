<?php
namespace Keepict\src\Keepict\Classes;

class PictureUtils
{
    public static function getExifFloatData($value) {
    	$pos = strpos($value, '/');
    	if ($pos === false) return (float) $value;
    	$a = (float) substr($value, 0, $pos);
    	$b = (float) substr($value, $pos+1);
    	return ($b == 0) ? ($a) : ($a / $b);
    }
    
    public static function printExifData($exif){
    	if($exif !== false){
    	    if(isset($exif['Make']) && 
    	       isset($exif['Model']) && 
               isset($exif['FocalLength']) && 
               isset($exif['ExposureTime']) && 
               isset($exif['FNumber']) &&
               isset($exif['ISOSpeedRatings'])){
    	        $appareil = $exif['Make'] . ' ' . $exif['Model'];
    	        $regexp = '#(\b[a-zA-Z]+\b)\s{1,}\1#';
    	        
    	        if(preg_match($regexp, $appareil)){
    	        	$appareil = $exif['Model'];
    	        }else {
    	        	$appareil = $exif['Make'] . ' ' . $exif['Model'];
    	        }
    	        
    	        $focale = PictureUtils::getExifFloatData($exif['FocalLength']) . 'mm';
    	        $vitesse = $exif['ExposureTime'] . ' secs';
    	        $ouverture = 'f/' . PictureUtils::getExifFloatData($exif['FNumber']);
    	        $iso = $exif['ISOSpeedRatings'];
    	        
    	        return array(
	        		'appareil' => $appareil,
	        		'focale' => $focale,
	        		'vitesse' => $vitesse,
	        		'ouverture' => $ouverture,
	        		'iso' => $iso
    	        );
    	    }else{
    	        return null;
    	    }
    	}
    }
}

?>