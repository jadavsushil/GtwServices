<script type="text/javascript">
    // <![CDATA[
    getFilesUrl = '<?php echo $this->Html->url(array('plugin'=>'gtw_services','controller'=>'services','action'=>'get_files',$serviceId));?>';
    // ]]>
</script>
<?php 
$this->Helpers->load('GtwRequire.GtwRequire');
$this->GtwRequire->req('ui/wysiwyg');
echo $this->GtwRequire->req($this->Html->url('/',true).'GtwServices/js/form.js');
?>
