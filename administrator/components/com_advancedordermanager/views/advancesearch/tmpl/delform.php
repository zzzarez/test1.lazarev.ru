<?php
defined('_JEXEC') or die('Restricted access');
?> 
	<form action="index.php" method="post" name="adminForm">
<div>
 <table class="adminlist">
	<!-- column header starts-->
	   
    <thead>
        <tr>
            <th width="2%">
                <?php echo JText::_( '#' ); ?>
            </th>
            <th width="3%">
              <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
            </th>
					
            <th width="9%" >
          <?php echo JText::_( 'Search Name' ); ?></th>
          
				 <th width="10" >
          <?php echo JText::_( 'Description' ); ?></th>
            
		</tr>
		</thead> 
		
	<!-- column header end-->        
	
    <?php
    $k = 0;
	$i=0;
   // loop for display records
	for ($i=0, $n=count($this->items); $i < $n; $i++)
			{
			
				$row = & $this->items[$i];
	
        $checked = Jhtml::_( 'grid.id', $i, $row->id );
       
        ?>
        <tr class="<?php echo "row$k"; ?>">
            
				
            <td>
              <?php echo $i; ?>
            </td>
           
			<td align="center">
            <?php echo $checked; ?>
            </td>
			<td align="center">
            <?php echo $row->searchname ?>
            </td>
			<td align="center">
              <?php echo $row->description ?>
            </td>
           
        </tr>
        <?php
     
	  $k = 1 - $k;
    }
    ?>   

 <input type="hidden" name="view" value="" />
<input type="hidden" name="option" value="com_advancedordermanager" />
<input type="hidden" name="task" value="remove" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="order" />


<?php echo JHTML::_( 'form.token' ); ?>
</table>
 </div>
	</form>