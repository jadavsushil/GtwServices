<?php

/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class ServicesController extends AppController
{

    public $name = 'Services';
    public $helpers = array('Text', 'Time', 'GtwStripe.Stripe');
    public $uses = array('GtwServices.Service', 'GtwServices.ServiceFile', 'GtwServices.ServiceCategory');

    public function beforeFilter()
    {
        $this->set('status', $this->Service->status);
        $this->Auth->allow('upload');
    }

    public function index()
    {
        $conditions = array();
        if ($this->Session->read('Auth.User.role') != 'admin') {
            $conditions['Service.user_id'] = $this->Session->read('Auth.User.id');
        }
        $this->paginate = $this->ServiceFile->setPagination($conditions);
        $this->set('services', $this->paginate('Service'));
    }

    public function view($serviceId = 0)
    {
        if (!empty($this->request->named['transaction'])) {
            $this->Transac = $this->Components->load('GtwStripe.Transac');
            $transaction = $this->Transac->getLastTransaction($this->request->named['transaction']);
            $this->Service->updateTransactions($transaction);
        }
        $service = $this->Service->findServiceById($serviceId);
        $this->set(compact('service', 'serviceId'));
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $response = $this->Service->addNewService($this->request->data);
            if (!empty($response)) {
                if ($response['status'] == 'success') {
                    $this->Session->setFlash($response['message'], 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    ));
                    return $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash($response['message'], 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-danger'
                    ));
                }
            }
        } elseif (!$this->Session->check('tmpServiceId')) {
            $this->Session->write('tmpServiceId', md5(microtime()));
        }
        $this->set('serviceId', $this->Session->read('tmpServiceId'));
    }

    public function request($serviceCategoryId)
    {
        if (!empty($this->request->named['transaction'])) {
            $this->Transac = $this->Components->load('GtwStripe.Transac');
            $transaction = $this->Transac->getLastTransaction($this->request->named['transaction']);
            if (!empty($transaction)) {
                $this->ServiceCategory->recursive = -1;
                $category = $this->ServiceCategory->findById($serviceCategoryId);
                $this->Service->addNewTransaction($transaction, $category);
                $this->Session->setFlash(__('Thank you for payment, Your request will proceed soon.<br>Upload your files'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                $this->redirect(array('plugin' => 'gtw_services', 'controller' => 'services', 'action' => 'edit', $this->Service->id));
            }
        }
        $this->Session->setFlash(__('Unable to process your payment request, Please try again'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
        $this->redirect($this->referer());
    }

    public function quote($serviceId = 0)
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Service']['status'] = 1;
        }
        $this->_update($serviceId);
    }

    public function edit($serviceId = 0)
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            //Unset Price if anyone trying to change
            if (isset($this->request->data['Service']['price'])) {
                unset($this->request->data['Service']['price']);
            }
        }
        $this->_update($serviceId);
    }

    private function _update($serviceId)
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Service']['id'] = $serviceId;
            if ($this->Service->save($this->request->data)) {
                $this->Session->setFlash(__('The service request has been updated successfully'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('The service request could not be update. Please, try again.'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        } else {
            $this->request->data = $this->Service->read(null, $serviceId);
        }
        $this->set('status', $this->Service->status);
        $this->set('serviceId', $serviceId);
    }

    public function delete($serviceId = 0)
    {
        if ($this->Service->delete($serviceId)) {
            $this->Session->setFlash(__('Service request has been deleted successfully'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
        } else {
            $this->Session->setFlash(__('Unable to delete service request, Please try again'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }
        $this->redirect($this->referer());
    }

    public function complete($serviceId)
    {
        $this->Service->id = $serviceId;
        $this->Service->saveField('status', 3);
        $this->Session->setFlash(__('Service request has been marked as completed successfully '), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-success'
        ));
        $this->redirect($this->referer());
    }

    public function get_files($serviceId)
    {
        $this->layout = 'ajax';
        //Get Files from Database
        if (is_numeric($serviceId)) {
            $serviceFiles = $this->ServiceFile->getServiceFiles($serviceId);
            $this->set(compact('serviceFiles'));
        } else {
            $arrServiceFiles = $this->Session->read('tmpFileId');
            if (!empty($arrServiceFiles[$serviceId])) {
                $serviceFiles = $this->ServiceFile->getFiles($arrServiceFiles[$serviceId]);
                $this->set(compact('serviceFiles'));
            }
        }
        $this->set(compact('serviceId'));
        $this->set('path', $this->ServiceFile->getPath());
    }

    public function upload($serviceId = 0, $fileId = array())
    {
        if (!empty($this->request->data['id'])) {
            $serviceId = $this->request->data['id'];
        }
        if (!empty($this->request->data['file_id'])) {
            $fileId = $this->request->data['file_id'];
        }
        $response = $this->ServiceFile->uploadFiles($serviceId, $fileId);
        return new CakeResponse(array(
            'body' => json_encode(array(
                'message' => $response['message'],
                'success' => $response['status'],
            )),
            'status' => 200
        ));
        exit;
    }
}
