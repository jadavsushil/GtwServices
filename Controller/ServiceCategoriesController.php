<?php

/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
class ServiceCategoriesController extends AppController
{

    public $name = 'ServiceCategories';
    public $helpers = array('Text', 'Time', 'GtwStripe.Stripe');

    public function beforeFilter()
    {
        $this->set('status', $this->ServiceCategory->status);
    }

    public function index()
    {
        $this->paginate = array(
            'ServiceCategory' => array(
                'order' => 'ServiceCategory.id DESC',
                'conditions' => array(
                    'ServiceCategory.status <>' => 2
                )
            )
        );
        $this->set('serviceCategories', $this->paginate('ServiceCategory'));
    }

    public function select()
    {
        $this->layout = 'ajax';
        $this->paginate = array(
            'ServiceCategory' => array(
                'order' => 'ServiceCategory.id DESC',
                'conditions' => array(
                    'ServiceCategory.status' => 1,
                    'ServiceCategory.is_custom' => 0
                )
            )
        );
        $this->set('serviceCategories', $this->paginate('ServiceCategory'));
    }

    public function view($serviceCategoryId = 0)
    {
        $serviceCategory = $this->ServiceCategory->find('first', array(
            'conditions' => array(
                'ServiceCategory.id' => $serviceCategoryId
            ),
            'contain' => array(
                'UserModel'
            ),
        ));
        $this->set(compact('serviceCategory', 'serviceCategoryId'));
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->ServiceCategory->create();
            if ($this->ServiceCategory->save($this->request->data)) {
                $this->Session->setFlash(__('The service category has been created successfully'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Unable to create service category. Please, try again.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
            }
        }
    }

    public function edit($serviceCategoryId = 0)
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['ServiceCategory']['id'] = $serviceCategoryId;
            if ($this->ServiceCategory->save($this->request->data)) {
                $this->Session->setFlash(__('The service category has been updated successfully'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('The service category could not be update. Please, try again.'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        } else {
            $this->request->data = $this->ServiceCategory->read(null, $serviceCategoryId);
        }
    }

    public function delete($serviceCategoryId = 0)
    {
        $this->ServiceCategory->recursive = -1;
        $serviceCategory = $this->ServiceCategory->findById($serviceCategoryId);
        if (!empty($serviceCategory) && empty($serviceCategory['ServiceCategory']['is_custom'])) {
            $serviceCategory['ServiceCategory']['status'] = 2;
            if ($this->ServiceCategory->save($serviceCategory)) {
                $this->Session->setFlash(__('Service category has been deleted successfully'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                $this->redirect($this->referer());
            }
        }
        $this->Session->setFlash(__('Unable to delete service category, Please try again'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
        $this->redirect($this->referer());
    }

}
