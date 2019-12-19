var datetimepickerIcons = {
	time: "fa fa-clock-o",
	date: "fa fa-calendar",
	up: "fa fa-chevron-up",
	down: "fa fa-chevron-down",
	previous: 'fa fa-chevron-left',
	next: 'fa fa-chevron-right',
	today: 'fa fa-screenshot',
	clear: 'fa fa-trash',
	close: 'fa fa-remove'
}

$(document).ready(function ()
{
	tinymce.init(
	{
		selector:'textarea.editortools',
		plugins: "link paste autoresize spellchecker code lists",
		gecko_spellcheck: true,
		language: 'nl',
		branding: false,
		menubar: false,
		theme: 'modern',
		skin: 'lightgray',
		toolbar: 'undo redo | bullist numlist | bold, italic, underline, strikethrough | link | code |',
		paste_as_text: true,
		autoresize_min_height: 225,
		autoresize_max_height: 750,
		autoresize_bottom_margin: 25,
	});

	// Hide suggestion lists when clicked somewhere outside the element.
	$(window).click(function()
	{
		$('.suggestions-listgroup').html('');
	});
	
	// Tell sidebar state to Material Dashboard Pro.
	var sidebar_cookie_state = getCookie('sidebar_mini_active');
	if (sidebar_cookie_state == "true") {
		md.misc.sidebar_mini_active = true;
	}
	else{
		md.misc.sidebar_mini_active = false;
	}
	
	// Remember the sidebar state in an cookie when toggling the sidebar.
	$("button#minimizeSidebar").click(function(){
		var sidebar_mini_active = md.misc.sidebar_mini_active;
		if (sidebar_mini_active == true) {
			document.cookie = "sidebar_mini_active=true";
		}
		else{
			document.cookie = "sidebar_mini_active=false";
		}
	});
	
	$('.sidebar .sidebar-wrapper').perfectScrollbar();
	
	// Initialize drag-drop fileinputs.
	var dragdropDivs = $('div.dragdrop');
	$(dragdropDivs).each(function(index){
		var element = $(this);
		initDragdrop(element);
	});
	
	// Reset the file-input when reset is pressed.
	$('[type="reset"]').click(function(){
		$(dragdropDivs).each(function(index){
			resetDragdropFile($(this));
		});
	});
	
});


function showNotification(type, message, timeout) {
	timeout = timeout || 7500;

	console.log(timeout);

	if (type == 'error')
		type = 'danger';

	$(".notification-bar").removeClass("alert-success alert-danger alert-info alert-warning");
	$(".notification-bar").addClass("alert-" + type);
	$(".notification-bar").slideDown("fast");
	$(".notification-bar").html(message);

	if (timeout != 0) {
		setTimeout(function () {
			$(".notification-bar").slideUp();
		}, timeout);
	}
}

function createNotification(type, message, timeout) {
	timeout = timeout || 7500;
	$.notify({
		icon: "notifications",
		message: message

	}, {
		type: type,
		timer: timeout,
		placement: {
			from: 'top',
			align: 'center'
		}
	});
}

$(document).keydown(function(e){
	if (e.which == 38 || e.which == 40 || e.which == 13) { // Up, down and enter key.
		var suggestions_listgroup = $(".table input").closest("td").find(".suggestions-listgroup:not(:empty)");
		var liSelected = $(suggestions_listgroup).find(".active");

		if ($(suggestions_listgroup).html()) {
			e.preventDefault();
			$(".table input:focus").blur();
			if (e.which == 40) { // Down.
				if (liSelected) {
					var next = $(liSelected).next();
					$(liSelected).removeClass('active');
					if (next.length > 0) {
						$(next).addClass('active');
					}
					else{
						$(suggestions_listgroup).find("li:first-child").addClass('active');
					}
				}
				else{
					$(suggestions_listgroup).find("li:first-child").addClass('active');
				}
			}
			else if (e.which == 38){ // Up.
				if (liSelected) {
					var next = $(liSelected).prev();
					$(liSelected).removeClass('active');
					if (next.length > 0) {
						$(next).addClass('active');
					}
					else{
						$(suggestions_listgroup).find("li:last-child").addClass('active');
					}
				}
				else{
					$(suggestions_listgroup).find("li:last-child").addClass('active');
				}
			}
			else if (e.which == 13) { // Enter.
				if (liSelected) {
					$(liSelected).click();
				}
			}
		}
	}
});

