<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Gregory Loichot <gloichot@cross-systems.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'Cross Google AdSense' for the 'crossadsens' extension.
 *
 * @author	Gregory Loichot <gloichot@cross-systems.com>
 * @package	TYPO3
 * @subpackage	tx_crossadsens
 */
class tx_crossadsens_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_crossadsens_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_crossadsens_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'crossadsens';	// The extension key.
	var $pi_checkCHash = true;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main ($content, $conf)	{
		$this->conf = $conf;
		$this->pi_USER_INT_obj = 1;    	// Configuring so caching is not expected. This value means that no cHash params are ever set. We do this, because it's a USER_INT object!
		$this->pi_loadLL();
		$this->pi_initPIflexForm(); 	// Init and get the flexform data of the plugin
		
		return $this->generateJS();
	}
	
	protected function & generateJS () {
		/*---------------------------- TEMPLATE HTML ----------------------------*/
		// Chargement du template
		$this->templateCode	= $this->cObj->fileResource($this->conf['templateFile']);
		
		// Début du template
		$template['total'] = $this->cObj->getSubpart($this->templateCode,'###TEMPLATE_ADSENSE###');

		/*---------------------------- FLEXFORM ----------------------------*/
		// Récupération des données du flexform
		$piFlexForm = $this->cObj->data['pi_flexform'];

		$googleIdTS = $this->conf['adId'];
		$googleId	= $piFlexForm['data']['sDEF']['lDEF']['googleAdId']['vDEF'];
		$pubId		= $piFlexForm['data']['sDEF']['lDEF']['googleAdPubId']['vDEF'];
		$width		= $piFlexForm['data']['sDEF']['lDEF']['googleAdWidth']['vDEF'];
		$height		= $piFlexForm['data']['sDEF']['lDEF']['googleAdHeight']['vDEF'];
		
		/*---------------------------- DECISION D'AFFICHAGE ----------------------------*/
		// Si l'id Google n'est ni défini dans le TS ni dans le FF, on affiche rien.
		if ($googleId == ''&& $googleIdTS != '') {
			$googleId = $googleIdTS;
		}
		elseif ($googleId == ''&& $googleIdTS == '') {
			return $googleId .' '. $googleIdTS;	
		}
		
		// Si l'id de l'annonce n'est pas défini, on affiche rien.
		if ($pubId == '') {
			return '';
		}
		
		/*---------------------------- Génération du script ----------------------------*/
		$js = '	<script type="text/javascript">
					<!--
					google_ad_client	= "'. $googleId .'";
					google_ad_slot		= "'. $pubId .'";
					google_ad_width		= '. $width .';
					google_ad_height	= '. $height .'
					//-->
				</script>
				<script type="text/javascript"
					src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
				</script>';
		
		/*---------------------------- REMPLASSAGE TEMPLATE ----------------------------*/
		$markerArray['###JS_ADSENSE###'] = $js;

		return $this->cObj->substituteMarkerArray($template['total'],$markerArray);
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/crossadsens/pi1/class.tx_crossadsens_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/crossadsens/pi1/class.tx_crossadsens_pi1.php']);
}

?>