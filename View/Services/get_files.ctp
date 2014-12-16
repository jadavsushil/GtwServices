<div>
    <?php
        if(isset($serviceFiles)){
            foreach($serviceFiles as $id=>$file){
                echo $this->element('service_file',array('path' =>$path,'dir'=>$file['File']['dir'],'name'=>$file['File']['filename']));
            }
        } else {
            echo 'No file uploaded yet.';
        }
    ?>
</div>
