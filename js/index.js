$(document).ready(function(){

	function outForWash(){
		$.ajax({
			url:'api/outForWash.php',
			type: 'post',
			dataType: 'JSON',
			beforeSend: function(){
				Swal.fire({
					type: 'info',
					padding: '40px',
					title: `Remove the cloth to be washed!`,					
					showConfirmButton: false,
					showCancelButton: false,
					showCloseButton: false,
					allowEscapeKey: false,
					allowOutsideClick: false
				});
			},
			success: function(d){				
				if(d[0] == true){
					window.location.reload();
				}else{		
					swal.close();
					Swal.fire({
						type: 'error',
						title: d[1],
						showConfirmButton: false,
						showCancelButton: false,
						showCloseButton: false,
						allowEscapeKey: false,
						allowOutsideClick: false,
						timer: 3000,
						onAfterClose: function(){
							// window.location.reload();
						}
					});
				}
			},
			error: function(){
				Swal.close();
				Swal.fire({
					type: 'error',
					title: 'Whoops!',
					text: 'An unknown error occued.',
					showConfirmButton: false,
					showCancelButton: false,
					showCloseButton: false,
					allowEscapeKey: false,
					allowOutsideClick: false,
					timer: 3000,
				});
			}
		});		

	}
	
	function updateWash(){
		$.ajax({
			url:'api/updateWash.php',
			type: 'post',
			dataType: 'JSON',
			beforeSend: function(){
				Swal.fire({
					type: 'info',
					padding: '40px',
					title: `Scan and Hang!`,
					text: `Scan the cloth and then hang it any empty slot`,
					showConfirmButton: false,
					showCancelButton: false,
					showCloseButton: false,
					allowEscapeKey: false,
					allowOutsideClick: false
				});
			},
			success: function(d){				
				if(d[0] == true){
					window.location.reload();
				}else{		
					swal.close();
					Swal.fire({
						type: 'error',
						title: d[1],
						showConfirmButton: false,
						showCancelButton: false,
						showCloseButton: false,
						allowEscapeKey: false,
						allowOutsideClick: false,
						timer: 3000,
						onAfterClose: function(){
							// window.location.reload();
						}
					});
				}
			},
			error: function(){
				Swal.close();
				Swal.fire({
					type: 'error',
					title: 'Whoops!',
					text: 'An unknown error occued.',
					showConfirmButton: false,
					showCancelButton: false,
					showCloseButton: false,
					allowEscapeKey: false,
					allowOutsideClick: false,
					timer: 3000,
				});
			}
		});		

	}

	function removePair(cloth1, color1, cloth2, color2, notCur){
		var alertTxt = `You need to wear ${allClothes[cloth1].name} (${allColors[color1].name}) and ${allClothes[cloth2].name} (${allColors[color2].name})`;
		if(notCur){
			alertTxt += '<br><br> <b>This is not a curated pair as you do not have sufficient clothes to generate one. Please add more clothes or return some from washing.</b>';
		}
		$.ajax({
			url:'api/removePair.php',
			type: 'post',
			dataType: 'JSON',
			beforeSend: function(){
				Swal.fire({
					type: 'info',
					padding: '40px',
					title: `Remove the pair that is lit up!`,
					html: alertTxt,
					showConfirmButton: false,
					showCancelButton: false,
					showCloseButton: false,
					allowEscapeKey: false,
					allowOutsideClick: false
				});
			},
			success: function(d){				
				if(d[0] == true){
					window.location.reload();
				}else{		
					swal.close();
					Swal.fire({
						type: 'error',
						title: d[1],
						showConfirmButton: false,
						showCancelButton: false,
						showCloseButton: false,
						allowEscapeKey: false,
						allowOutsideClick: false,
						timer: 3000,
						onAfterClose: function(){
							// window.location.reload();
						}
					});
				}

			},
			error: function(){
				Swal.close();
				Swal.fire({
					type: 'error',
					title: 'Whoops!',
					text: 'An unknown error occued.',
					showConfirmButton: false,
					showCancelButton: false,
					showCloseButton: false,
					allowEscapeKey: false,
					allowOutsideClick: false,
					timer: 3000,
				});
			}
		});		

	}

	function genPair(){
		$.ajax({
			url:'api/genPair.php',
			type: 'post',
			dataType: 'JSON',
			success: function(d){
				if(d[0] == true){
					let c1 = d[1].split('-')
					let c2 = d[2].split('-')
					let cloth1 = Number(c1[0])
					let cloth2 = Number(c2[0])
					let color1 = Number(c1[1])
					let color2 = Number(c2[1])
					removePair(cloth1, color1, cloth2, color2, d[3]);
				}else{		
					Swal.fire({
						type: 'error',
						title: d[1],
						showConfirmButton: false,
						showCancelButton: false,
						showCloseButton: false,
						allowEscapeKey: false,
						allowOutsideClick: false,
						timer: 5000,
					});
				}
			},
			error: function(){
				Swal.close();
				Swal.fire({
					type: 'error',
					title: 'Whoops!',
					text: 'An unknown error occued.',
					showConfirmButton: false,
					showCancelButton: false,
					showCloseButton: false,
					allowEscapeKey: false,
					allowOutsideClick: false,
					timer: 3000,
				});
			}
		});		

	}

	let hidden = false;

	$(document).keyup(function(e){
		// 1
		if(e.keyCode == 49){
			if(hidden){
				$('#hide-all').css("display", "none");
				hidden = false;
			}else{
				$('#hide-all').css("display", "block");
				hidden = true;
			}
		
		// 2
		}else if(e.keyCode == 50){
			genPair();
		
		// 3
		}else if(e.keyCode == 51){
			updateWash();
		
		// 4
		}else if(e.keyCode == 52){
			outForWash();
		
		// 5
		}else if(e.keyCode == 53){
			window.location.reload();
		}
	});
});