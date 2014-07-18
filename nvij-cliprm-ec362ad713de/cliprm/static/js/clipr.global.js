function bindUnclipped(elem){
	elem.click(function(e){
		if(elem.data("state")=="unclipped"){
			var pid = elem.data('pid');
			$.ajax({
				type: "GET",
				url: "/clip",
				data: {pid:pid}
			}).done(function(data){
				if(data.not_authenticated)
					window.location.href=data.login_url+'?next='+encodeURIComponent('http://'+window.location.host+'/product/'+pid);
				else
					changeToClipped(elem);
			});
		}
	});
}

function changeToClipped(elem){
	var pid = elem.data('pid');
	elem.parent().css({opacity:0.9});
	elem.data("state","clipped");
	elem.parent().find('.clip-btn-text').html("Clipped");
	elem.button('disable');
	elem.buttonMarkup({ icon: "check",iconpos:"left" });
	elem.unbind('click');
}

function changeToUnclipped(elem){
	var pid = elem.data('pid');
	elem.parent().css({opacity:1});
	elem.data("state","unclipped");
	elem.parent().find('.clip-btn-text').html("Clip");
	elem.button('enable');
	elem.buttonMarkup({ icon: "",iconpos:"" });
	bindUnclipped(elem);
}

function contractDescription(elem){
	elem.parent().animate({'max-height':'200px'}, {
		complete: function(){
			$(this).siblings('.more-less-btn').buttonMarkup({ icon: "arrow-d" });
			elem.css({background:''});
		}
	});
	elem.data('expanded','false')
}

function expandDescription(elem,h){
	elem.parent().animate({'max-height':h+'px'}, {
		complete: function(){
			$(this).siblings('.more-less-btn').buttonMarkup({ icon: "arrow-u" });
		}
	});
	elem.css({background:'transparent'});
	elem.data('expanded','true')
}

function smallFollowClicked(elem){
	var fid = elem.data('uid');
	var follow = 1;
	if(elem.data('following'))
		follow=0;
	$.ajax({
		type: "POST",
		url: "/follow",
		data: {fid:fid,follow:follow}
	}).done(function(data){
		if(data.not_authenticated)
			window.location.href=data.login_url+'?next='+encodeURIComponent('http://'+window.location.host+'/'+fid);
		else{
			if(elem.data('following'))
				changeToUnfollowed(elem);
			else
				changeToFollowed(elem);
		}
	});
}

function changeToFollowed(elem){
	elem.addClass('following');
	elem.buttonMarkup({'icon':'check'});
	elem.find('.ui-btn-text').text('Following');
	elem.data('following',true);
}
function changeToUnfollowed(elem){
	elem.removeClass('following');
	elem.buttonMarkup({'icon':''});
	elem.find('.ui-btn-text').text('Follow');
	elem.data('following',false);
}
function bindTagForm(elem){
	var tagSubmit = elem.find('.tag-submit');
	tagSubmit.unbind('click');
	tagSubmit.click(function(e){
		var pid = elem.data('pid');
		var comment = elem.find('.tagmessage').val();
		var friends = $.grep(elem.find('.as-values').val().split(','), function(n, i){return n != "";});
		friends = $.map(friends, function(n, i){return parseInt(n);});
		$.ajax({
			type: "POST",
			url: "/tag",
			data: {pid:pid, comment:comment, friends:friends}
		}).done(function(data){
			if(data.not_authenticated)
				window.location.href=data.login_url+'?next='+encodeURIComponent('http://'+window.location.host+'/product/'+pid);
		});
	});
}

function csrfSafeMethod(method) {
	// these HTTP methods do not require CSRF protection
	return (/^(GET|HEAD|OPTIONS|TRACE)$/.test(method));
}
// add header for csrf protection in all ajax post requests
$.ajaxSetup({
	crossDomain: false, // obviates need for sameOrigin test
	beforeSend: function(xhr, settings) {
		if (!csrfSafeMethod(settings.type)) {
			xhr.setRequestHeader("X-CSRFToken", $.cookie('csrftoken'));
		}
	}
});

$('.follow-btn-small, .follow-btn').live('click',function(){
	smallFollowClicked($(this));
});