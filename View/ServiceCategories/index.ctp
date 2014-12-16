<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
?>
<div class="gtwServices">
    <div class="pull-right">
        <?php echo $this->Html->actionIconBtn('fa fa-plus',' '.__('New Category'),'add',array(),'btn-primary'); ?>
    </div>
    <h1><?php echo __('Service Category')?></h1>
    <table class="table table-hover table-striped table-bordered">
        <thead>
            <tr>
                <th width='2%'><?php echo $this->Paginator->sort('id'); ?></th>
                <th width='25%'><?php echo $this->Paginator->sort('name'); ?></th>
                <th width='10%'><?php echo $this->Paginator->sort('price'); ?></th>
                <th width='10%'><?php echo $this->Paginator->sort('service_count',__('# Services')); ?></th>
                <th width='10%'><?php echo $this->Paginator->sort('status'); ?></th>
                <th width='12%'><?php echo $this->Paginator->sort('created', 'Added On'); ?></th>
                <th width='12%'><?php echo $this->Paginator->sort('modified', 'Date Updated'); ?></th>
                <th width='10%' class='text-center'><?php echo __('Action')?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($serviceCategories)){?>
                <tr>
                    <td colspan='7' class='text-warning'><?php echo __('No record found.')?></td>
                </tr>
            <?php 
                }else{
                    foreach ($serviceCategories as $serviceCategory){                            
            ?>
                        <tr>
                            <td><?php echo $serviceCategory['ServiceCategory']['id']?></td>
                            <td><?php echo $serviceCategory['ServiceCategory']['name']?></td>
                            <td class='text-right'>
                                <?php 
                                    if(!$serviceCategory['ServiceCategory']['is_custom']){
                                        echo $this->Stripe->showPrice($serviceCategory['ServiceCategory']['price']);
                                    }else{
                                        echo __("N/A");
                                    }
                                ?>
                            </td>
                            <td class="text-center"><?php echo $serviceCategory['ServiceCategory']['service_count']?></td>                                
                            <td><?php echo $status[$serviceCategory['ServiceCategory']['status']]?></td>
                            <td><?php echo $this->Time->format('Y-m-d H:i:s', $serviceCategory['ServiceCategory']['created']); ?></td>
                            <td><?php echo $this->Time->format('Y-m-d H:i:s', $serviceCategory['ServiceCategory']['modified']); ?></td>
                            <td class="text-center actions">
                                <?php 
                                    echo $this->Html->actionIcon('fa fa-th', 'view', $serviceCategory['ServiceCategory']['id']);
                                    echo "&nbsp;|&nbsp;";
                                    echo $this->Html->actionIcon('fa fa-pencil', 'edit', $serviceCategory['ServiceCategory']['id']);
                                    if(!$serviceCategory['ServiceCategory']['is_custom']){
                                        echo "&nbsp;|&nbsp;";
                                        echo $this->Html->link('<i class="fa fa-trash-o"> </i>',array('controller'=>'service_categories','action'=>'delete',$serviceCategory['ServiceCategory']['id']),array('role'=>'button','escape'=>false,'title'=>__('Delete this Service Category')),__('Are you sure? You want to delete this Service Category.'));
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
</div>
