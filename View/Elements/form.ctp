<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
?>
<div class="gtwServices">
	<h1><?php echo $title?></h1>
	<div class="row">
		<div class="<?php echo $type=='add'?'col-md-12':'col-md-6'?>">				
			<?php echo $this->Form->create('Service', array('type'=>'file','inputDefaults' => array('div' => 'col-md-12 form-group','class' => 'form-control'),'class' => 'form-horizontal','id'=>'ServiceAddEditForm', 'novalidate'=>'novalidate')); ?>
				<?php $readOnly = (!empty($this->request->data['Service']['service_category_id']) && $this->request->data['Service']['service_category_id']!=1)?'disabled':'';?>
				<?php echo $this->Form->input('title',array(
					'type'=>'text',
					$readOnly
				)); ?>
				<?php echo $this->Form->input('description',array(
					'class' =>'wysiwyg',
					'style' => 'width:100%;height:60px;',
					$readOnly
				)); ?>
				<?php 
					if($type=='quote' && $this->Session->read('Auth.User.role')=='admin'){
						echo $this->Form->input('price',array(
							'type'=>'number','min'=>0
						)); 
					}
				?>
				<div class="col-md-12 form-group">
					<label for="ServicePhoto"><?php echo __('Files')?></label>
					<div class="input file">						
						<input type="file" id="serviceFiles" name="serviceFiles" />
						<hr>
						<div  id='uploadedServiceFiles'>
							<i class="fa fa-refresh fa-spin"></i> <?php echo __('Loading Files')?>							
						</div>
					</div>
				</div>
				<?php echo $this->Form->submit($title, array(
					'div' => false,
					'class' => 'btn btn-primary'
				)); ?>
				<?php echo $this->Html->actionBtn(__('Cancel'), 'index'); ?>
			<?php echo $this->Form->end(); ?>
		</div>
		<?php if($type!='add'){?>
			<div class="col-md-6">
				<?php if (CakePlugin::loaded('GtwComments')):?>
					<div class="hidden-sm">
						<h3 class="discuss"><?php echo __('Discussion')?></h3>
						<?php echo $this->element('GtwComments.comment',array('model'=>'Service','refId'=>$serviceId));?>
					</div>                    
				<?php endif;?>
			</div>
		<?php }?>
	</div>		
</div>
<?php echo $this->element('GtwServices.uploadify',array('serviceId'=>$serviceId))?>