/**
 * Link when a table row or column with the data-href tag is clicked.
 * It also opens a new tab when the ctrl key or the middle mouse button is pressed.
 *
 */
$(document).on("mousedown", "table.table tr[data-href], table.table td[data-href], table.table th[data-href]", function(e){
	var href = $(this).attr("data-href");
	var target = $(this).data("target");
	if (e.ctrlKey || e.which == 2 || target == 'blank') { // Ctrl pressed, middle mouse button pressed or has data-target="blank" attribute.
		e.preventDefault();
		var winopen = window.open(href, '_blank');
		winopen.blur();
		window.focus();
	}
	else if (e.which == 1) { // Left mouse button
		window.open(href, '_self');
	}
});

function getCookie(name)
{
	var re = new RegExp(name + "=([^;]+)");
	var value = re.exec(document.cookie);
	return (value != null) ? unescape(value[1]) : null;
}

/**
 * Initializes the izi account drag drop file inputs.
 *
 */
function initDragdrop(element) {
		var input = $(element).find('input[type="file"]');
		if (input.length == 0) {
			return false;
		}
		var inputName = $(input).attr('name');
		var inputId = $(input).attr('id');
		if (inputId === undefined) {
			inputId = "dragdrop_input_" + inputName;
			$(input).attr('id', inputId);
		}
		
		var files = $(input).prop("files");
		
		var html = '<label class="dropzone" for="' + inputId + '" ondrop="dropHandler(this, event);" ondragover="dragOverHandler(this, event);" ondragleave="dragLeaveHandler(this, event);">';
		// If a file exists in the input, show it on the screen. (Not supported in Firefox)
		if (files.length > 0) {
			fileName = files[0].name;
			html += '<span class="dragdrop-label"><i class="fas fa-file"></i> ' + fileName + ' <button type="button" onclick="removeDragdropFile(this, event)" class="btn btn-fab btn-fab-mini btn-round btn-danger"><i class="material-icons">close</i></button>';
		}
		else {
			html += '<span class="dragdrop-label"><i class="fas fa-cloud-upload-alt"></i> Upload bestanden hier...</span>';
		}
		html += '</label>';
		
		$(element).append(html);
		
		// If the file input has changed show the filename in the drag drop area.
		$(input).on('change', function(){
			showFilename($(this).parent('.dragdrop'));
		});
}
/**
 * Controls when a file is dropped in the dropzone.
 *
 */
function dropHandler(element, ev) {
	
	ev.preventDefault();
	
	$(".dragdrop .dropzone").removeClass('dragging');
	
	if (ev.dataTransfer.files) {
		// Add file to the input.
		try {
			var parent = $(element).closest('.dragdrop');
			$(parent).find('input[type="file"]').prop("files", ev.dataTransfer.files)
			showFilename(parent);
		} catch (e) {
			// Set an error message if drag drop is not supported (Edge).
			$(element).find(".dragdrop-label").html('<i class="fas fa-exclamation-circle"></i> Drag en drop kon niet worden uitgevoerd');
		}
	}
}

/**
 * Controls if a file is dragged over the dropzone.
 *
 */
function dragOverHandler(element, ev) {
	ev.preventDefault();
	
	$(element).addClass('dragging');
}

/**
 * Controls when a file leaves the dropzone
 *
 */
function dragLeaveHandler(element, ev) {
	ev.preventDefault();
	
	$(element).removeClass('dragging');
}

/**
 * Activates the resetDragdropFile function by pressing on the remove button.
 *
 */
function removeDragdropFile(element, ev) {
	ev.preventDefault();
	var parent = $(element).closest('.dragdrop');
	resetDragdropFile(parent);
}

/**
 * Resets image from drag drop file input.
 *
 */
function resetDragdropFile(element) {
	$(element).find('input[type="file"]').val('');
	$(element).find(".dragdrop-label").html('<i class="fas fa-cloud-upload-alt"></i> Upload bestanden hier...');
}

/**
 * Show the filename in the dropzone.
 *
 */
function showFilename(element) {
	var files =  $(element).find('input[type="file"]').prop('files');
	var fileName = files[0].name;
	$(element).find(".dragdrop-label").html('<i class="fas fa-file"></i> ' + fileName + ' <button type="button" onclick="removeDragdropFile(this, event)" class="btn btn-fab btn-fab-mini btn-round btn-danger"><i class="material-icons">close</i></button>');
	
}
