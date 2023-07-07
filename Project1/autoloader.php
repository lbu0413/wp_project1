<?php
/** 
 * Set up auto loader for class definitions
 *
 * @return void 
 */
function cfg_autoloader()
{
    spl_autoload_register(
        function ($className) {
            $classFile = __DIR__ . '/' . strtolower($className) . '.php';
            if (file_exists($classFile)) {
                include $classFile;
            }
        }
    );
}
?>
