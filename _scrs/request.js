/* 
===============================================

Wittgenstein 
 	request.js

@author: Samuel Acuna 
@date: 10/2013 

Script to keep track of time and requests. Also 
feeds interface with feedback provided from actual 
requests. 

===============================================
*/

// interval of requests in seconds 
var interval = 5; 
// hashtags to fetch 
var hashtags = ["atx", 
				"austin",
				"austintx"]; 		



// NO NEED TO EDIT BEYOND THIS POINT
// ============================================
var request; 
var t = 1; 
var r = 1; 

function request() {
	if(t%interval==0) {
		$('.requests').append(callAPIs(r)); 
		r++; 
	}

	$('.timer').html('Active for: '+parseSeconds(t)); 
	t++; 
}

$(document).ready(function() {
	request = setInterval(request,1000); 
	$('.action').click(function() {
		var id = $(this).attr('id'); 

		if(id=='pause') {
			clearInterval(request); 
			request = null; 
			$(this).attr('id','resume').html('&#xf04b;'); 
		} else {
			if(request!=null) return; 
			var func = function() {
				if(t%interval==0) {
					$('.requests').append(callAPIs(r)); 
					r++; 
				}

				$('.timer').html('active for: '+parseSeconds(t)); 
				t++; 
			}
			request = setInterval(func,1000); 
			$(this).attr('id','pause').html('&#xf04c;'); 
		}
	})
}); 

function callAPIs(r) {
	var tags = hashtags.join("|"); 
	var count = parseInt($('.count').val()); 
	$.ajax({
		type:'POST', 
		url:'_incs/request.php', 
		data: {tags:tags}, 
		async: false, 
		success: function(data) {
			var t = parseInt(data.split(".")[0]); 
			var i = parseInt(data.split(".")[1]); 
			if(t==0||i==0) {
				$('.requests').append('<span class="warning"><span class="description">('+r+')</span> Warning <span class="description">: <b>+'+t+'</b> Tweets, <b>+'+i+'</b> Instagrams, <b>+'+(t+i)+'</b> Added, new Total: '+count+'-><b>'+(count+t+i)+'</b></span></span>'); 
			} else {
				$('.requests').append('<span class="success"><span class="description">('+r+')</span> Success <span class="description">: <b>+'+t+'</b> Tweets, <b>+'+i+'</b> Instagrams, <b>+'+(t+i)+'</b> Added, new Total: '+count+'-><b>'+(count+t+i)+'</b></span></span>'); 
			}
			count += t+i; 
		}, 
		error: function(data) {
			$('.requests').append('<span class="error"><span class="description">('+r+')</span> Error: <span class="description">'+data+'</span></span>'); 
			console.log("=================================="); 
			console.log('('+r+')'); 
			console.log(data); 
		} 
	}); 

	$('.count').val(count); 
	$('.counter').html("Posts: "+count); 
}

function parseSeconds(t) {
	var sec = t%60; 
	var min = Math.floor((t/60)%60); 
	var hou = Math.floor((t/3600)%3600); 
	var day = Math.floor((t/86400)%86400); 
	var mon = Math.floor((t/2592000)%2592000); 
	var label = ''; 

	label = sec+' second'+((sec!=1)?'s':''); 

	if(min>0) label = min+' minute'+((min!=1)?'s':'')+', '+label; 
	if(hou>0) label = hou+' hour'+((hou!=1)?'s':'')+', '+label; 
	if(day>0) label = day+' day'+((day!=1)?'s':'')+', '+label; 
	if(mon>0) label = mon+' month'+((mon!=1)?'s':'')+', '+label; 

	return label; 
}