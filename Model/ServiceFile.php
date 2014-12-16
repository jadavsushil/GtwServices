<?php

/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
class ServiceFile extends AppModel
{

    var $name = 'ServiceFile';
    var $belongsTo = array(
        'Service' => array(
            'className' => 'Service',
            'foreignKey' => 'service_id'
        )
    );

    function getUniqueFileName($fileName)
    {
        $prefix = 'service';
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        return (!empty($prefix) ? $prefix . '_' : '') . md5(microtime()) . '.' . $ext;
    }

    function getServiceFiles($serviceId)
    {
        //Load File Model From GtwFiles Plugin
        App::import('Model', 'GtwFiles.File');
        $this->File = new File();

        $this->File->bindModel(array('hasOne' => array('ServiceFile')));

        return $this->File->find('all', array(
                    'fields' => array(
                        'File.id', 'File.title', 'File.filename', 'File.dir'
                    ),
                    'conditions' => array(
                        'ServiceFile.service_id' => $serviceId
                    )
        ));
    }

    public function getFiles($fileId = [])
    {
        App::import('Model', 'GtwFiles.File');
        $this->File = new File();
        return $this->File->find('all', array(
                    'fields' => array(
                        'File.id', 'File.title', 'File.filename'
                    ),
                    'conditions' => array(
                        'File.id' => $fileId
                    )
        ));
    }

    public function getPath($filename = '') {
        return DS . 'files' . DS . 'uploads' . DS . $filename;
    }

    public function setPagination($conditions = [])
    {
        $this->paginate = array(
            'Service' => array(
                'fields' => array(
                    'Service.*',
                    'ServiceCategory.id',
                    'ServiceCategory.name',
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
        return $this->paginate;
    }

    public function uploadFiles($serviceId, $fileId)
    {
        $newFileIds = explode(', ', $fileId);
        $arrResponse = array('status' => false, 'message' => 'Unable to upload file. Please, try again.');
        if (is_numeric($serviceId)) {
            $serviceFile['service_id'] = $serviceId;
            foreach ($newFileIds as $key => $fileId) {
                $serviceFile['file_id'] = $fileId;
                $this->create();
                if ($this->save($serviceFile)) {
                    $arrResponse = array('status' => true, 'message' => 'file has been uploaded successfully.');
                }
            }
            
        } else {
            $arrResponse = array('status' => true, 'message' => 'file has been uploaded successfully.');
            foreach ($newFileIds as $key => $fileId) {
                if (CakeSession::check('tmpFileId.' . $serviceId)) {
                    $oldFileId = CakeSession::read('tmpFileId.' . $serviceId);
                    $oldFileId[] = $fileId;
                    CakeSession::write('tmpFileId.' . $serviceId, $oldFileId);
                } else {
                    CakeSession::write('tmpServiceId', $serviceId);
                    $newFile[$serviceId][] = $fileId;
                    CakeSession::write('tmpFileId', $newFile);
                }
            }
        }
        return $arrResponse;
    }

}
