var $el2 = $("#uploadfile");
 
$el2.fileinput({
    overwriteInitial: true,
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
    uploadUrl: '<?php echo base_url()?>admin_content/douploadkartik_video/{file_id}/{id}',
	<?php 
	if($filename!=""){
	?>
	initialPreview: [
	    "<img src='{filename}' class='kv-image' alt='{filename}'>",
	],
	<?php }?>
});
