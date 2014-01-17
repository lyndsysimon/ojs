<?php 
import('classes.plugins.GenericPlugin'); 
class ExamplePlugin extends GenericPlugin { 
    function register($category, $path) { 
        if (parent::register($category, $path)) { 
            HookRegistry::register( 
                'Templates::Manager::Index::ManagementPages', 
                array(&$this, 'callback') 
            ); 
            return true; 
        } 
        return false; 
    } 
    function getName() { 
        return 'ExamplePlugin'; 
    } 
    function getDisplayName() { 
        return 'Example Plugin'; 
    } 
    function getDescription() { 
        return 'A description of this plugin'; 
    } 
    function callback($hookName, $args) { 
        $params =& $args[0]; 
        $smarty =& $args[1]; 
        $output =& $args[2]; 
        $output = '<li>&#187; <a href=”http://pkp.sfu.ca”>My New Link</a></li>'; 
        return false; 
    } 
} 
?>
