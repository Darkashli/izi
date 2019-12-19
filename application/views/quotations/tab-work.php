<div class="row">
	<div class="col-md-4">
		<label>Select preset</label>
		<?php foreach ($defaulttexts as $defaulttext): ?>
			<a class="btn btn-default w-100 text-white textBtn" id="defaultWebsite" data-description="<?= html_escape($defaulttext->Text) ?>"><?= $defaulttext->Titel ?></a>
		<?php endforeach ?>
	</div>
	<div class="col-md-8">
		<label>Omschrijving</label>
		<textarea id="description" name="description" class="editortools" rows="8" ><?= $quotation->WorkDescription ?></textarea>
	</div>
</div>
<div class="row">
	<div class="col-md-9 form-group label-floating">
		<label class="control-label">Artikelnummer</label>
		<input class="form-control" type="text" name="work_articlenumber" id="work_articlenumber" value="<?= $quotation->WorkArticleC ?>" autocomplete="off" onkeyup="searchArticlenumberWork(this)">
		<ul class="list-group suggestions-listgroup clickable"></ul>
	</div>
	<div class="col-md-3">
		<div class="form-group label-floating">
			<label class="control-label">Prijs van werkzaamheden</label>
			<input class="form-control" type="number" name="price" value="<?= $quotation->WorkAmount ?>" step="any">
		</div>
	</div>
</div>

<script type="text/javascript">
	
	$(".textBtn").click(function() {

		var description = $(this).data("description");

		$(tinymce.get('description').getBody()).html(description);
		
	});
	
	function searchArticlenumberWork(element){
		var value = $(element).val().trim();

		$.ajax({
			url: '<?= site_url() ?>/product/searchProducts/'+value+'/ArticleNumber/0/2'
		}).done(function(msg){
			articles = JSON.parse(msg);
			var html = '';

			$.each(articles.products, function(index, value){
				html += '<li class="list-group-item list-group-item-action" onclick="clickSuggestionListWork('+index+')"><span class="font-weight-bold mr-1">'+value.ArticleNumber+'</span> | '+value.Description + ((value.ProductKind == 1) ? ' (Beschikbaar: ' + value.CountBackOrder + ')' : '') +'</li>';
			});

			$(element).closest(".form-group").find("ul.suggestions-listgroup").html(html);
		});
	}
	
	function clickSuggestionListWork(index){
		$('[name="work_articlenumber"]').val(articles['products'][index]['ArticleNumber']);
	}
	
</script>
