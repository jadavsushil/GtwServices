<?php

/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
class Service extends AppModel
{

    var $name = 'Service';
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

    public function findServiceById($serviceId = null)
    {
        $service = $this->find('first', array(
            'fields' => array(
                'Service.*', 'User.id',
                'User.first',
                'User.last',
                'User.email'
            ),
            'conditions' => array(
                'Service.id' => $serviceId
            ),
            'contain' => array(
                'UserModel'
            ),
        ));
        return $service;
    }

    public function updateTransactions($transaction)
    {
        if (!empty($transaction)) {
            $this->updateAll(
                    array(
                'Service.transaction_id' => '"' . $transaction['Transaction']['id'] . '"',
                'Service.status' => 2
                    ), array('Service.id' => $serviceId)
            );
            $this->Session->setFlash(__('Thank you for payment, Your request will proceed soon.'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
        } else {
            $this->Session->setFlash(__('Unable to process your payment request, Please try again'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }
    }

    public function addNewService($data)
    {
        $data['Service']['user_id'] = CakeSession::read('Auth.User.id');
        $arrResponse = array('status' => 'fail', 'message' => 'Unable to create service request. Please, try again.');
        $this->create();
        if ($this->save($data)) {
            $arrResponse = array('status' => 'success', 'message' => 'The service request has been created successfully');
            $serviceId = $this->id;
            $tmpServiceId = CakeSession::read('tmpServiceId');
            $arrServiceFiles = CakeSession::read('tmpFileId');
            if (!empty($arrServiceFiles[$tmpServiceId])) {
                App::import('Model', 'GtwServices.ServiceFile');
                $this->ServiceFile = new ServiceFile();
                foreach ($arrServiceFiles[$tmpServiceId] as $k => $fileId) {
                    $this->ServiceFile->create();
                    $data = array(
                        'service_id' => $serviceId,
                        'file_id' => $fileId
                    );
                    $this->ServiceFile->save(array('ServiceFile' => $data));
                }
            }
        }
        CakeSession::delete('tmpServiceId');
        CakeSession::delete('tmpFileId');
        return $arrResponse;
    }

    public function addNewTransaction($transaction, $category)
    {
        $arrService = array(
            'user_id' => CakeSession::read('Auth.User.id'),
            'service_category_id' => $category['ServiceCategory']['id'],
            'title' => $category['ServiceCategory']['name'],
            'description' => $category['ServiceCategory']['description'],
            'price' => $category['ServiceCategory']['price'],
            'status' => 2,
            'transaction_id' => $transaction['Transaction']['id'],
        );
        $this->save(array('Service' => $arrService), false);
    }

}
