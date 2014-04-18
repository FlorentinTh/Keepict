<?php
namespace Keepict\src\Keepict\Classes;

class FolderUtils
{
    public static function rmdirRf($dir){
    	$files = array_diff(scandir($dir), array('.','..'));
    	foreach ($files as $file) {
    		(is_dir("$dir/$file")) ? $this->rmdirRf("$dir/$file") : unlink("$dir/$file");
    	}
    	return rmdir($dir);
    }
}

?>