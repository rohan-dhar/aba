$(document).ready(()=>{
	function getAllClothesHTML(){
		let html = '';
		let btn = '<button class="ui-btn cloth-add cloth-action">Add</button>';
		for (let i = 0; i < allClothes.length; i++){
			let cloth = allClothes[i];
			html += `<h3 class="clothes-holder-sub-head">${cloth.name}</h3>`;
			for (let j = 0; j < cloth.colors.length; j++){
				let id = i+'-'+cloth.colors[j];
				html += `<div class="cloth" data-type="${id}">`;
				html += `<img src="img/${i},${j}.png">`;
				html += `<div class="cloth-name">${cloth.name}</div>`;
				html += `<div class="ui-tag cloth-color" style="background-color: ${allColors[cloth.colors[j]].hex};">${allColors[cloth.colors[j]].name}</div>`;
				html += btn;
				html += `</div>`;
			}
		}	
		return html;
	}	

	function appendUserClothes(){

		$("#clothes-holder-current").html('<h2 class="clothes-holder-head">All clothes <div class="clothes-holder-head-sep"></div> </h2><div class="ui-complete-base clothes-holder-complete" id="added-complete"><div class="ui-complete-bar"></div></div>');
		$("#clothes-holder-washing").html('<h2 class="clothes-holder-head">Clothes out for washing <div class="clothes-holder-head-sep"></div> </h2> <div class="ui-complete-base clothes-holder-complete" id="washing-complete"><div class="ui-complete-bar"></div></div> ');

		let addedAvail = {}, addedWash = {}, addedWashNum = 0;
		let btn = '<button class="ui-btn cloth-remove cloth-action">Remove</button>';
		let html = ''; 

		if(window.userClothes.length < 1){
			$("#clothes-holder-current").append('<div class="no-clothes">You have added no clothes</div>');
			$("#clothes-holder-washing").append('<div class="no-clothes">No clothes out for washing</div>');
			$("#washing-complete").remove();
			$("#added-complete").remove();
			return 0;
		}	

		for(let i = 0; i < window.userClothes.length; i++){
			
			html = '';
			
			let info_id = window.userClothes[i];
			
			let info = info_id.split('-');
			let clothCode = info[0] + '-' + info[1];
			
			let cloth = allClothes[Number(info[0])];
			let color = allColors[Number(info[1])];

			if(Number(info[2]) == 1){		
				if(clothCode in addedWash){
					addedWash[clothCode]++;
					$("#clothes-holder-washing").children(`.cloth[data-type="${clothCode}"]`).children('.cloth-name').html(cloth.name + ` (${addedWash[clothCode]})`);
				}else{
					addedWash[clothCode] = 1;
				}			
				
				addedWashNum++;
			}
			
			if(clothCode in addedAvail){
				addedAvail[clothCode]++;
				$("#clothes-holder-current").children(`.cloth[data-type="${clothCode}"]`).children('.cloth-name').html(cloth.name + ` (${addedAvail[clothCode]})`);
				continue;
			}else{
				addedAvail[clothCode] = 1;
			}


			html += `<div class="cloth" data-type="${clothCode}">`;
			html += `<img src="img/${info[0]},${info[1]}.png">`;
			html += `<div class="cloth-name">${cloth.name} (1)</div>`;
			html += `<div class="ui-tag cloth-color" style="background-color: ${color.hex};">${color.name}</div>`;					
			html += btn;	
			
			html += `</div>`;
			
			if(Number(info[2]) == 1){
				$("#clothes-holder-washing").append(html);
				$("#clothes-holder-washing").children('.cloth').children('.cloth-action').remove();
				
			}
			$("#clothes-holder-current").append(html);
		}
		
		if(addedWashNum < 1){
			$("#clothes-holder-washing").append('<div class="no-clothes">No clothes out for washing</div>');
			$("#washing-complete").remove();
		}

		$("#added-complete .ui-complete-bar").width(window.userClothes.length / window.maxClothes * $("#added-complete").width()).text(`${window.userClothes.length} / ${window.maxClothes}`);
		$("#washing-complete .ui-complete-bar").width(addedWashNum / window.userClothes.length * $("#added-complete").width()).text(`${addedWashNum} / ${window.userClothes.length}`);

	}

	function addClothes(cloth, color){
		$.ajax({
			url:'api/addClothes.php',
			type: 'post',
			dataType: 'JSON',
			data: {
				'type': cloth,
				'color': color
			},
			beforeSend: function(){
				Swal.fire({
					type: 'info',
					padding: '40px',
					title: 'Scan and hang!',
					text: 'Scan the tag and hang the cloth at any empty slot!',
					showConfirmButton: false,
					showCancelButton: false,
					showCloseButton: false,
					allowEscapeKey: false,
					allowOutsideClick: false
				});
			},
			success: function(d){
				Swal.close();
				if(d[0]){
					window.userClothes.push(`${cloth}-${color}-0`);
					appendUserClothes();
					Swal.fire({
						type: 'success',
						title: 'Cloth added!',
						text: 'The selected cloth is now added to your wardrobe'
					}).then(()=>{
						$('body, html').animate({scrollTop: 0}, 600);	
					});
				}else{
					Swal.fire({
						type: 'error',
						title: 'Whoops!\nThe cloth was not added',
						text: d[1]
					});					
				}
			},
			error: function(){
				Swal.close();
				Swal.fire({
					type: 'error',
					title: 'Whoops!\nThe cloth was not added',
					text: 'Could not connect to the server.'
				});
			}
		});		
	}	

	function removeClothes(cloth, color){
		$.ajax({
			url:'api/manualRemoveClothes.php',
			type: 'post',
			dataType: 'JSON',
			data: {
				'type': cloth,
				'color': color
			},
			success: function(d){
				if(d[0]){
					window.userClothes = d[1]
					appendUserClothes();
					Swal.fire({
						type: 'success',
						title: 'Cloth Removed!',
						text: 'The selected cloth is now remove. You can now use the slot to add more clothes.'
					}).then(()=>{
						$('body, html').animate({scrollTop: 0}, 600);	
					});
				}else{
					Swal.fire({
						type: 'error',
						title: 'Whoops!\nThe cloth was not removed',
						text: d[1]
					});					
				}
			},
			error: function(){
				Swal.close();
				Swal.fire({
					type: 'error',
					title: 'Whoops!\nThe cloth was not removed',
					text: 'Could not connect to the server.'
				});
			}
		});		

	}
	appendUserClothes();	
	$("#clothes-holder-add").append(getAllClothesHTML());
	$("#clothes-holder-add").on('click', '.cloth-action', function(e){
		var d = $(this).parent().attr('data-type');
		d = d.split('-');
		addClothes(Number(d[0]), Number(d[1]));
	});
	$("#clothes-holder-current, #clothes-holder-washing").on('click', '.cloth-action', function(e){
		var d = $(this).parent().attr('data-type');
		d = d.split('-');
		removeClothes(Number(d[0]), Number(d[1]));
	});

});