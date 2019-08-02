(function($) {
	$(document).ready(function() {
		
		var il = $("#il");
		var id;
		var ilce = $('select[name=ilce]');

		$.getJSON(cekilis.pluginsUrl + '/il.json', function(data) {
			var iller = [];
			$.each(data[2].data, function(key, val) {
				$('<option value="' + val.id + '">' + val.name + '</option>').appendTo(il);
			});
			
			if (chosen_il_id != 0) {
				$('option[value=' + chosen_il_id + ']').attr('selected', 'selected');
				
				ilce.empty();
				id = $('#il option:selected').val();
				
				$.getJSON(cekilis.pluginsUrl + '/ilce.json', function(data) 
				{
					var districts = [];
					$.each(data[2].data, function(key, val)
					{
						if (val.il_id == id) 
						{
							$('<option value="' + val.id + '"' + (chosen_ilce_id != 0 && chosen_ilce_id == val.id ? ' selected' : '') + '>' + val.name + '</option>').appendTo(ilce);
						}
					});
				});
			}
		});
		$('#ilce').change(function(e) {
			$('#ilcetext').val($('#ilce option:selected').text());
		});
		$('#il').change(function(e) {
			ilce.empty();
			id = $('#il option:selected').val();
			txt = $('#il option:selected').text();
			$('#iltext').val(txt);
			
			$.getJSON(cekilis.pluginsUrl + '/ilce.json', function(data) 
			{
				var districts = [];
				$.each(data[2].data, function(key, val)
				{
					if (val.il_id == id) 
					{
						$('<option value="' + val.id + '"' + (chosen_ilce_id != 0 && chosen_ilce_id == val.id ? ' selected' : '') + '>' + val.name + '</option>').appendTo(ilce);
					}
				});
				$('#ilcetext').val($('#ilce option:selected').text());
			});
		});

	});
}(jQuery));