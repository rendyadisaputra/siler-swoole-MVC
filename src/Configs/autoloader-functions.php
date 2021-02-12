<?php
$globalArray = [];
function include_dir_r( $dir_path ) {
    $path = realpath( $dir_path );
    $objects = new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( $path ), \RecursiveIteratorIterator::SELF_FIRST );
    foreach( $objects as $name => $object ) {
        if( $object->getFilename() !== "." && $object->getFilename() !== ".." ) {
            
            if( !is_dir( $name ) ){
                if (substr($name, -4) == '.php') {
                    include_once ($name);
                  }
            }
        }
    }
}
include_dir_r(realpath(__DIR__."/../Functions"));
