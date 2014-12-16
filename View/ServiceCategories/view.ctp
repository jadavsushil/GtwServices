<div class="gtwServices">            
    <div class="pull-right">
        <?php
            if($this->Session->read('Auth.User.role') =='admin'){
                echo $this->Html->actionIconBtn('fa fa-pencil',__(' Edit This'),'edit',$serviceCategory['ServiceCategory']['id'],'btn-primary');
            }else{
                echo $this->element('GtwStripe.one_time_fix_payment',array('options'=>array(
                                                                        'description'=>$serviceCategory['ServiceCategory']['name'],
                                                                        'amount'=>$serviceCategory['ServiceCategory']['price'],
                                                                        'label'=>__('Request this Service & Pay %s Now',$this->Stripe->showPrice($serviceCategory['ServiceCategory']['price'])),
                                                                        'success-url'=>$this->Html->url(array('plugin'=>'gtw_services','controller'=>'services','action'=>'request',$serviceCategory['ServiceCategory']['id'],'type'=>'success'),true),
                                                                        'fail-url'=>$this->Html->url(array('plugin'=>'gtw_services','controller'=>'service_categories','action'=>'view',$serviceCategory['ServiceCategory']['id'],'type'=>'fail'),true),
                                                            )));
            }
        ?>
    </div>
    <h1><?php echo __('Service Detail');?></h1>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <th class="col-md-2"><?php echo __('Name')?></th>
                    <td class="col-md-10"><?php echo $serviceCategory['ServiceCategory']['name']?>&nbsp;</td>
                </tr>
                <?php if(!$serviceCategory['ServiceCategory']['is_custom']):?>
                    <tr>
                        <th class="col-md-2"><?php echo __('Price')?></th>
                        <td class="col-md-10"><?php echo $this->Stripe->showPrice($serviceCategory['ServiceCategory']['price']);?>&nbsp;</td>
                    </tr>
                <?php endif;?>
                <?php if($this->Session->read('Auth.User.role') =='admin'):?>
                    <tr>
                        <th class="col-md-2"><?php echo __('Total Services')?></th>
                        <td class="col-md-10"><?php echo $serviceCategory['ServiceCategory']['service_count']?>&nbsp;</td>
                    </tr>                                                    
                    <tr>
                        <th class="col-md-2"><?php echo __('Status')?></th>
                        <td class="col-md-10"><?php echo $status[$serviceCategory['ServiceCategory']['status']]?>&nbsp;</td>
                    </tr>
                    <tr>
                        <th class="col-md-2"><?php echo __('Added On')?></th>
                        <td class="col-md-10"><?php echo $this->Time->format('Y-m-d H:i:s', $serviceCategory['ServiceCategory']['created'])?>&nbsp;</td>
                    </tr>
                    <tr>
                        <th class="col-md-2"><?php echo __('Updated On')?></th>
                        <td class="col-md-10"><?php echo $this->Time->format('Y-m-d H:i:s', $serviceCategory['ServiceCategory']['modified'])?>&nbsp;</td>
                    </tr>
                <?php endif;?>
                <tr>
                    <th class="col-md-2"><?php echo __('Description')?></th>
                    <td class="col-md-10"><?php echo $serviceCategory['ServiceCategory']['description']?>&nbsp;</td>
                </tr>
            </table>
        </div>                
    </div>
</div>
