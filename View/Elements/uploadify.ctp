<script type="text/javascript">
    // <![CDATA[
	var uploadifySetting = {};
	uploadifySetting.uploader	= '<?php echo $this->Html->url('GtwService/swf/uploadify.swf');?>';
	uploadifySetting.script 	= '<?php echo $this->Html->url(array('plugin'=>'gtw_services','controller'=>'services','action'=>'upload',$serviceId));?>';
	uploadifySetting.buttonText = '<?php echo __('Select Files')?>';
	uploadifySetting.getFilesUrl = '<?php echo $this->Html->url(array('plugin'=>'gtw_services','controller'=>'services','action'=>'get_files',$serviceId));?>';
    // ]]>
</script>
<?php 
$this->Helpers->load('GtwRequire.GtwRequire');
$this->GtwRequire->req('ui/wysiwyg');
echo $this->GtwRequire->req($this->Html->url('/',true).'GtwServices/js/form.js');
echo $this->Html->script(array('GtwServices.swfobject')); 
?>