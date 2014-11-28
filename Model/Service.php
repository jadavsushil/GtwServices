<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
class Service extends AppModel {
    public $belongsTo = 'User';
	public $validate = array(
        'title' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter Title'
            )           
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