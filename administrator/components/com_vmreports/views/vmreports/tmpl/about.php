<?php

/**
 * Componet for dispaying statistics about VirtueMart trades.
 * Can display many types of statistics and customized them by
 * number of parameters.  
 *
 * @version		$Id$
 * @package     ArtioVMReports
 * @copyright	Copyright (C) 2010 ARTIO s.r.o.. All rights reserved. 
 * @author 		ARTIO s.r.o., http://www.artio.net
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link        http://www.artio.net Official website
 */

VmreportsHelper::importChartJS ();

?>


		<div class='left_panel'>
				<?php
				echo $this->renderMenu ();
				?>
			</div>
		
		<div class='center_panel'>
			<span class='title'><?php echo JText::_('ABOUT'); ?></span>
			<hr />
			<table class="admintable">
	   <tr>
			<td class="key"></td>
			<td>
	      		<a href="<?php echo $this->info['productpage'];?>" target="_blank">
	          		<img src="components/com_vmreports/assets/images/logo.png" align="middle" alt="VM Reports logo" style="border: none; margin: 8px;" />
	        	</a>
			</td>
		</tr>
	   <tr>
	      <td class="key" width="120"></td>
	      <td><a href="<?php echo $this->info['authorUrl']; ?>" target="_blank">ARTIO</a> VM Reports</td>
	   </tr>	
	   <tr>
	      <td class="key"><?php echo JText::_('Version'); ?>:</td>
	      <td><?php echo $this->info['version']; ?></td>
	   </tr>
	   <tr>
	      <td class="key"><?php echo JText::_('Date'); ?>:</td>
	      <td><?php echo $this->info['creationDate']; ?></td>
	   </tr>
	   <tr>
	      <td class="key" valign="top"><?php echo JText::_('Copyright'); ?>:</td>
	      <td>&copy; 2006 - <?php echo date('Y', strtotime($this->info['creationDate'])); ?>, <?php echo $this->info['copyright']; ?></td>
	   </tr>
	   <tr>
	      <td class="key"><?php echo JText::_('Author'); ?>:</td>
	      <td><a href="<?php echo $this->info['authorUrl']; ?>" target="_blank"><?php echo $this->info['author']; ?></a>,
	      <a href="mailto:<?php echo $this->info['authorEmail']; ?>"><?php echo $this->info['authorEmail']; ?></a></td>
	   </tr>
	   <tr>
	      <td class="key" valign="top"><?php echo JText::_('Description'); ?>:</td>
	      <td>
	      	<?php echo $this->info['description']; ?>
	      	</td>
	   </tr>
	   <tr>
	      <td class="key"><?php echo JText::_('License'); ?>:</td>
	      <td><a href="<?php echo $this->info['license']; ?>" target="_blank"><?php echo JText::_('Combined license') ?></a></td>
	   </tr>
	   <tr>
	      <td class="key"><?php echo JText::_('Support us'); ?>:</td>
	      <td>
	          <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	          <input name="cmd" type="hidden" value="_s-xclick"></input>
	          <input name="submit" type="image" style="border: none;" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" title="Support JoomSEF"></input>
	          <img src="https://www.paypal.com/en_US/i/scr/pixel.gif" border="0" alt="" width="1" height="1" />
	          <input name="encrypted" type="hidden" value="-----BEGIN PKCS7-----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-----END PKCS7-----"></input>
	          </form>
	      </td>
	   </tr>
	</table>
			<hr />
			<span class='title'><?php echo Jtext::_('SUPPORT'); ?></span>
			<hr />
			<div id="cpanel">	 
	 <!-- ARTIO VM Reports Homepage -->
	 <div class="icon">
	 	<a href="<?php echo $this->info['productpage'];?>" target="_blank" title="Visit ARTIO JoomSEF Homepage">
	 		<img src="components/com_vmreports/assets/images/icon-48-vmreports.png" alt="" width="48" height="48" border="0" />
	 		<span><?php echo JText::_('Official Product Page'); ?></span>
	 	</a>
	 </div>
	 <!-- ARTIO VM Reports Support Forums -->
	 <div class="icon">
	 	<a href="<?php echo $this->info['forum'];?>" target="_blank" title="Visit ARTIO support forums">
	 		<img src="components/com_vmreports/assets/images/icon-48-forum.png" alt="" width="48" height="48" border="0" />
	 		<span><?php echo JText::_('ARTIO Support Forums'); ?></span>
	 	</a>
	 </div>
	 <!-- ARTIO Paid Support -->
	 <div class="icon">
	 	<a href="<?php echo $this->info['paidsupport'];?>" target="_blank" title="Get paid support from ARTIO">
	 		<img src="components/com_vmreports/assets/images/icon-48-support.png" alt="" width="48" height="48" border="0" />
	 		<span><?php echo JText::_('ARTIO Paid Support'); ?></span>
	 	</a>
	 </div>
</div>
</div>


