<div class="row">
	<div class="col-12">

		<textarea id="status" name="currentsituationandadvice" class="editortools" rows="8" ><?= $quotation->CurrentSituationAndAdvice ?></textarea>
<?php if ($this->uri->segment(2) == 'update'): ?>
		<?php if ($quotationFiles != NULL) { ?>

					<h4 class="my-4">Bijlagen</h4>


					<div class="table-responsive">
						<table class="table table-hover table-striped table-sm" id="attachmentTable" width="100%">
							<thead>
								<tr>
									<th>Weergavenaam</th>
									<th>Bestandsnaam</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($quotationFiles as $quotationFile) { ?>
								<tr data-href="<?= base_url("uploads/$business->DirectoryPrefix/business/status/$quotationFile->QuotationId/$quotationFile->Name") ?>" data-target="blank">
									<td><?= $quotationFile->DisplayFileName ?></td>
									<td><?= $quotationFile->Name ?></td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>

		<?php } ?>
		<?php endif; ?>



				<h4 class="my-4">Bijlage toevoegen</h4>


				<div id="rij1" class="row align-items-center uploadrow mt-2">
					<div class="col-md-6 form-group label-floating">
						<label class="control-label">Omschrijving</label>
						<input id="FileDescription1" name="FileDescription[]" class="form-control">
					</div>
					<div class="col-8 col-md-5 dragdrop">
						<input name="upload[]" id="upload1" type="file" multiple>
					</div>
					<div class="col-4 col-md-1 text-center">
						<button type="button" onclick="addRow()" class="btn btn-success btn-round btn-fab btn-fab-mini">
							<i class="material-icons">add</i>
						</button>
					</div>
				</div>

	</div>
</div>

<div class="mt-5" id="thumb-output"></div>

<script type="text/javascript">
	var currentRow = 2;

	$(document).ready(function () {

		populateProjectPhases();
	});

	function removeRow(i) {
		$('#rij' + i).remove();
	}

	function addRow(){
		var myRow = '<div id="rij' + currentRow + '" class="row align-items-center uploadrow">';
		myRow += '<div class="col-md-6 form-group label-floating is-empty">';
		myRow += '<label class="control-label">Omschrijving</label>';
		myRow += '<input id="FileDescription' + currentRow + '" name="FileDescription[]" class="form-control">';
		myRow += '</div>';
		myRow += '<div class="col-8 col-md-5 dragdrop">';
		myRow += '<input name="upload[]" id="upload' + currentRow + '" type="file">';
		myRow += '</div>';
		myRow += '<div class="col-4 col-md-1 text-center">';
		myRow += '<button type="button" onclick="removeRow(' + currentRow + ')" class="btn btn-danger btn-round btn-fab btn-fab-mini">';
		myRow += '<i class="material-icons">remove</i>';
		myRow += '</button>';
		myRow += '</div>';
		myRow += '</div>';

		$("div.uploadrow:last").after(myRow);
		initDragdrop($('#rij' + currentRow + ' .dragdrop'));

		currentRow ++;
	}

	/**
	 * AJAX function to get project phases for the selected project.
	 *
	 */

	function populateProjectPhases() {
		var projectId = $('#project').val();

		if (projectId != '') {
			$('#project_phase').prop('required', true);
			$('#project_phase_div').slideDown();
			$('#prognosis_div input').prop('required', true);
			$('#prognosis_div label small').show();

			$.ajax({
				url: "<?= base_url('projects/ajax_getPhases/') ?>" + projectId
			}).done(function(msg){
				var data = JSON.parse(msg);
				var html = '<option></option>';

				if (data.error) {
					console.log(data.error);
				}
				else {
					$.each(data.projectPhases, function(phaseIndex, phase){
						html += '<option value="' + phase.Id + '">' + phase.Name + '</option>';
					});
				}

				$('#project_phase').html(html);
			});
		}
		else {
			$('#project_phase').prop('required', false);
			$('#project_phase_div').slideUp();
			$('#prognosis_div input').prop('required', false);
			$('#prognosis_div label small').hide();
		}
	}


// 	$(document).ready(function(){
// 	$('#file-upload').on('change', function(){ //on file input change
// 		if (window.File && window.FileReader && window.FileList && window.Blob) //check File API supported browser
//     	{
// 			$('#thumb-output').html(''); //clear html of output element
// 			var data = $(this)[0].files; //this file data

// 			$.each(data, function(index, file){ //loop though each file
// 				if(/(\.|\/)(gif|jpe?g|png|pdf)$/i.test(file.type)){ //check supported file type
// 					var fRead = new FileReader(); //new filereader
// 					fRead.onload = (function(file){ //trigger function on successful read
// 					return function(e) {
// 						var img = $('<img/>').addClass('thumb').attr('src', e.target.result); //create image element
// 						$('#thumb-output').append(img); //append image to output element
// 					};
// 				  	})(file);
// 					fRead.readAsDataURL(file); //URL representing the file's data.
// 				}
// 			});

// 		}else{
// 			alert("Uw browser ondersteunt geen File API!"); //if File API is absent
// 		}
// 	});
// });
</script>