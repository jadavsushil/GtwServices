<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			 <h4 class="modal-title"><?php echo __("New Service Request")?></h4>
		</div>
		<div class="modal-body">
			<div class="gtwServices">
				<div class="row">
					<div class='col-md-12 text-center'>
						<?php echo $this->Html->actionIconBtn('fa fa-plus',' '.__('Custom Service Request'),'add',array('controller'=>'services','plugin'=>'gtw_services'),'btn-primary'); ?>
					</div>
				</div>
				<div class="row">
					<div class='col-md-12 text-center separator'>
						<h3><?php echo __('OR')?></h3>
						<hr>				
					</div>
				</div>
				<h3><?php echo __('Select from our service list')?></h3>
				<div class="row selectcategory">
					<?php if(empty($serviceCategories)){?>
						<?php echo __('No record found.')?>
					<?php 
						}else{
							foreach ($serviceCategories as $serviceCategory){							
					?>
					<div class="col-sm-6 col-md-6">	
						<div class="thumbnail">
							<div class="caption">
								<div class='detail'>
									<h4 title="<?php echo $serviceCategory['ServiceCategory']['name']?>"><?php echo $serviceCategory['ServiceCategory']['name']?></h4>
									<h3><?php echo $this->Stripe->showPrice($serviceCategory['ServiceCategory']['price']);?></h3>						
								</div>
								<p>
								  <?php echo $this->Html->link(__('View Detail'),array('plugin'=>'gtw_services','controller'=>'service_categories','action'=>'view',$serviceCategory['ServiceCategory']['id']),array('title'=>__('View Service'),'class'=>'btn btn-success btn-block'))?>							
								</p>
							</div>
						</div>
					</div>
					<?php 
							}
						}
					?>			
				</div>	
			</div>
		</div>
	</div>
</div>