<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
?>
<div class="gtwServices">
	<?php if($this->Session->read('Auth.User.role') !='admin'):?>
	<div class='pull-right'>
		<?php echo $this->Html->link('<i class="fa fa-plus"> </i> '.__('New Service Request'),array('plugin'=>'gtw_services','controller'=>'service_categories','action'=>'select'),array('class'=>'btn btn-primary','data-target'=>'#service_request','data-toggle'=>'modal','escape'=>false,'title'=>__('New Service Request')));?>
	</div>	
	<?php endif;?>
	<h1><?php echo __('Service')?></h1>
	<table class="table table-hover table-bordered">
		<thead>
			<?php $colCount = 7?>
			<tr>
				<th width='2%'><?php echo $this->Paginator->sort('id'); ?></th>
				<?php if($this->Session->read('Auth.User.role')=='admin'){?>
					<th width='10%'><?php echo $this->Paginator->sort('service_category_id'); ?></th>
					<?php $colCount++;?>
				<?php }?>				
				<th width='22%'><?php echo $this->Paginator->sort('title'); ?></th>
				<?php if($this->Session->read('Auth.User.role')=='admin'){?>
					<th width='15%'><?php echo $this->Paginator->sort('User.first','Added By'); ?></th>
					<?php $colCount++;?>
				<?php }?>				
				<th width='8%'><?php echo $this->Paginator->sort('price'); ?></th>
				<th width='10%'><?php echo $this->Paginator->sort('status'); ?></th>
				<th width='12%'><?php echo $this->Paginator->sort('created', 'Date Added'); ?></th>
				<th width='12%'><?php echo $this->Paginator->sort('modified', 'Date Updated'); ?></th>
				<th width='10%' class='text-center'><?php echo __('Action')?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(empty($services)){?>
				<tr>
					<td colspan='<?php echo $colCount;?>' class='text-warning'><?php echo __('No record found.')?></td>
				</tr>
			<?php 
				}else{
					foreach ($services as $service){
						$class='warning';
						if($service['Service']['status']==1){
							$class='info';
						}elseif($service['Service']['status']==2){
							$class='success';
						}elseif($service['Service']['status']==3){
							$class='complete';
						}
			?>
						<tr class="<?php echo $class;?>">
							<td><?php echo $service['Service']['id']?></td>
							<?php if($this->Session->read('Auth.User.role')=='admin'){?>
								<td><?php echo $service['ServiceCategory']['name'];?></td>
							<?php }?>
							<td><?php echo $service['Service']['title'];?></td>
							<?php if($this->Session->read('Auth.User.role')=='admin'){?>
								<td><?php echo $service['User']['first'].' <small>('.$service['User']['email'].')</small>';?></td>
							<?php }?>
							<td class='text-right'>
								<?php 
									if($service['Service']['status']>0){
										echo $this->Stripe->showPrice($service['Service']['price']);
									}else{
										echo __("N/A");
									}
								?>
							</td>
							<td><?php echo $status[$service['Service']['status']]?></td>
							<td><?php echo $this->Time->format('Y-m-d H:i:s', $service['Service']['created']); ?></td>
							<td><?php echo $this->Time->format('Y-m-d H:i:s', $service['Service']['modified']); ?></td>
							<td class="text-center actions">
								<?php 
									echo $this->Html->actionIcon('fa fa-th', 'view', $service['Service']['id']);									
									if($service['Service']['status']<3){
										echo '&nbsp|&nbsp';
										echo $this->Html->actionIcon('fa fa-pencil', 'edit', $service['Service']['id']);
									}
									if($this->Session->read('Auth.User.role')=='admin'){
										if($service['Service']['status']<2){											
											echo '&nbsp|&nbsp';
											echo $this->Html->link('<i class="fa fa-trash-o"> </i>',array('controller'=>'services','action'=>'delete',$service['Service']['id']),array('role'=>'button','escape'=>false,'title'=>__('Delete this Request')),__('Are you sure? You want to delete this Request.'));
											echo '&nbsp|&nbsp';
											echo $this->Html->actionIcon('fa fa-money', 'quote', $service['Service']['id']);
										}elseif($service['Service']['status']==2){
											echo '&nbsp|&nbsp';
											echo $this->Html->link('<i class="fa fa-tags"> </i>',array('controller'=>'services','action'=>'complete',$service['Service']['id']),array('role'=>'button','escape'=>false,'title'=>__('Mark this service request as Complete')),__("Are you sure?\n You want to mark this service request as Complete."));
										}										
									}else{
										if($service['Service']['status']==0){
											echo '&nbsp|&nbsp';
											echo $this->Html->link('<i class="fa fa-trash-o"> </i>',array('controller'=>'services','action'=>'delete',$service['Service']['id']),array('role'=>'button','escape'=>false,'title'=>__('Delete this Request')),__('Are you sure? You want to delete this Request.'));
										}elseif($service['Service']['status']==1){
											echo '&nbsp|&nbsp';
											echo $this->Html->actionIcon('fa fa-cog fa-spin', 'view', $service['Service']['id']);
										}
									}									
								?>
							</td>
						</tr>
			<?php
					}
				}
			?>
		</tbody>
	</table>		
	<div class="bginfo">
		<div class="col-md-2 bg-warning"><?php echo __('Pending')?></div>
		<div class="col-md-2 bg-info"><?php echo __('Service Request')?></div>
		<div class="col-md-2 bg-success"><?php echo __('Accepted')?></div>
		<div class="col-md-2 complete"><?php echo __('Completed')?></div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="service_request" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					 <h4 class="modal-title"><?php echo __("New Service Request")?></h4>
				</div>
				<div class="modal-body">
					<i class="fa fa-refresh fa-spin"></i> <?php echo __('Please wait...');?>
				</div>			
			</div> <!-- /.modal-content -->
		</div> <!-- /.modal-dialog -->
	</div> <!-- /.modal -->
</div>