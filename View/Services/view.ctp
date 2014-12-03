<div class="gtwServices">
	<div class="pull-right">
		<?php 
			if(($this->Session->read('Auth.User.role') =='admin' && $service['Service']['status']<3) || $service['Service']['status']==0){
				echo $this->Html->actionIconBtn('fa fa-pencil',' Edit This','edit',$service['Service']['id'],'btn-primary');
				echo '&nbsp';
			}
			if($this->Session->read('Auth.User.role') !='admin' && $service['Service']['status']==1){
				echo $this->element('GtwStripe.one_time_fix_payment',array('options'=>array(
																		'description'=>$service['Service']['title'],
																		'amount'=>$service['Service']['price'],
																		'label'=>__('Pay %s Now',$this->Stripe->showPrice($service['Service']['price'])),
																		'success-url'=>$this->Html->url(array('plugin'=>'gtw_services','action'=>'view',$service['Service']['id'],'type'=>'success'),true),
																		'fail-url'=>$this->Html->url(array('plugin'=>'gtw_services','action'=>'view',$service['Service']['id'],'type'=>'fail'),true),
															)));
			}					
		?>
	</div>
	<h1><?php echo __('Service Request Detail');?></h1>
	<div class="row">
		<div class="col-md-6">
			<h4><?php echo $service['Service']['title']?>&nbsp;</h4>
			<?php if($service['Service']['status']>0):?>
				<div>
					<?php echo __('Price').' '.$this->Stripe->showPrice($service['Service']['price']);?>
				</div>
			<?php endif;?>
			<div> 
				<?php echo __('Status').': '.$status[$service['Service']['status']];?>
			</div>
			<div> 
				<?php echo __('Created On').': '.$this->Time->format('Y-m-d H:i:s', $service['Service']['created']);?>
			</div>
			<div> 
				<?php echo __('Updated On').': '.$this->Time->format('Y-m-d H:i:s', $service['Service']['modified']);?>
			</div>
			<div><?php echo $service['Service']['description']?>&nbsp;</div>
			<div>
				<h4><?php echo __("Service Files")?></h4>
				<hr>
				<div  id='uploadedServiceFiles'>
					<i class="fa fa-refresh fa-spin"></i> <?php echo __('Loading Files')?>							
					<div style='clear:both'></div>
				</div>
				<div class='c'></div>
			</div>
		</div>
		<div class="col-md-6">
			<?php if (CakePlugin::loaded('GtwComments')):?>
				<div class="egoodComments hidden-sm">
					<h3 class="discuss"><?php echo __('Discussion')?></h3>
					<?php echo $this->element('GtwComments.comment',array('model'=>'Service','refId'=>$service['Service']['id']));?>
				</div>                    
			<?php endif;?>
		</div>
	</div>
</div>
<?php echo $this->element('GtwServices.uploadify',array('serviceId'=>$service['Service']['id']))?>