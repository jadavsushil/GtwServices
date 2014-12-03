<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */

class ServiceCategory extends AppModel {
	var $name = 'ServiceCategory';
	
	var $hasMany = array(
		'Service' => array(
			'className' => 'Service',
			'foreignKey' => 'service_category_id',
		));
	public function setDefaultData(){
		$check = $this->find('count');
		if(empty($check)){
			$arrDefaultData = array(
				array(
						'id'		=>1,
						'name'		=>__('Custom Service'),
						'is_custom'	=>1,
					),
			);
			foreach($arrDefaultData as $k=>$data){
				$this->create();
				$this->save($data);
			}
		}
	}
	    public $status = array(
                        1 => 'Publish', 
                        0 => 'Unpublish'
                    );
}