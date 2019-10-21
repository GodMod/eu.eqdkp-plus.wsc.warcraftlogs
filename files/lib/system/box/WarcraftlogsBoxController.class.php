<?php
namespace wcf\system\box;
use wcf\system\WCF;
use wcf\system\box\AbstractBoxController;
use wcf\system\cache\builder\WoWRealmstatusBoxCacheBuilder;

use wcf\util\CryptoUtil;
use wcf\util\HTTPRequest;
use wcf\util\JSON;
use wcf\util\exception\CryptoException;
use wcf\system\exception\SystemException;
use wcf\system\request\LinkHandler;
use wcf\system\cache\builder\WarcraftlogsBoxCacheBuilder;

/**
 * Controller for the WoW Realmstatus Box
 *
 * @author		GodMod
 * @copyright	2019 GodMod / EQdkp Plus Team
 * @license		AGPLv3 <https://eqdkp-plus.eu/en/about/license-agpl.html>
 */
class WarcraftlogsBoxController extends AbstractBoxController {

	//setup
	protected static $supportedPositions = ['sidebarLeft', 'sidebarRight'];

	//functions
	protected function loadContent(){

		if(strlen(WARCRAFTLOGS_APIKEY)){
			//get logs from cache
			$arrLogs = WarcraftlogsBoxCacheBuilder::getInstance()->getData();
			
			$arrOut = array();
			
			switch(WARCRAFTLOGS_TYPE){
				case 'retail':	$url = 'https://www.warcraftlogs.com';
				break;
				
				case 'classic':	$url = 'https://classic.warcraftlogs.com';
				break;
			}	
			
			if(is_array($arrLogs)){
				$i=0;
				foreach($arrLogs as $arrLog){
					if($i === intval(WARCRAFTLOGS_COUNT)) break;
					
					if (MODULE_IMAGE_PROXY) {
						$icon = $this->getProxyLink('https://dmszsuqyoe6y6.cloudfront.net/img/warcraft/zones/zone-'.$arrLog['zone'].'-small.jpg');
					} else {
						$icon = 'https://dmszsuqyoe6y6.cloudfront.net/img/warcraft/zones/zone-'.$arrLog['zone'].'-small.jpg';
					}
					
					$arrOut[] = array(
							'id'		=> $arrLog['id'],
							'title'		=> $arrLog['title'],
							'icon'		=> $icon,
							'start'		=> round($arrLog['start']/1000),
							'link'		=> $url.'/reports/'.$arrLog['id'],
					);	
					$i++;
				}
				
			}
			
			$this->content = WCF::getTPL()->fetch('boxWarcraftlogs', 'wcf', ['warcraftlogs' => $arrOut]);
		}
		
	}
	
	/**
	 * Returns the link to fetch the image using the image proxy.
	 *
	 * @param	string		$link
	 * @return	string
	 * @since	3.0
	 */
	protected function getProxyLink($link) {
		try {
			$key = CryptoUtil::createSignedString($link);
			
			return LinkHandler::getInstance()->getLink('ImageProxy', [
					'key' => $key
			]);
		}
		catch (CryptoException $e) {
			return $link;
		}
	}
}
