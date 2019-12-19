<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Test');
define('PAGE', 'test');

include 'application/views/inc/header.php';

?>

<style media="screen">
	
	#drop_zone {
		border: 5px dashed black;
		background-color: white;
		width:  200px;
		height: 100px;
		transition-duration: 0.5s;
	}
	
	#drop_zone.dragging{
		background-color: grey;
	}
	
	#file2{
		display: none;
	}
	
</style>

<form method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">subject</i>
					</div>
					<h4 class="card-title">Drag drop test</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12">
							
							<!-- Drop zone -->
							<div id="drop_zone" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" ondragleave="dragLeaveHandler(event);">
								<span id="file-title">Sleep items hier naartoe...</span>
							</div>
							
						</div>
						<div class="col-12">
							
							<input type="file" name="file2" id="file2">
							
						</div>
						<div class="col-12">
							
							<input type="reset">
							
						</div>
						<div class="col-12">
							<input type="submit">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">
	
	$(document).ready(function(){
		
		$('input[type="reset"]').click(function(){
			$("#file-title").html('Sleep items hier naartoe...');
		});
		
	});
		
	function dropHandler(ev) {
		console.log('File(s) dropped');
		
		$("#drop_zone").removeClass('dragging');
		
		// Prevent default behavior (Prevent file from being opened)
		ev.preventDefault();
		
		// console.log('items...');
		// console.log(ev.dataTransfer.items);
		// console.log('filelist...');
		// console.log(ev.dataTransfer.files);
		
		// if (ev.dataTransfer.items) {
		// 
		// 
		// 
		// 	// Use DataTransferItemList interface to access the file(s)
		// 	for (var i = 0; i < ev.dataTransfer.items.length; i++) {
		// 		// If dropped items aren't files, reject them
		// 		if (ev.dataTransfer.items[i].kind === 'file') {
		// 			var file = ev.dataTransfer.items[i].getAsFile();
		// 			console.log(file);
		// 			console.log('... file[' + i + '].name = ' + file.name);
		// 		}
		// 	}
		// } else {
		if (ev.dataTransfer.files) {
			// Add file to the input.
			console.log('File input is without files');
			var fileinput2 = $("#file2");
			console.log(fileinput2);
			console.log('Add file to input...');
			$(fileinput2).prop("files", ev.dataTransfer.files);
			console.log('File successfully added to input');
			console.log(fileinput2);
			
			// Use DataTransfer interface to access the file(s)
			for (var i = 0; i < ev.dataTransfer.files.length; i++) {
				var filename = ev.dataTransfer.files[i].name;
				console.log('... file[' + i + '].name = ' + filename);
				$("#file-title").html(filename);
			}
		}
	}
	
	function dragOverHandler(ev) {
		// Prevent default behavior (Prevent file from being opened)
		ev.preventDefault();
		
		console.log('File(s) in drop zone'); 
		
		$("#drop_zone").addClass('dragging');
	}
	
	function dragLeaveHandler(ev) {
		// Prevent default behavior (Prevent file from being opened)
		ev.preventDefault();
		
		console.log('File(s) in drop zone'); 
		
		$("#drop_zone").removeClass('dragging');
	}
	
</script>

<?php include 'application/views/inc/footer.php'; ?>
