var $el2 = $("#uploadfile");
 
$el2.fileinput({
    overwriteInitial: true,
    showPreview: true,
    fileType : 'any',
	browseClass: "btn btn-success",
	browseLabel: " Choose File",
	browseIcon: '<i class="glyphicon glyphicon-file"></i>',
	removeClass: "btn btn-danger",
	removeLabel: "Delete",
	removeIcon: '<i class="glyphicon glyphicon-trash"></i>',
	uploadClass: "btn btn-info",
	uploadLabel: "Upload & Save",
	uploadIcon: '<i class="glyphicon glyphicon-upload"></i>',
    uploadUrl: '<?php echo base_url()?>admin_content/douploadkartik_download/{file_id}/{id}'
});
