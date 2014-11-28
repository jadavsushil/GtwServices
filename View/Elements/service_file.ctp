<div class="service-file">
	<a class="thumbnail" href="<?php echo $this->Html->url($name);?>" target='_blank'>
		<?php 
			$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
			if(in_array($ext,array('png','jpg','jpeg','gif','bmp'))){
                echo $this->Html->image($name,array('class'=>'img-responsive'));
            }elseif(in_array($ext,array('pdf'))){
                echo $this->Html->image('GtwServices.icons/pdf.png',array('class'=>'img-responsive'));
            }elseif(in_array($ext,array('zip','rar','tar'))){
                echo $this->Html->image('GtwServices.icons/zip.png',array('class'=>'img-responsive'));
            }elseif(in_array($ext,array('doc','docx'))){
                echo $this->Html->image('GtwServices.icons/doc.png',array('class'=>'img-responsive'));
            }elseif(in_array($ext,array('xls','xlsx'))){
                echo $this->Html->image('GtwServices.icons/excel.png',array('class'=>'img-responsive'));
            }elseif(in_array($ext,array('txt'))){
                echo $this->Html->image('GtwServices.icons/txt.png',array('class'=>'img-responsive'));
            }else{
                echo $this->Html->image('GtwServices.icons/files.png',array('class'=>'img-responsive'));
            }
		?>
	</a>
</div>
