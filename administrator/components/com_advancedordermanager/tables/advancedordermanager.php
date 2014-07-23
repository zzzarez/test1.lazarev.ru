<?php
class TableAdvancedordermanager extends JTable
{
    
    var $id = null;
    var $savequery=null;
 
    /*
     Constructor
    */
    function __construct( &$db ) {
        parent::__construct('#__advancedordermanager', 'id', $db);

    }
}





?>