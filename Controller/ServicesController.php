<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
 
class ServicesController extends AppController {
	public $name = 'Services';
	public $helpers = array('Text','Time','GtwStripe.Stripe');
	public $uses = array('GtwServices.Service','GtwServices.ServiceFile');

    public function beforeFilter(){
        $this->set('status',$this->Service->status);
		$this->Auth->allow('upload');
    }	
    public function index(){
		$conditions = array();
        if($this->Session->read('Auth.User.role')!='admin'){
			$conditions['Service.user_id'] = $this->Session->read('Auth.User.id');
		}
		$this->paginate = array(
			'Service' => array(
                'fields'=>array(
                    'Service.*',                    
                    'User.id',
                    'User.first',
                    'User.email',
                ),
				'conditions' => $conditions,
				'contain' => array(
					'UserModel'
				),
				'order' => 'Service.status ASC, Service.created DESC'
			)
		);
		$this->set('services', $this->paginate('Service'));
	}
    public function view($serviceId=0){
		if(!empty($this->request->named['transaction'])){
			$this->Transac = $this->Components->load('GtwStripe.Transac');
			$transaction = $this->Transac->getLastTransaction($this->request->named['transaction']);
			if(!empty($transaction)){			
				$this->Service->updateAll(
										array(
											'Service.transaction_id'=>'"'.$transaction['Transaction']['id'].'"',
											'Service.status' =>2
										),
										array('Service.id'=>$serviceId)
									);
				 $this->Session->setFlash(__('Thank you for payment, Your request will proceed soon.'), 'alert', array(
					'plugin' => 'BoostCake',
					'class' => 'alert-success'
				));
			}else{
				$this->Session->setFlash(__('Unable to process your payment request, Please try again'), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-danger'
			)); 
			}
		}
		$service = $this->Service->find('first',array(
				'fields'=>array(
						'Service.*','User.id',
						'User.first',
						'User.last',
						'User.email'
				),
				'conditions'=>array(
					'Service.id' =>$serviceId
				),
				'contain' => array(
					'UserModel'
				),
			));
        $this->set(compact('service','serviceId'));
    }  
	public function add(){
		if ($this->request->is('post')){
            $this->request->data['Service']['user_id'] = $this->Session->read('Auth.User.id');
            $this->Service->create();
            if ($this->Service->save($this->request->data)) {
                $this->Session->setFlash(__('The service request has been created successfully'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                /**
                 * Copy and Move uploaded Service Files
                 */
                $serviceId = $this->Service->id;
                $tmpServiceId = $this->Session->read('tmpServiceId');
				$oldPath = $this->setFilePath($tmpServiceId);
				$newPath = $this->setFilePath($serviceId);
                $tmpServiceFile = new Folder($oldPath['dir']);
                $tmpServiceFile->move(array('to'=>$newPath['dir'],'from'=>$oldPath['dir']));                
                //Save to File Table
                $arrServiceFiles = $this->Session->read('tmpServiceFiles');
                if(!empty($arrServiceFiles[$tmpServiceId])){
                    foreach($arrServiceFiles[$tmpServiceId] as $k=>$fileName){
                        $this->Service->ServiceFile->create();
                        $data = array(
                                    'service_id'=>$serviceId,
                                    'name'=>$fileName
                                );
                        $this->Service->ServiceFile->save(array('ServiceFile'=>$data));
                    }
                }
                $this->Session->delete('tmpServiceId');
                $this->Session->delete('tmpServiceFiles');
                
                return $this->redirect(array('action' => 'index'));
            }else{
				$this->Session->setFlash(__('Unable to create service request. Please, try again.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
			}
        }elseif(!$this->Session->check('tmpServiceId')){
            $this->Session->write('tmpServiceId',md5(microtime()));            
        }
        $this->set('serviceId',$this->Session->read('tmpServiceId'));
		$this->set('path',$this->setFilePath($this->Session->read('tmpServiceId')));
    }
	public function quote($serviceId=0) {
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['Service']['status'] = 1;
		}
		$this->_update($serviceId);
	}
    public function edit($serviceId=0) {
		if ($this->request->is('post') || $this->request->is('put')) {
			//Unset Price if anyone trying to change
			if(isset($this->request->data['Service']['price'])){
				unset($this->request->data['Service']['price']);
			}
		}
        $this->_update($serviceId);
    }
	private function _update($serviceId){
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
        $this->set('status',$this->Service->status);        
		$this->set('serviceId',$serviceId);
	}
    public function delete($serviceId=0) {
		if($this->Service->delete($serviceId)){
			$this->Session->setFlash(__('Service request has been deleted successfully'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
		}else{
			$this->Session->setFlash(__('Unable to delete service request, Please try again'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
		}
        $this->redirect($this->referer());
    }    
    public function complete($serviceId){
		$this->Service->id = $serviceId;
		$this->Service->saveField('status',3);
		$this->Session->setFlash(__('Service request has been marked as completed successfully '), 'alert', array(
				'plugin' => 'BoostCake',
				'class' => 'alert-success'
			));
        $this->redirect($this->referer());
    }
	function get_files($serviceId){
        $this->layout = 'ajax';
        //Get Files from Database
        if(is_numeric($serviceId)){
            $serviceFiles = $this->Service->ServiceFile->find('list',array('conditions'=>array('service_id'=>$serviceId),'recursive'=>-1));
            $this->set(compact('serviceFiles'));
        }
        $this->set(compact('serviceId'));
		$this->set('path',$this->setFilePath($serviceId));
    }
	function upload($serviceId=0) {
		$this->layout = "ajax";		
		if (!empty ($_FILES) && !empty($serviceId)){
			$path = $this->setFilePath($serviceId);
            $fileName = $this->ServiceFile->getUniqueFileName($_FILES ['Filedata'] ['name']);
			if (move_uploaded_file($_FILES ['Filedata'] ['tmp_name'], $path['dir'].$fileName)) {
                if(is_numeric($serviceId)){
                    $data = array(
                                'service_id'=>$serviceId,
                                'name'=>$fileName
                            );
                    $this->Service->ServiceFile->save(array('ServiceFile'=>$data));
                }else{
                    $arrData = $this->Session->check('tmpServiceFiles')?$this->Session->read('tmpServiceFiles'):array();
                    $arrData[$serviceId][] = $fileName;
                    $this->Session->write('tmpServiceFiles',$arrData);
                }
				echo true;
            }else{
                echo false;
            }
		}
		exit;
	}
	function setFilePath($serviceId=0){
		$arrPath = array();
		$path = WWW_ROOT.'files'.DS.'services'.DS.(is_numeric($serviceId)?$serviceId:'tmp'.DS.$serviceId).DS;
		if(!file_exists($path) && !is_dir($path)){
			mkdir($path,'0777',true);
		}
		$arrPath['dir'] = $path;
		
		$arrPath['url'] = "/files/services/".(is_numeric($serviceId)?$serviceId:'tmp/'.$serviceId)."/";
		return $arrPath;
	}
}