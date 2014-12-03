<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
class Service extends AppModel {
	public $validate = array(
        'title' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter Title'
            )           
        )
    );  
	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'ServiceCategory' => array(
			'className' => 'ServiceCategory',
			'foreignKey' => 'service_category_id',
			'counterCache' => true
		)
	);
	var $hasMany = array(
		'ServiceFile' => array(
			'className' => 'ServiceFile',
			'foreignKey' => 'service_id',
		));
		
    public $status = array(
                        0 => 'Pending', 
                        1 => 'Service Request',
                        2 => 'Accepted',
                        3 => 'Completed',
                    );
}