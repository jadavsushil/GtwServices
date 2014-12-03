<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
class ServiceFile extends AppModel {
	var $name = 'ServiceFile';
	public $displayField = 'file';
	var $belongsTo = array(
		'Service' => array(
			'className' => 'Service',
			'foreignKey' => 'service_id'
		)
	);    
	function getUniqueFileName($fileName){
		$prefix = 'service';
		$ext = pathinfo($fileName, PATHINFO_EXTENSION);
		return (!empty($prefix)?$prefix.'_':'').md5(microtime()).'.'.$ext;
	}
}