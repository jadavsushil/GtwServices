<div>
    <?php 
        $flag = false;
        if(isset($orderFiles)){
            foreach($orderFiles as $id=>$file){
                echo $this->element('service_file',array('name'=>$path['url'].$file));
                $flag = true;
            }
        }else{
            foreach(scandir($path['dir']) as $k=>$file){
                if(!in_array($file,array('.','..','.svn')) && !is_dir($path['dir'].DS.$file)){
                    echo $this->element('service_file',array('name'=>$path['url'].$file));
                    $flag = true;
                }
            }
        }
        if(!$flag){
            echo 'No file uploaded yet.';
        }
    ?>
</div>