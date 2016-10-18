var $el2 = $("#uploadfile");
 
var footerTemplate = '<div class="file-thumbnail-footer">\n' +
'   <div style="margin:5px 0">\n' +
'       <input class="kv-input kv-new form-control input-sm {TAG_CSS_NEW}" value="{caption}" placeholder="Enter caption...">\n' +
'       <input class="kv-input kv-init form-control input-sm {TAG_CSS_INIT}" value="{TAG_VALUE}" placeholder="Image URL">\n' +
'   </div>\n' +
'   {actions}\n' +
'</div>';

$el2.fileinput({
    uploadUrl: '<?php echo base_url()?>admin_content/douploadkartik/{file_id}/{module}',
    uploadAsync: true,
    maxFileCount: 5,
    overwriteInitial: false,
	previewFileType: "image",
	browseClass: "btn btn-success",
	browseLabel: " Pick Image",
	browseIcon: '<i class="glyphicon glyphicon-picture"></i>',
	removeClass: "btn btn-danger",
	removeLabel: "Delete",
	removeIcon: '<i class="glyphicon glyphicon-trash"></i>',
	uploadClass: "btn btn-info",
	uploadLabel: "Upload & Save",
	uploadIcon: '<i class="glyphicon glyphicon-upload"></i>',
    layoutTemplates: {footer: footerTemplate},
    previewThumbTags: {
        '{TAG_VALUE}': '',        // no value
        '{TAG_CSS_NEW}': '',      // new thumbnail input
        '{TAG_CSS_INIT}': 'hide'  // hide the initial input
    },
	initialPreview: [
		<?php 
		foreach($files as $item=>$file):
		?>
		    "<img src='<?php echo base_url().$file['relative_path']."/".$file['name']?>' height='150' class='kv-image' alt='<?php echo $file['name']?>'>",
		<?php endforeach;?>
	],
	initialPreviewConfig: [
		<?php 
		$i=0;
		foreach($files as $item=>$file):
			$i++;
		?>
		    {caption: "<?php echo $file['name']?>", width: "120px", url: "<?php echo base_url()?>admin_content/dodelkartik/{file_id}/{module}/<?php echo $file['name']?>", key: <?php echo $i?>},
		<?php endforeach;?>
	],
	initialPreviewThumbTags: [
		<?php 
		foreach($files as $item=>$file):
		?>
		    {'{TAG_VALUE}': '<?php echo $file['name']?>', '{TAG_CSS_NEW}': 'hide', '{TAG_CSS_INIT}': ''},
		<?php endforeach;?>
	]
});

	$(".kv-input").bind( "click", function() {
		$("#clipboard").val("<?php echo base_url()?>media/images/{module}/{file_id}/" + $(this).val());
	});

	$(".kv-image").bind( "click", function() {
		$("#clipboard").val("<?php echo base_url()?>media/images/{module}/{file_id}/" + $(this).attr('alt'));
	});

	$('.file-drop-zone').click(function(){
		$("#clipboard").select();
		$("#clipboard").focus();
	});