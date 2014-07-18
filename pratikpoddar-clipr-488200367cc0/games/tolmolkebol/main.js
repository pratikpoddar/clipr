String.prototype.escape_html = function() {
  return this.replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/"/g, "&quot;");
};
String.prototype.trim = function() {
  return this.replace(/^\s+|\s+$/g,"");
};

String.prototype.startsWith = function(str){
    return (this.indexOf(str) === 0);
}

String.prototype.is_int =function (){ 
  if((parseFloat(this) == parseInt(this)) && !isNaN(this)){
      return true;
  } else { 
      return false;
  } 
}

Number.prototype.toCurrency = function(n) {
	if (parseFloat(this) == parseInt(this)) {
		return this.toString();
	}
	return this.toFixed(n);
}

// jquery cookie
jQuery.cookie = {
	'get' : function(name){
		var regex = new RegExp('(^|[ ;])'+name+'\\s*=\\s*([^\\s;]+)');
		return regex.test(document.cookie)?unescape(RegExp.$2):null;
	},
	'set' : function(name, value, days){
		var expire = new Date();
		expire.setDate(expire.getDate() + (days||0));
		document.cookie = name+'='+escape(value)+(days?'':';expires='+expire);
	}
};

// $.jStorage by Andris Reinman, andris.reinman@gmail.com
(function(g){function m(){if(e.jStorage)try{d=n(""+e.jStorage)}catch(a){e.jStorage="{}"}else e.jStorage="{}";j=e.jStorage?(""+e.jStorage).length:0}function h(){try{e.jStorage=o(d),c&&(c.setAttribute("jStorage",e.jStorage),c.save("jStorage")),j=e.jStorage?(""+e.jStorage).length:0}catch(a){}}function i(a){if(!a||"string"!=typeof a&&"number"!=typeof a)throw new TypeError("Key name must be string or numeric");if("__jstorage_meta"==a)throw new TypeError("Reserved key name");return!0}function k(){var a,
b,c,e=Infinity,f=!1;clearTimeout(p);if(d.__jstorage_meta&&"object"==typeof d.__jstorage_meta.TTL){a=+new Date;c=d.__jstorage_meta.TTL;for(b in c)c.hasOwnProperty(b)&&(c[b]<=a?(delete c[b],delete d[b],f=!0):c[b]<e&&(e=c[b]));Infinity!=e&&(p=setTimeout(k,e-a));f&&h()}}if(!g||!g.toJSON&&!Object.toJSON&&!window.JSON)throw Error("jQuery, MooTools or Prototype needs to be loaded before jStorage!");var d={},e={jStorage:"{}"},c=null,j=0,o=g.toJSON||Object.toJSON||window.JSON&&(JSON.encode||JSON.stringify),
n=g.evalJSON||window.JSON&&(JSON.decode||JSON.parse)||function(a){return(""+a).evalJSON()},f=!1,p,l={isXML:function(a){return(a=(a?a.ownerDocument||a:0).documentElement)?"HTML"!==a.nodeName:!1},encode:function(a){if(!this.isXML(a))return!1;try{return(new XMLSerializer).serializeToString(a)}catch(b){try{return a.xml}catch(d){}}return!1},decode:function(a){var b="DOMParser"in window&&(new DOMParser).parseFromString||window.ActiveXObject&&function(a){var b=new ActiveXObject("Microsoft.XMLDOM");b.async=
"false";b.loadXML(a);return b};if(!b)return!1;a=b.call("DOMParser"in window&&new DOMParser||window,a,"text/xml");return this.isXML(a)?a:!1}};g.jStorage={version:"0.1.7.0",set:function(a,b,c){i(a);c=c||{};l.isXML(b)?b={_is_xml:!0,xml:l.encode(b)}:"function"==typeof b?b=null:b&&"object"==typeof b&&(b=n(o(b)));d[a]=b;isNaN(c.TTL)?h():this.setTTL(a,c.TTL);return b},get:function(a,b){i(a);return a in d?d[a]&&"object"==typeof d[a]&&d[a]._is_xml&&d[a]._is_xml?l.decode(d[a].xml):d[a]:"undefined"==typeof b?
null:b},deleteKey:function(a){i(a);return a in d?(delete d[a],d.__jstorage_meta&&("object"==typeof d.__jstorage_meta.TTL&&a in d.__jstorage_meta.TTL)&&delete d.__jstorage_meta.TTL[a],h(),!0):!1},setTTL:function(a,b){var c=+new Date;i(a);b=Number(b)||0;return a in d?(d.__jstorage_meta||(d.__jstorage_meta={}),d.__jstorage_meta.TTL||(d.__jstorage_meta.TTL={}),0<b?d.__jstorage_meta.TTL[a]=c+b:delete d.__jstorage_meta.TTL[a],h(),k(),!0):!1},flush:function(){d={};h();return!0},storageObj:function(){function a(){}
a.prototype=d;return new a},index:function(){var a=[],b;for(b in d)d.hasOwnProperty(b)&&"__jstorage_meta"!=b&&a.push(b);return a},storageSize:function(){return j},currentBackend:function(){return f},storageAvailable:function(){return!!f},reInit:function(){var a;if(c&&c.addBehavior){a=document.createElement("link");c.parentNode.replaceChild(a,c);c=a;c.style.behavior="url(#default#userData)";document.getElementsByTagName("head")[0].appendChild(c);c.load("jStorage");a="{}";try{a=c.getAttribute("jStorage")}catch(b){}e.jStorage=
a;f="userDataBehavior"}m()}};(function(){var a=!1;if("localStorage"in window)try{window.localStorage.setItem("_tmptest","tmpval"),a=!0,window.localStorage.removeItem("_tmptest")}catch(b){}if(a)try{window.localStorage&&(e=window.localStorage,f="localStorage")}catch(d){}else if("globalStorage"in window)try{window.globalStorage&&(e=window.globalStorage[window.location.hostname],f="globalStorage")}catch(g){}else if(c=document.createElement("link"),c.addBehavior){c.style.behavior="url(#default#userData)";
document.getElementsByTagName("head")[0].appendChild(c);c.load("jStorage");a="{}";try{a=c.getAttribute("jStorage")}catch(h){}e.jStorage=a;f="userDataBehavior"}else{c=null;return}m();k()})()})(window.$||window.jQuery);

//added by dev 

function require_login(){
  var path = location.pathname.toString();
  if (!path.startsWith('/login') && path !='/'){
	location.href="/signup?next="+location.href;
  }
  else{
	location.href='/signup';	
  }
  //location.href='/signup';
  return false;
  /*
  var path = location.pathname.toString();
  if (!path.startsWith('/login') && path !='/'){
	location.href="/login?next="+location.href;
  }
  else{
	location.href='/login';	
  }

  return false;
  */
}

function scrollToElement(elem, top) {
	var pos = elem.offset().top;
	if (top != undefined) {
		pos -= top;
    }
    $('html, body').animate({
        scrollTop: pos + 'px'
    }, 'fast');
}

if($('form:first').length){
  if (!('placeholder' in document.createElement('input'))) {
		  $('input[placeholder], textarea[placeholder]').each(function() {
			  var text = this.getAttribute('placeholder');
			  var fld = $(this);

			  function setPlaceholder() {
				  if (fld.val() == text || fld.val() == '') {
					  fld.addClass('jqPlaceholder');
					  fld.val(text);
				  }
			  }

			  function removePlaceholder() {
				  if (fld.val() == text || fld.val() == '') {
					  fld.val('');
					  fld.removeClass('jqPlaceholder');
				  }
			  }

			  setPlaceholder();

			  fld.focus(removePlaceholder);
			  fld.blur(setPlaceholder);
			  fld.parents("form").submit(removePlaceholder);
		  });
	  }
}

/**
 * Fancy UI
 */
var Fancy = {
    
    /**
     * Init function
     */
    init: function() {
      /*
        try{
        new Fancy.Placeholder('.search-box input[name="keyword"]');
        jQuery('input[placeholder], textarea[placeholder]').not('.search-box input[name="keyword"]').JSizedFormPlaceholder();
        }
        catch(err)
        {
        }
      */
        Fancy.logo();
        Fancy.searchBox();
	Fancy.scrollToTop();
        Fancy.buttons();
		Fancy.followListButtons();
        Fancy.formTips();
        //Fancy.photoTips();
		//Fancy.rankTips();
		Fancy.verifiedTips();
        Fancy.privateTips();
		Fancy.popupTips();
        Fancy.showSomeone();
        Fancy.usersAutoComplete();
        Fancy.validation();
        Fancy.notification();
        Fancy.comments();
        Fancy.usernameSyn();
        Fancy.changePass();
        //Fancy.youSubNav();
        Fancy.filter();
        Fancy.rankPopup();
	Fancy.reportPopup();
        Fancy.fieldFocus();
		Fancy.fancyBox();
        Fancy.datePicker();
        Fancy.customize();
	Fancy.selectAllFriends();


		$('form.search').submit(function(event){
		  event.preventDefault();
		  var query = $(this).find('input#search-query').val();
		  var url = "http://"+document.location.host.toLowerCase()+"/search?q="+encodeURIComponent(query);
			location.href= url;
		});
	if ($.browser.msie && parseInt($.browser.version, 10) < 8) {
		Fancy.fixStreamLinking();
	}	
        
    },
    
    /**
     * Logo Sliding up/down
     */
    logo: function() {
        var logoAction = {
            down: function(){
                $(this).find('span').animate({ height: 191 }, 300 );
            },
            up: function(){
                $(this).find('span').animate({ height: 62 }, 300 );
            }
        }

        $('#header .site-name a').hoverIntent({
            over: logoAction.down, 
            timeout: 250, 
            out: logoAction.up
        });
    },

    /**
     * Search box
     */
    searchBox: function() {
        var sbHeader = $('#header');
        var sbWrapper = sbHeader.find('.search-box');
        var sbSearch = sbHeader.find('.search');
        var sbAction = {
            sbUp: function() {
                sbSearch.animate({
                    paddingBottom: 0,
                    height: 0,
                    paddingTop: 0
                }, 400 );
                sbWrapper.removeClass('sb-expand');
            },
            sbDown: function() {
                sbSearch.animate( {
                    paddingTop: 7,
                    height: 56,
                    paddingBottom: 7
                }, 400 );
                sbWrapper.addClass('sb-expand');
				var st = $('body').scrollTop(), $input = sbWrapper.find('input').focus();
				setTimeout(function(){ $('body').animate({scrollTop:st},'fast') },100);
            }
        }

        sbWrapper.find('.trigger').click(function(){
            if (sbWrapper.hasClass('sb-expand')) {
                sbAction.sbUp();
            } 
            else {
                sbAction.sbDown();
            }
            return false;
        });

        $(document).keydown(function(e) {
            if (e.keyCode == 27) {
                sbAction.sbUp();
            }
        });
    },
    
    /**
    *  Scroll to top 
    */	
    
    scrollToTop: function() {
	    var scrollBtn = $("#scroll-to-top");
	    scrollBtn.hide();

	    $(function () {
		    $(window).scroll(function () {
			    if ($(this).scrollTop() > 300) {
				    scrollBtn.fadeIn();
			    } else {
				    scrollBtn.fadeOut();
			    }
		    });
    
		    scrollBtn.click(function () {
			    $('body,html').animate({
				    scrollTop: 0
			    }, 800);
			    return false;
		    });
	    });
    },
    
	/**
	 * Buttons
	 */
	buttons: function() {
		// Follow button
		var followBtn = $('.button.follow.entry,.button.follow.user-icon');
		var followingBtn = $('.button.following.entry,.button.following.user-icon');
        
        followBtn.live('click', function() {
            var uid = $(this).attr('uid');
            var eid = $(this).attr('eid');
            var param = {};
            param['user_id']=uid;
            param['directory_entry_id']=eid;
            var btn = $(this);
            $.post("/add_follow.xml",param, 
                    function(xml){
                            if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
                                btn.removeClass("follow").addClass("following");
                                btn.html('<span></span>' + gettext('Following'));
                                btn.children('span').css({ backgroundPosition: '0 -35px', height: '14px', width: '40px' });
                                btn.css({ backgroundPosition: "-200px 0" });
                            }
                            else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
                            }  
            }, "xml");
            
            return false;
        });
        
        followingBtn.live('click', function() {
            var uid = $(this).attr('uid');
            var eid = $(this).attr('eid');
            var param = {};
            param['user_id']=uid;
            param['directory_entry_id']=eid;
            var btn = $(this);
            $.post("/delete_follow.xml",param, 
                    function(xml){
                            if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {			
                                btn.removeClass("following").addClass("follow");
                                btn.html('<span></span>' + gettext('Follow'));
                                btn.children('span').css({ backgroundPosition: '0 0', height: '16px', width: '40px' });
                                btn.css({ backgroundPosition: "0 0" });
                            }
                            else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
                            }  
            }, "xml");
            
            return false;
        });
        
        followingBtn.live('mouseover mouseout', function(event) {
            var btn = $(this);
            if (event.type == 'mouseover') {
                 btn.html('<span></span>Unfollow');
            } 
            else {
                 btn.html('<span></span>Following');
                 btn.removeAttr('style');
            }
        });
        
        // Fancy it button
        var fancyBtn = $('.button.fancy');
        var fancydBtn = $('.button.fancyd');
        
        fancyBtn
			.live('click', function() {
				var login_require = $(this).attr('require_login'); 
				if (login_require && login_require=='true') require_login();
				return false;
			})
			.live('mouseleave', function(event){
				$(this).removeAttr('style');
			});
        
		fancydBtn
			.live('click', function() {
				var login_require = $(this).attr('require_login'); 
				if (login_require && login_require=='true') require_login();
				return false;
			})
			.live('mouseenter mouseleave', function(event) {
				var $this = $(this);
				if (event.type == 'mouseenter') {
					$this.contents().filter(function(){ return this.nodeType == 3; }).remove();
					$this.append(gettext('Edit'));
				} else {
					$this.html("<span><i></i></span>" + gettext("Fancy'd")).removeAttr('style');
				}
			});
        
        followBtn.live('mouseover mouseout', function(event) {
            if (event.type == 'mouseout') {
                $(this).removeAttr('style');
            }
        });
        
        // Follow links
        var followLink = $('.follow-link');
        var followingLink = $('.following-link');
        
        followLink.live('click', function() {
            var login_require = $(this).attr('require_login'); 
            if (typeof(login_require) != undefined && login_require != null && login_require=='true'){ 
                  require_login();
                  return false;
            }
            var uid = $(this).attr('uid');
            var eid = $(this).attr('eid');
            var link_type = $(this).attr('linktype');
	    var shown_uids = "";
	    if (link_type =='recommended')
	    {
	      
	      $('.follow-link').each(function() {
		  shown_uids = shown_uids + $(this).attr('uid') + ",";
		});
	      
	    }
	    
            var param = {};
            param['user_id']=uid;
            param['directory_entry_id']=eid;
            var link = $(this);
            $.post("/add_follow.xml",param, 
                function(xml){
                    if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
			if (link_type == 'recommended')
			{
			  var single_vcard = '_single_vcard.html';
			  if (shown_uids != "")
			  {
			    single_vcard = single_vcard + "?uids=" + shown_uids;
			  }
			  var item = link.parent('li');
			  link.text('Following');
			  link.removeClass('follow-link');
			  setTimeout(function() {
				  $.get(single_vcard, function(data) {
					  item.fadeTo(500, 0.01, function() {
						  item.html(data);
						  item.fadeTo(250, 1);
					  });
				  });
			  }, 2000);
			}
			else{
                        link.removeClass("follow-link").addClass("following-link");
                        link.html('Following');
			}
                    }
                    else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
                    }  
            }, "xml");
            return false;
        });
        
        followingLink.live('click', function() {
            var login_require = $(this).attr('require_login'); 
            if (typeof(login_require) != undefined && login_require != null && login_require=='true'){ 
                  require_login();
            }
            var uid = $(this).attr('uid');
            var eid = $(this).attr('eid');
            var link_type = $(this).attr('linktype');
            var param = {};
            param['user_id']=uid;
            param['directory_entry_id']=eid;
            var link = $(this);
            $.post("/delete_follow.xml",param, 
                function(xml){
                    if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
		      if (link_type == 'private'){
                        link.removeClass("following-link").addClass("follow-link");
                        link.html('');
		      }
		      else{
                        link.removeClass("following-link").addClass("follow-link");
                        link.html('Follow');
		      }
                    }
                    else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
                    }  
            }, "xml");
            return false;
        });
        
        followingLink.live('mouseover mouseout', function(event) {
            var link = $(this);
            if (event.type == 'mouseover') {
                 link.html('Unfollow');
            } else {
                link.html('Following');
            }
        });
	
	// Follow contact links
	var followuserLinks = $('.follow-user-link');
	var followuserLinksFollowed = $('.follow-user-link.following');

	followuserLinks.live('click', function() {
            var login_require = $(this).attr('require_login'); 
            if (typeof(login_require) != undefined && login_require != null && login_require=='true'){ 
                  require_login();
                  return false;
            }
	    
	    if ($(this).hasClass('following'))
	    {
	      return false;
	    }
	    
            var uid = $(this).attr('uid');
	    
            var param = {};
            param['user_id']=uid;
            var link = $(this);
            $.post("/add_follow.xml",param, 
                function(xml){
                    if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
					  link.html("Following");		
					  link.addClass("following");
                    }
                    else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
                    }  
            }, "xml");
            return false;
	});
	
	followuserLinksFollowed.live('mouseover mouseout', function(event) {
		if (event.type == 'mouseover'){
			$(this).html('Unfollow');
		} 
		else {
			$(this).html("Following");
		}
	});
	
	followuserLinksFollowed.live('click', function() {
	      
            var login_require = $(this).attr('require_login'); 
            if (typeof(login_require) != undefined && login_require != null && login_require=='true'){ 
                  require_login();
            }
            var uid = $(this).attr('uid');
            var link_type = $(this).attr('linktype');
            var param = {};
            param['user_id']=uid;
            var link = $(this);
            $.post("/delete_follow.xml",param, 
                function(xml){
                    if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
					  if (link_type == 'private'){
                        link.html('');
						link.removeClass("following");
					  }
					  else{
						link.html('Follow');						  
						link.removeClass("following");
					  }
                    }
                    else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
                    }  
            }, "xml");
            return false;
		
	});
    },    

	/**
	 * Follow lists buttons
	 */
	followListButtons: function() {
		
		// Follow lists button
		var followlistsBtn = $('.button.lists.follow');
		var followinglistsBtn = $('.button.lists.following');
		var txtList;
		
		/**
		 * Check if there are some lists followed
		 */
		function areFollowed() {
			var numLists = $(".catalog-lists #content .listings li .following").length * 1;
			if (numLists === 0) {
				txtList = ' lists';
				return false;
			} else if (numLists === 1) {
				txtList = ' list';
				return true;
			} else {
				txtList = ' lists';
				return true;
			}
		}
		
		/**
		 * Count number of followed lists
		 */
		function countFollowedLists() {
			var numLists = $(".catalog-lists #content .listings li .following").length * 1;
			if (numLists === 0) {
				txtList = ' list';
			} else if (numLists === 1) {
				txtList = ' list';
			} else {
				txtList = ' lists';
			}
			return numLists;
		}
		
		/**
		 * Count total number of lists
		 */
		function countTotalLists() {
			var numLists = $(".catalog-lists #content .listings li").length * 1;
			return numLists;
		}
		
		/**
		 * Update follow button
		 */
		function updateFollowButton(btn,count) {
			//var num = countFollowedLists();
			btn.removeClass("follow").addClass("following");
			if (typeof(count) != undefined && count != null){
				btn.attr('cnt',count);
				if (count == 1)
					txtList = ' list';
				else
					txtList = ' lists';
				btn.html('<span></span>Following ' + count + txtList);			  			  
			}
			else{
				//btn.html('<span></span>Following All');// + num + txtList);			  
				btn.html('<span></span>Following');// + num + txtList);			  
			}
			btn.children('span').css({ backgroundPosition: '0 -35px', height: '14px', width: '40px' });
			//btn.css({ backgroundPosition: "-200px 0" });
		  }
		
		
		function updateProfileFollowButton(btn,count) {
			//var num = countTotalLists();
			var profile_button = $('.button.lists.following');
			if (!profile_button.length)
			  profile_button = $('.button.lists.follow')

			if (typeof(count) != undefined && count != null){
				if (count == 0){
				  profile_button.removeClass("following").addClass("follow");
				  //profile_button.html('<span></span>Follow All');// + num + txtList);			  
				  profile_button.html('<span></span>Follow');// + num + txtList);			  
				  profile_button.children('span').css({ backgroundPosition: '0 0', height: '16px', width: '40px' });
				  profile_button.css({ backgroundPosition: "0 0" });
				  return false;
				}
				profile_button.attr('cnt',count);
				
				if (count == 1)
					txtList = ' list';
				else
					txtList = ' lists';
				profile_button.html('<span></span>Following ' + count + txtList);
				//alert(btn.text());
			}
			else{
				//profile_button.html('<span></span>Following All');// + num + txtList);			  
				profile_button.html('<span></span>Following');// + num + txtList);			  
			}
			profile_button.children('span').css({ backgroundPosition: '0 -35px', height: '14px', width: '40px' });
			if(count>0 && $('.button.lists.follow').length){
			  $('.button.lists.follow').removeClass("follow").addClass("following");
			}

		}

		/**
		 * Update following button
		 */
		function updateFollowingButton(btn,count) {
			//var num = countTotalLists();
			btn.removeClass("following").addClass("follow");
			if (typeof(count) != undefined && count != null && count > 0){
				btn.attr('cnt',count);
				if (count == 1)
					txtList = ' list';
				else
					txtList = ' lists';
				btn.html('<span></span>Follow ' + count + txtList);			  			  
			}
			else{
				//btn.html('<span></span>Follow All');// + num + txtList);			  
				btn.html('<span></span>Follow');// + num + txtList);			  
			}

			btn.children('span').css({ backgroundPosition: '0 0', height: '16px', width: '40px' });
			btn.css({ backgroundPosition: "0 0" });
		}
		
		// Follow lists button, means no lists are followed yet so basically by clicking follow all lists
		followlistsBtn.live('click', function() {
            var login_require = $(this).attr('require_login'); 
            if (typeof(login_require) != undefined && login_require != null && login_require=='true'){ 
                  require_login();
            }

			var selectedRow = $(this);
            var uid = $(this).attr('uid');
            var param = {};
            param['user_id']=uid;
            var btn = $(this).addClass('loading');			
            $.post("/add_follow.xml",param, 
				function(xml){
					btn.removeClass('loading');
					if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
						selectedRow.attr('cnt','All');
						$(".catalog-lists #content .listings .follow-list-link").removeClass('follow').addClass('following').html("Following");
						updateFollowButton(selectedRow);
					} else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
					}
            }, "xml");

			return false;
		});
		
		// Following some lists already, by clicking it unfollow all lists
		followinglistsBtn.live('click', function() {
            var login_require = $(this).attr('require_login'); 
            if (typeof(login_require) != undefined && login_require != null && login_require=='true'){ 
                  require_login();
            }

			var selectedRow = $(this);
            var uid = $(this).attr('uid');
            var param = {};
            param['user_id']=uid;
            var btn = $(this).addClass('loading');
            $.post("/delete_follow.xml",param, 
				function(xml){
					btn.removeClass('loading');
					if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
						selectedRow.removeAttr('cnt');
						$(".catalog-lists #content .listings .follow-list-link").removeClass('following').addClass('follow').html("Follow");
						updateFollowingButton(selectedRow);
					} else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
					}
            }, "xml");
			return false;
		});
		
		// Hover states
		followinglistsBtn.live('mouseenter mouseleave', function(event) {
			var num = $(this).attr('cnt');//countFollowedLists();
			
			var btn = $(this);
				if (event.type == 'mouseenter') {
					if (typeof(num) != undefined && num != null && num != 'All'){
						if (num == 1)
							txtList = ' list';
						else
							txtList = ' lists';
						btn.html('<span></span>Unfollow ' + num + txtList);
					}
					else{
						//btn.html('<span></span>Unfollow All');// + num + txtList);
						btn.html('<span></span>Unfollow');// + num + txtList);
					}
				} 
				else {
					if (typeof(num) != undefined && num != null && num != 'All'){
						if (num == 1)
							txtList = ' list';
						else
							txtList = ' lists';
						btn.html('<span></span>Following ' + num + txtList);
					}
					else{
						//btn.html('<span></span>Following All');// + num + txtList);					  
						btn.html('<span></span>Following');// + num + txtList);					  
					}
					btn.removeAttr('style');
				}
		});
		
		// Follow lists links
		var followuserLinks = $('.follow-list-link');
		var followuserLinksFollowed = $('.follow-list-link.following');
		
		function updateButtons(count) {
			if (typeof(count)!= undefined && count != null){
				//if (count == 0) {
				//	updateFollowingButton(followlistsBtn,count);
				//} else {
				//	updateFollowButton(followinglistsBtn,count);
				//}
				updateProfileFollowButton(followinglistsBtn,count);
			}
			else{
				var num = countFollowedLists();
				if (num == 0) {
					updateFollowingButton(followlistsBtn);
				} else {
					updateFollowButton(followinglistsBtn);
				}
			  
			}
		}

		followuserLinks.live('click', function() {
            var login_require = $(this).attr('require_login'); 
            if (typeof(login_require) != undefined && login_require != null && login_require=='true'){ 
                  require_login();
            }

			var selectedRow = $(this);
			if (selectedRow.hasClass('following'))
				return false;
            var luid = $(this).attr('luid');
            var lid = $(this).attr('lid');
            var param = {};
            param['lid']=lid;
            param['loid']=luid;
            var btn = $(this);
            $.post("/follow_list.xml",param, 
				function(xml){
					if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
						var total_count = $(xml).find("count").text();
						selectedRow.html("Following");
						selectedRow.addClass("following");
						updateButtons(total_count);
					}
					else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
					}  
            }, "xml");
			return false;

		});
		
		followuserLinksFollowed.live('click', function() {
            var login_require = $(this).attr('require_login'); 
            if (typeof(login_require) != undefined && login_require != null && login_require=='true'){ 
                  require_login();
            }

			var selectedRow = $(this);
            var luid = $(this).attr('luid');
            var lid = $(this).attr('lid');
            var param = {};
            param['lid']=lid;
            param['loid']=luid;
            var btn = $(this);
            $.post("/unfollow_list.xml",param, 
				function(xml){
					if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
						var total_count = $(xml).find("count").text();
						selectedRow.html("Follow");
						selectedRow.removeClass("following");
						updateButtons(total_count);
					}
					else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
					}  
            }, "xml");
		  
			return false;
			
		});
		
		followuserLinksFollowed.live('mouseover mouseout', function(event) {
			if (event.type == 'mouseover'){
				$(this).html('Unfollow');
			} 
			else {
				$(this).html("Following");

			}
		});
		
	},	
    
    /**
     * Form Tips
     */
    formTips: function() {
        if($('.page-deal-create').length) {
			$('.page-deal-create #content input[title]').tipsy({
				trigger: 'focus',
				gravity: 'n',
				html: true
			});            
        }
		/*if(!$('.page-deal-create').length) {
			$('#content input[title], #content textarea[title], #showpopup input[title], #showpopup textarea[title], #edit-list input[title]').tipsy({
				trigger: 'focus', 
				gravity: 'w',
				html: true
			});
		}
		
		else {
			$('.page-deal-create #content input[title]').tipsy({
				trigger: 'focus',
				gravity: 'n',
				html: true
			});
        }
        */

    },
    
    /**
     * Photo Tips
     */
    photoTips: function() {
        $('.tagged a[title]').tipsy({
            trigger: 'hover', 
            gravity: 's',
            html: true
        });
    },
	verifiedTips: function() {
		$('.nickname span.ico-link[title]').tipsy({
			trigger: 'hover', 
			gravity: 's',
			html: true
		});
	},
    privateTips: function() {
		$('.vcard span.ico-private[title]').tipsy({
			trigger: 'hover', 
			gravity: 's',
			html: true
		});
	},
    /**
    * Rank Tips
    */
    rankTips: function() {
	    $('.rank span[title]').tipsy({
		    trigger: 'hover', 
		    gravity: 's',
		    html: true
	    });
    },
    
	/**
 	* Popup Tips
 	*/
	popupTips: function() {
		$('#new-category[title]').tipsy({
			trigger: 'focus', 
			gravity: 's',
			html: true
		});
	},
    /**
     * Show to someone Dialog
     */
    showSomeone: function() {

		return;
        
        if (!$("#show-someone").length) {
            return;
        }
        
        // Set up the dialog
        var showPopup = $("#showpopup").dialog({ 
            open: function(event, ui) {  
                $(".ui-dialog-titlebar-close").hide(); 
                $(".ui-dialog-content.ui-widget-content").css({
                    width: '356px' 
                    //height: '330px', 
                    //position: 'absolute' 
                }); 
            },
            autoOpen: false,
            closeOnEscape: true,
            draggable: false,
            closeText: 'Cancel'
        });
        
        // Open by clicking on the link
        $('#show-someone').click(function() {
            var login_require = $(this).attr('require_login');
            if (typeof(login_require) != undefined && login_require != null && login_require=='true'){ 
                require_login();
                return false;
            }

            showPopup.dialog('open'); 
            return false;
        });
        
        // Close by clicking on Cancel
        $('#showpopup .button.cancel').click(function(){
            $(".tipsy").hide();
            showPopup.dialog('close');
            return false;
        });
        
        // Make it draggable
        showPopup.draggable({ "handle": '#showpopup h3' }); 
        
        // Switch email/username dialog
        var viaEmail = $('#showpopup .via-email');
        var onFancy = $('#showpopup .on-fancy');
        var emailAddress = $('#showpopup .emailaddress');
        var fancyUsername = $('#showpopup .fancyusername');
        var personalnote = $('#showpopup .personalnote');
        
        $('#showpopup input:radio').change(function() { 
            if ($("#showpopup input:radio:checked").val() == 'email') {
                viaEmail.show();
                emailAddress.removeClass('ignore');
                onFancy.hide();
                fancyUsername.addClass('ignore');
                personalnote.show();
            }
            else {
                viaEmail.hide();
                emailAddress.addClass('ignore');
                onFancy.show();
                fancyUsername.removeClass('ignore');
                personalnote.show();
            }
            return false;
        });
        
        $('#showpopup form').validate({
            ignore: ".ignore",
            rules: {
                email: {
                    required: true,
                    email: true
                },
                username: "onfancy"
            },
            messages: {
                email: "Doesn’t look like a valid email address."
            }
        });

        jQuery.validator.addMethod("onfancy", function(value) { 
            if ($("#users").hasClass('found')) {
                return true;
            } 
            else {
                return false;
            }
        }, "Can't find anyone with that name");
        
        // Submit the form
        $('#showpopup .send').live('click',function() {
            var person_note = $('#showpopup .personalnote').val();
            var person_note_pl = $('#showpopup .personalnote').attr('placeholder');
            var name = $('#showpopup').find('h3').attr('name');
            var url = $('#showpopup').find('h3').attr('url');
            if ($("#showpopup input:radio:checked").val() == 'email') {
                var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
                if(emailAddress.val().search(emailRegEx) == -1){
                    if(!emailAddress.parent('div').find('label.error').length){
                        emailAddress.addClass('error');
                        emailAddress.parent('div').append('<label class="error" for="email" generated="true">Doesn’t look like a valid email address.</label>');
						return false;
                    }
                }
		/*
                var param = {};
                param['email']=emailAddress.val();
                if (person_note.trim().length >0 && person_note != person_note_pl)
                    param['message']=person_note;
                param['via']='email';			

                param['name']=name;
                param['url']=url;
                
                var otype = $('#showpopup').find('h3.name').attr('otype');
                if (otype == 'nt'){
                    param['type']='nt';
                    param['oid'] = $('#showpopup').find('h3.name').attr('oid');
                    param['ooid'] = $('#showpopup').find('h3.name').attr('ooid');				
                }
                else if (otype == 'p'){
                    param['type']='p';
                    param['oid'] = $('#showpopup').find('h3.name').attr('oid');
                    param['ooid'] = $('#showpopup').find('h3.name').attr('ooid');				
                }
                else if (otype == 't'){
                    param['type']='t';
                    param['oid'] = $('#showpopup').find('h3.name').attr('oid');
                }
                
                $.post("/show-it.xml",param, 
                    function(xml){
                        if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
                            alert('Sent!');
                            emailAddress.val('');
                            $('#showpopup .personalnote').val('');
                            $(".tipsy").hide();
                            showPopup.dialog('close');

                        }
                        else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
                            alert($(xml).find("message").text());
                        }  
                }, "xml");
		*/
		
	  	sender_name = $('#showpopup').find('h3.name').attr('sender');
		object_url = $('#showpopup').find('h3.name').attr('object_url');
		
		var email_body = "";
		if (person_note.trim().length >0 && person_note != person_note_pl)
                    email_body = email_body + person_note + "\r\n\r\n";
	        email_body = email_body + "Click here to check it out\r\n";
		email_body = email_body + "http://www.thefancy.com"+object_url+"\r\n\r\n";
		

		var link = "mailto:"+emailAddress.val()
		      + "?&subject=" + escape(sender_name + " wants to show you something on Fancy")              
		      + "&body=" + escape(email_body);      
		      location.href = link;
		      
		emailAddress.val('');
		$('#showpopup .personalnote').val('');
		$(".tipsy").hide();
		showPopup.dialog('close');
		
                return false
                
            }
            else {
                var uid = fancyUsername.attr('uid'); 
                if (typeof(uid) != undefined && uid != null){ 
                    var param = {};
                    param['uid']=uid;
                    param['via']='fancy';			
                    param['name']=name;
		  if (person_note.trim().length >0 && person_note != person_note_pl)
		      param['message']=person_note;
                    param['url']=url;
                    var otype = $('#showpopup').find('h3.name').attr('otype');

                    if (otype == 'nt'){
                        param['type']='nt';
                        param['oid'] = $('#showpopup').find('h3.name').attr('oid');
                        param['ooid'] = $('#showpopup').find('h3.name').attr('ooid');				
                    }
                    else if (otype == 'p'){
                        param['type']='p';
                        param['oid'] = $('#showpopup').find('h3.name').attr('oid');
                        param['ooid'] = $('#showpopup').find('h3.name').attr('ooid');				
                    }
                    else if (otype == 't'){
                        param['type']='t';
                        param['oid'] = $('#showpopup').find('h3.name').attr('oid');
                    }
                    $.post("/show-it.xml",param, 
                        function(xml){
                            if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
                                alert('Sent!');
                                $(".tipsy").hide();
                                fancyUsername.removeAttr('uid');
                                fancyUsername.val('');
				$('#showpopup .personalnote').val('');
                                showPopup.dialog('close');
    
                            }
                            else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
                                alert($(xml).find("message").text());
                            }  
                    }, "xml");
                    return false
                    
                }
            }
            return false;
        });
        // Submit the form
        //$('#showpopup .send').click(function() {
        //    $('#showpopup form').submit();
        //    return false;
        //});
        
    },

    /**
     * Users auto complete
     */
     usersAutoComplete: function() {
        
        if (!$("#users").length) {
            return;
        }
        
        $.widget("ui.customautocomplete", $.extend({}, $.ui.autocomplete.prototype, {
            _response: function(contents){
                $.ui.autocomplete.prototype._response.apply(this, arguments);
                $(this.element).trigger("autocompletesearchcomplete", [contents]);
            }
        }));

        
        $("#users").customautocomplete({
            minLength: 0,
            source: "/search-users.json",
            focus: function( event, ui ) {
                $("#users").val( ui.item.username );
                //$("#users").css({ backgroundImage: "url(" + ui.item.image_url + ")" });
                return false;
            },
            select: function( event, ui ) {
                $("#users").val( ui.item.username );                
                $("#users").attr('uid', ui.item.id );
                //$("#users").css({ backgroundImage: "url(" + ui.item.image_url + ")" });
                return false;
            }
        })
        .data( "autocomplete" )._renderItem = function( ul, item ) {
            return $( "<li></li>" )
                .data( "item.autocomplete", item )
                .append('<a><img style="max-width:30px;max-height:30px;" src="' + item.image_url + '" />'  + item.username + "<span>" + item.name + "</span>" + "</a>" )
                .appendTo( ul );
        };
        
        $("#users").bind("autocompletesearchcomplete", function(event, contents) {
            var users = $('#users');
            if (contents.length === 0) {
                users.removeClass("found");
            }
            else {
                users.addClass("found");
            }
            $('#showpopup form').validate().element("#users");
        });
        
    },
     
    /**
    * Validation
    */
    validation: function() {
		var $form = $('.sign #content form');
        if (!$form.length || !$form.validate) return;

        $form.validate({
            rules: {
                /*email: {
                    required: true,
                    email: true
                },*/
                password: "required",
                username: "required",
                name: "required"
            },
            messages: {
                email: "Hmm, that doesn’t look like a valid email address."
            }
        });
    },
    
    /**
     * Notification
     */
    notification: function() {
        $('.hide-notification').click(function(){
            $('.notification').slideToggle('slow');
        })
    },
    
    /**
     * Comments
     * @requires FancyCommentHandler comments.js
     * @requires FancyCommentEditHandler comments.js
     */
    comments: function() {
        var textarea = $('#content textarea[name="comment-msg"]');
        if (!textarea.length) {
            return;
        }
        textarea.FancyCommentHandler();
        $('#content a.edit-comment').FancyCommentEditHandler();
        
        $('#content a.delete-comment').live('click',function(){
            var param = {};
            param['comment_id']=$(this).attr('cid');
            if($(this).attr('cuid') != undefined && $(this).attr('cuid')!= null){
                param['commenter_id']=$(this).attr('cuid');					
            }
            var selectedRow = $(this);			
            $.post("/delete_comment.xml",param, 
                function(xml){
                    if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
                        selectedRow.parents('li').fadeOut(500, function(){
                        selectedRow.remove();
                    })
                    }
                    else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
                        alert($(xml).find("message").text());
                    }  
            }, "xml");
			return false;            
        });

        
    },

    /* Username */
    usernameSyn : function(){
        var obj = $('input#username');
        if (obj.length){
            obj.keyup(function(){
                obj.next('.username').children('strong').html($('input#username').val().replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;"));
            })
        }
    },

    /**
     * Setting - change password
     */
    changePass: function() {
        $('#change-password').find('.pass-trigger').click( function() {
            $(this).hide();
            $(this).next('ul').animate({
                height: 100
            }, 400 );
            $(this).parent('#change-password').addClass('snp-expanded');
            return false;
        })
    },

    /**
     * You menu
     */
    youSubNav: function() {
        $('#navigation .you-menu').find('.mn-you').click( function() {
            if ( $(this).parent('.you-menu').hasClass('show-subnav') ) {
                $(this).parent('.you-menu').removeClass('show-subnav');
            } else {
                $(this).parent('.you-menu').addClass('show-subnav');
            }  
            return false;
        })
        $("*:not(.you-menu)").live("click", function(){
            $('#navigation').find('.you-menu').removeClass('show-subnav');
        });
    },

    /**
     * Fancy filter
     */
     filter: function() {
        var filter = $('#filter');
        var em = filter.find('h3 em');
        
        filter.click(function() {
            $(this).toggleClass("expanded");
        });
        
        filter.find('a').click(function(e) {
            em.html($(this).text());
            e.preventDefault();
        });
     },
	rankPopup: function () {
        if($('#rankpopup').length){
            var showPopup = $('#rankpopup').dialog(Fancy.Popup.options);
            Fancy.Popup.setup(showPopup);
            
            $('#frank').click(function() {
                showPopup.dialog('open'); 
            });
            
            $('#rankpopup .button.ok').click(function() {
                showPopup.dialog('close'); 
                return false;
            });
        }
		
	},
	
	reportPopup: function () {
		var showPopup = $('#reportpopup').dialog(Fancy.Popup.options);
		Fancy.Popup.setup(showPopup);
		
		$('.report-link').click(function() {
		      var login_require = $(this).attr('require_login');
		      
		      if (typeof(login_require) != undefined && login_require != null && login_require=='true'){ 
			    require_login();
			    return false;
		      }
			showPopup.dialog('open'); 
		});
		
		$('#reportpopup .popup-btns-wrap .button.ok').click(function() {
			$('.report-confirm').show();
			$('.popup-btns-wrap').hide();
			//showPopup.dialog('close'); 
			return false;
		});
		
		$('#reportpopup .report-confirm .button.ok').click(function() {
			showPopup.dialog('close'); 
			$('.report-confirm').hide();
			$('.popup-btns-wrap').show();
			return false;
		});
		
	},	
    
	fieldFocus: function () {
			var sfEls = document.getElementsByTagName("INPUT");
			for (var i=0; i<sfEls.length; i++) {
				sfEls[i].onfocus=function() {
					this.className+=" sffocus";
					$(this).parent().addClass("hastext");
				}
				sfEls[i].onblur=function() {
					this.className=this.className.replace(new RegExp(" sffocus\\b"), "");
				}
			
			}
	},
	
	fancyBox: function () {
		
		/* This is basic - uses default settings */
			$("a.image").fancybox();

			/* Using custom settings */
			$("a#inline").fancybox({
				'hideOnContentClick': true
			});

			/* Apply fancybox to multiple items */
			$("a.group").fancybox({
				'transitionIn'	:	'elastic',
				'transitionOut'	:	'elastic',
				'speedIn'		:	600, 
				'speedOut'		:	600, 
				'overlayShow'	:	false
			});
	
	},
    
	datePicker: function () {
		$("#deal-end, #deal-start, #store_deal_expiration").datepicker();
	},


    fixStreamLinking: function () {
	    $("#content .fig-image").click(function() {
		    window.location = $(this).parents("a").attr("href");
	    });
    },
    customize: function () {
    $("#content div.customize .toggle").click(function() {
        $(this).parents(".customize").addClass("opened");
        return false;
    });
    
    $("#content div.customize .send").click(function() {
        var show_featured_items = $('#content div.customize #show_featured_items').is(':checked');
        var show_followed_adds = $('#content div.customize #show_followed_adds').is(':checked');
        var show_shown_to_you = $('#content div.customize #show_shown_to_you').is(':checked');
        var show_followed_fancyd = $('#content div.customize #show_followed_fancyd').is(':checked');
        
        var selectedRow = $(this);
        var param = {};
        param['show_featured_items']=show_featured_items;
        param['show_followed_adds']=show_followed_adds;
        param['show_shown_to_you']=show_shown_to_you;
        param['show_followed_fancyd']=show_followed_fancyd;
        
        $.post("/update_timeline.xml",param, 
            function(xml){
                if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
                    selectedRow.parents(".customize").removeClass("opened");
                    location.reload(false);

                }
                else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
                    alert($(xml).find("message").text());
                }  
        }, "xml");
        return false;
    });
},

	
	/**
	 * Select all friends checkboxes
	 */
	selectAllFriends: function () {
		if (!$("#content .friends-list").length) {
			return;
		}
		
		var all = $("#all");
		var checkboxes = $("#content .friends-list ul input[type=checkbox]");
		var all_link = $("#content .friends-list .selected a");
		
		function toggleAll() {
			if (all.is(':checked')) {
				checkboxes.attr("checked", "checked");
			} else {
				checkboxes.removeAttr("checked", "");
			}
		}
		
		all_link.bind("click", function() {
			all.attr('checked', !all.is(':checked'));
			toggleAll();
			return false;
		});
		
		all.bind("click", function() {
			toggleAll();
		});
		
		checkboxes.bind("click", function() {
			
			var checked = $("#content .friends-list ul input[type=checkbox]:checked");
			
			if (checked.length === checkboxes.length) {
				all.attr("checked", "checked");
			} else {
				all.removeAttr("checked", "");
			}
		});
	}



}

/**
 * Fancy popups default setting
 */ 
Fancy.Popup = {

    /**
     * Default options
     */
    options: { 
            open: function(event, ui) {  
                $(".ui-dialog-titlebar-close").hide(); 
                $(".ui-dialog-content.ui-widget-content").css({ 
                    width: '356px'
                }); 
            },
            autoOpen: false,
            closeOnEscape: true,
            draggable: false,
            closeText: 'Cancel'
    },
    
    /**
     * Other setting
     */
    setup: function(popup,fixed) {
        // Close by clicking on Cancel
        popup.find('.button.cancel').click(function(){
            $(".tipsy").hide();
            popup.dialog('close');
            return false;
        });
        
		// Make it draggable
		if (fixed) {
		} else {
			popup.draggable({ "handle": 'h3' }); 	
		}  
    }
}

/**
 * Fancy Lists
 */
Fancy.Lists = {

    init: function() {
        if ($('#show-add-to-list').length) {
		  $('#show-add-to-list').click(function() {
			  if ($('.figure-row.first > .figure-640.big .button.fancyd').length){
				$('.figure-row.first > .figure-640.big .button.fancyd').attr('sl',true);
				$('.figure-row.first > .figure-640.big .button.fancyd').click();
			  }
			  else{
				$('.figure-row.first > .figure-640.big .button.fancy').attr('sl',true);
				$('.figure-row.first > .figure-640.big .button.fancy').click();
			  }
			  //addToListPopup.dialog('open');
			  
			  return false;
		  });
        }
		
        if ($('#show-delete-to-list').length) {
		  var addToListPopup = $('#add-to-list').dialog(Fancy.Popup.options);
		  Fancy.Popup.setup(addToListPopup);
		  $('#show-delete-to-list').click(function() {
			  addToListPopup.dialog('open');
			  
			  return false;
		  });
        }
		
		return;
        //Fancy.Lists.addToList();
        //Fancy.Lists.addToList2();
		//Fancy.Lists.editList();
    },

    
    /**
     * Add to list dialog
     */
    addToList: function() {
     
        if (!$("#add-to-list").length) {
            return;
        }
        
        var addToListPopup = $('#add-to-list').dialog(Fancy.Popup.options);
        Fancy.Popup.setup(addToListPopup);
        
        // Open by clicking on the link
        $('#show-add-to-list').click(function() {
			if ($('.button.fancyd').length){
			  $('.button.fancyd').attr('sl',true);
			  $('.button.fancyd').click();
			}
			else{
			  $('.button.fancy').attr('sl',true);
			  $('.button.fancy').click();
			}
            //addToListPopup.dialog('open');
			
            return false;
        });
        
        
        // Add new list
        var list = $('#add-to-list ul');
        var input = $("#add-to-list .create-new")
        var submit_add_to_list = false;
        input.bind("change", function() {
            if(submit_add_to_list)
                return false;
            var listName = $(this).val().trim();
            if (listName === '') {
                return;
            }
            var param = {};
            param['list_name'] = listName;
            $.post("/create_list.xml",param, 
                function(xml){
                    if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
                        var list_id = $(xml).find("list_id").text();
                        if($("#add-to-list #"+list_id).length ){
                            $("#add-to-list #"+list_id).attr('checked',true);
                        }
                        else{
                            var row = $('<li><label for="' +  list_id + '"><input type="checkbox" id="' + list_id + '" checked="checked" /> ' + listName + ' </label></li>');
                            row.appendTo(list);                               
                        }
                    }
                    else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
                        alert($(xml).find("message").text());
                    }  
            }, "xml");
        });
        
        // Fancy
        $('#add-to-list input[type=checkbox]').live("change", function() {
            $(".figure-row.first .button.fancy, #sidebar .button.fancy").removeClass("fancy").addClass("fancyd").html("<span><i></i></span>Fancy'd");
        });
        
        // Submit the form
        var form = $('#add-to-list form');
        form.bind("keypress", function(e) {
            if (e.keyCode === 13) {
                if ($.browser.msie && parseInt($.browser.version, 10) < 9) {
                } else {
                    input.trigger('change');
                }
                input.val('');
                return false;
            }
        });
        
        $('#add-to-list .send').click(function() {
            
            submit_add_to_list = true;
            var login_require = $('#add-to-list').attr('require_login');
            if (typeof(login_require) != undefined && login_require != null && login_require=='true'){
                  require_login();
            }
            var list_ids = '';
            $("#add-to-list input:checked").each(function(){
                if(list_ids.length>0){
                    list_ids = list_ids+","+$(this).attr('id');
                }
                else{
                    list_ids = ""+$(this).attr('id');
                }
            });
            
            //if(list_ids.length<=0)
                //return false;

            var param = {};
            
            var otype = $('#add-to-list').find('h3').attr('otype');
            
            
            var new_list = $('#add-to-list .create-new').val();
            var new_list_ph = $('#add-to-list .create-new').attr('placeholder');

            if(list_ids.length<=0 && (new_list==new_list_ph || new_list.length<=0))
                return false;
            
            //var existing_lists = $('#add-to-list .existing_list')
            
            //for (i=0; i<existing_lists.length; i++){
            //}
            
            if (new_list != new_list_ph){
                param['list_name'] = new_list;
            }

            param['list_ids']=list_ids;
            if (otype == 'nt'){
                param['type']='nt';
                param['oid'] = $('#add-to-list').find('h3').attr('oid');
                param['ooid'] = $('#add-to-list').find('h3').attr('ooid');
                param['tid'] = $('#add-to-list').find('h3').attr('tid');
            }
            else if (otype == 'p'){
                param['type']='p';
                param['oid'] = $('#add-to-list').find('h3').attr('oid');
                param['ooid'] = $('#add-to-list').find('h3').attr('ooid');				
            }
            else if (otype == 't'){
                param['type']='t';
                param['oid'] = $('#add-to-list').find('h3').attr('oid');
            }
            $.post("/add_to_list.xml",param, 
                function(xml){
                    submit_add_to_list = false;
                    if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
                        //if($(xml).find("list_id").length>0){
                            //var list_id = $(xml).find("list_id").text();
                            //if(!$("#add-to-list #"+list_id).length ){
                                //var row = $('<li><label for="' +  list_id + '"><input type="checkbox" id="' + list_id + '" /> ' + listName + ' </label></li>');
                                //row.appendTo(list);                               
                            //}
                        //}
                        addToListPopup.dialog('close');
                        alert('Added!');
                    }
                    else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
                        alert($(xml).find("message").text());
                    }  
            }, "xml");
            return false;
        });
    },    
    
/**
     * Add to list dialog
     */
    addToList2: function() {

        // Add new list 2
        var list = $('#add-to-list-2 ul');
        var input = $("#add-to-list-2 .create-new")
        input.bind("change", function() {
            var listName = $(this).val().trim();
            if (listName === '') {
                return;
            }
            var param = {};
            param['list_name'] = listName;
            $.post("/create_list.xml",param, 
                function(xml){
                    if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
                        var list_id = $(xml).find("list_id").text();
                        if($("#add-to-list-2 #"+list_id).length ){
                            $("#add-to-list-2 #"+list_id).attr('checked',true);
                        }
                        else{
                            var row = $('<li><label for="' +  list_id + '"><input type="checkbox" id="' + list_id + '" checked="checked" /> ' + listName + ' </label></li>');
                            row.appendTo(list);                               
                        }
                    }
                    else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
                        alert($(xml).find("message").text());
                    }  
            }, "xml");
        });

        var form = $('#add-to-list-2').closest('form');
        form.bind("keypress", function(e) {
            if (e.keyCode === 13) {
                if ($.browser.msie && parseInt($.browser.version, 10) < 9) {
                } else {
                    input.trigger('change');
                }
                input.val('');
                return false;
            }
        });

    },  
    
    /**
     * Edit list dialog
     */    
    editList: function() {
     
        if (!$("#edit-list").length) {
            return;
        }
     
        var editListPopup = $('#edit-list').dialog(Fancy.Popup.options);
        Fancy.Popup.setup(editListPopup);
        
        // Open by clicking on the link
        $('#show-edit-list').click(function() { 
            editListPopup.dialog('open'); 
            return false;
        });
        
        // Delete
        $('#edit-list p.delete a').click(function() { 
            if (confirm("Delete this list?")) {
                
                var param = {};
                var lid = $('#edit-list p.delete a').attr('lid');
                var oid = $('#edit-list p.delete a').attr('oid');
                
                var return_url = $(this).attr('return_url');
                param['lid'] = lid;
                param['oid'] = oid;
                $.post("/delete_list.xml",param, 
                    function(xml){
                        if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
                            location.href=return_url;
                        }
                        else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
                        }  
                }, "xml");
            }
            return false;
        });
        
        // Submit the form
       $('#edit-list .send').click(function() {
            var param = {};
            var title = $('#edit-list input#list-title').val();
            var oid = $('#edit-list').attr('oid');
            var lid = $('#edit-list').attr('lid');
            
            var privacy_mode = $('#edit-list #list-privacy:checked').val();
            
            param['title'] = title;
            param['lid'] = lid;
            param['oid'] = oid;
            param['privacy_mode'] = privacy_mode;
            
            $.post("/update_list.xml",param, 
                function(xml){
                    if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
                        editListPopup.dialog('close');
                    }
                    else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
                    }  
            }, "xml");
            
            return false;
        });
    }
}


/**
 * Fancy Add To list Popup
 */
Fancy.AddToListPopup = {
	
	/**
 	* Options
 	*/
	options: { 
			open: function(event, ui) {  
				$(".ui-dialog-titlebar-close").hide(); 
				$(".ui-dialog-content.ui-widget-content").css({ 
					width: '560px'
				}); 
				$("body").css({ overflow: 'hidden' })
			},
			beforeClose: function(event, ui) {
				$("body").css({ overflow: 'inherit' })
			},
			autoOpen: false,
			closeOnEscape: true,
			draggable: false,
			closeText: 'Cancel',
			modal: true
	},	
	
	init: function() {
		if (!$(".fancy-popup").length) return;

		Fancy.AddToListPopup.setPopup();
		Fancy.AddToListPopup.categories();
		Fancy.AddToListPopup.createCategory();
		Fancy.AddToListPopup.submit();
		Fancy.AddToListPopup.commentTyped();
	},
	
	/**
	 * Set up popup
	 */
	setPopup: function() {
		var add_timer, stop_timer = false, event = null;
		var fancyPopup = $('.fancy-popup').dialog(Fancy.AddToListPopup.options);

		Fancy.Popup.setup(fancyPopup, true);

		$('.button.fancyd, .button.fancy').live('click',function(e) {
			var $this = $(this);
			var selectedRow = $this;
			var tid  = $this.attr('tid') || null;
			var rtid = $this.attr('rtid') || null;
			var sl   = $this.attr('sl') || null;
			var checkbox_url = '/_get_list_checkbox.html?t='+(new Date).getTime();

			e.preventDefault();

			$this.addClass('loading');
				  
			if(tid != null)  checkbox_url += '&tid='+tid;
			if(rtid != null) checkbox_url += '&rtid='+rtid;
			if(sl != null)   checkbox_url += '&sl='+sl;
				  
			var no_check_point = false;
			var img_src = $this.attr('item_img_url');
			var obj_name = $this.attr('item_name');
				  
			if (!img_src) {
				img_src = $this.data('image-src') || $this.parent().find('.fig-image img').attr('src');
				no_check_point=true;
			}

			if (!obj_name) obj_name = $this.parent().find('figcaption').text();

			$.get(checkbox_url, function(data) {
				$this.removeClass('loading');

				$('.item-categories input#new-category').val('');

				$('.fancy-popup')
					.find('.list-categories ul').html(data).end()
					.find('.obj-name').text(obj_name).end()
					.find('.item-image img').attr('src', img_src);
					
				$('.item-categories')
					.find('input#new-category').val('').end()
					.find('.button.add-to-list').html(gettext('Update lists')).removeClass('remove').end()
					.find('.button.unfancy').html(gettext('Unfancy')).removeClass('remove');
					
				var show_lists = true;
				if (sl) $this.removeAttr('sl');

				if (!rtid) {
					rtid = $('.fancy-popup').find('.list-categories ul').find('input.rtid').val();

					$this
						.attr('rtid', rtid)
						.removeClass('fancy')
						.addClass('fancyd')
						.html("<span><i></i></span>Fancy'd");

					$('.item-categories')
						.find('.button.unfancy').hide().end()
						.find('.button.add-to-list').html(gettext('Add to lists')).removeClass('remove').css('width','300px');

					if(!sl) $('.fancyd-item .not-interested').show();
					if($('.fancy-popup').find('.list-categories ul input.not-show-lists').length) show_lists = false;
				} else {
					$('.item-categories')
						.find('.button.unfancy').show().end()
						.find('.button.add-to-list').css('width','125px');

					$('.fancyd-item .not-interested').hide();
				}

				if(tid)  $('.item-categories .button').attr('tid',tid);
				if(rtid) $('.item-categories .button').attr('rtid',rtid);

				$('.button-fancy-popup').removeClass('button-fancy-popup');

				if (show_lists) {
					fancyPopup.dialog('open');
					$this.addClass('button-fancy-popup');
					event = e;
					$('.ui-widget-overlay').one('click', function() {
						$('.button-fancy-popup').removeClass('button-fancy-popup');
						fancyPopup.dialog('close');
					});
				}

				$this.trigger('fancy');
			});
		});

		$('.btn-close').click(function(){
			$('.button-fancy-popup').removeClass('button-fancy-popup');
			fancyPopup.dialog('close'); 
			return false;
		});

		$('.fancyd-item a.close-link').click(function(){
			var param = {show_lists:'false'};

			$.post(
				'/set_show_lists_after_fancyd.xml',
				param, 
				function(xml){
					var status_code = +($(xml).find('status_code').text() || 0);
					if (status_code == 1) {
						$('.item-categories form').submit();
						$('.button-fancy-popup').removeClass('button-fancy-popup');
					}
				},
				'xml'
			);

			return false;
		});

		$('.item-categories .button.unfancy').click(function() {
			var $this = $(this), selectedRow = $this;
			var tid  = $this.attr('tid') || null;
			var rtid = $this.attr('rtid') || null;

			if (!tid || !rtid) return false;

			if (confirm('Are you sure you want to unfancy this?')){
				$.post(
					'/delete_reaction_tag.xml',
					{rtid:rtid},
					function(xml){
						var status_code = +($(xml).find('status_code').text() || 0);
						if (status_code === 1) {
							$('.button-fancy-popup')
								.removeAttr('rtid')
								.removeClass('fancyd')
								.addClass('fancy')
								.html('<span><i></i></span>Fancy it');

							$('.item-categories form').submit();
							$('.button-fancy-popup').removeClass('button-fancy-popup');

							$this.trigger('unfancy');
						}
					},
					'xml'
				);
			} else {
				$('.item-categories form').submit();
				$('.button-fancy-popup').removeClass('button-fancy-popup');
			}

			return false;
		});

        $('.item-categories .button.add-to-list').click(function() {
			var selectedRow = $(this);
			var tid = null;
			if (!$(this).attr('tid') == undefined)
			  return false;
			tid = $(this).attr('tid');
			var rtid = null;
			if ($(this).attr('rtid') != undefined)
			  rtid = $(this).attr('rtid');

			var list_name = $(".item-categories input#new-category").val();
			var list_name_holder = $(".item-categories input#new-category").attr('placeholder');
			var add_new_cat = false;
			if (list_name != null && typeof(list_name)!= undefined && list_name.length > 0 && list_name != list_name_holder)
			  add_new_cat = true;

			if(selectedRow.hasClass('remove-me') && rtid != null && !add_new_cat){
				var param = {};
				param['tid']=tid;
				param['rtid']=rtid;
				$('.item-categories form').submit();

				$.post("/remove_from_all_lists.xml",param, 
				  function(xml){
					
					if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
					  //btn.removeClass("fancy").addClass("fancyd");
					  //$('.button-fancy-popup').removeAttr('rtid');
					  //$('.button-fancy-popup').removeClass("fancyd").addClass("fancy");
					  var good = $('<span class="remove_me">good</span>');
					  $(event.target).parent().append(good);
					  
					  setTimeout("$('.remove_me').fadeOut()", 500);

					}
					else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
					  alert($(xml).find("message").text());
					}  
					$('.button-fancy-popup').removeClass('button-fancy-popup');
				}, "xml");
			  
			}
			else{
			  var lists = $(".list-categories ul li.selected");
				
			  if (lists .length == 0 && !add_new_cat){
				$('.item-categories form').submit();
				$('.button-fancy-popup').removeClass("fancy").addClass("fancyd");
				return false;
			  }

			  if (lists .length > 0 || add_new_cat){
				var list_ids = "";
				lists.each(function(){
				  if (list_ids.length>0)
					list_ids =list_ids+","+ $(this).find('input').attr('id');
				  else
					list_ids = ""+$(this).find('input').attr('id')
				});

				$('.item-categories form').submit();
				var param = {};
				param['tid']=tid;
				param['list_ids']=list_ids;
				if(add_new_cat)
				  param['list_name']=list_name;
				$.post("/add_tid_to_list.xml",param, 
				  function(xml){
					
					if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
					  //btn.removeClass("fancy").addClass("fancyd");
					  var rtid = $(xml).find("rtid").text();
					  
					  $('.button-fancy-popup').removeClass("fancy").addClass("fancyd");
					  $('.button-fancy-popup').attr('rtid',rtid);

					  var good = $('<span class="remove_me">good</span>');
					  $(event.target).parent().append(good);
					  
					  setTimeout("$('.remove_me').fadeOut()", 500);

					}
					else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
					  alert($(xml).find("message").text());
					}  
					$('.button-fancy-popup').removeClass('button-fancy-popup');
				}, "xml");
				
			  }
			  
			}
			return false;
		});
               
		// Center on resize
		$(window).resize(function() {
			fancyPopup.dialog('option', 'position', 'center');
			fancyPopup.dialog('close'); 
			$('.button-fancy-popup').removeClass('button-fancy-popup');
		});
	},
	
	categories: function() {

		$('.list-categories li input').live("change", function() {
			
			var li = $(this).parent().parent();
			
			var lid = li.find('input').attr('id');
			var tid = $('.item-categories .button.add-to-list').attr('tid');
			if (li.hasClass("selected")) {
				var param = {};
				param['tid']=tid;
				param['list_ids']=''+lid;
				$.post("/remove_item_from_lists.xml",param, 
				  function(xml){					
					if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
					}
					else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
					}  
				  li.removeClass('selected');
				  Fancy.AddToListPopup.numberOfCategories();

				}, "xml");
				
			} else {
				var param = {};
				param['tid']=tid;
				param['list_ids']=''+lid;
				$.post("/add_item_to_lists.xml",param, 
				  function(xml){					
					if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
					}
					else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
					}  
					li.addClass('selected');
					Fancy.AddToListPopup.numberOfCategories();
				}, "xml");


			}
		});	
	},
	
	createCategory: function() {
		
		var list = $(".fancy-popup .list-categories ul");
		var input = $(".fancy-popup #new-category");
		
		function addCategory(input) {
			var listName = input.val();
			if (listName == '') {
				return;
			}
			var param = {};
			param['list_name']=listName;
            $.post("/create_list.xml",param, 
			  function(xml){
				if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
				  var lid = $(xml).find("list_id").text();
				  if(list.find('input#'+lid).length){
					var input_row =  list.find('input#'+lid);
					input_row.attr('checked','checked');
					input_row.parent().parent('li').addClass('selected');
					
				  }
				  else{
					var row = $('<li class="selected" style="background-color: #fff6a0;"><label for="' +  lid + '"><input type="checkbox" id="' + lid + '" checked="checked" /> ' + listName + '</label></li>');
					row.prependTo(list); 
					list.animate({scrollTop:0}, 'fast');
					row.animate({ backgroundColor: "#fff" }, 500);
					
				  }

				  var param2 = {};
				  var tid = $('.item-categories .button.add-to-list').attr('tid');
				  param2['tid']=tid;
				  param2['list_ids']=''+lid;
				  $.post("/add_item_to_lists.xml",param2, 
					function(xml){					
					  if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==1) {
					  }
					  else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
					  }  
				  }, "xml");				  
				  input.val('');
				  
				  Fancy.AddToListPopup.numberOfCategories();
				}
				else if ($(xml).find("status_code").length>0 && $(xml).find("status_code").text()==0) {
				  alert($(xml).find("message").text());
				}  
            }, "xml");

		}
		
		input.bind("keypress", function(e) {
			if (e.keyCode === 13) {
				addCategory($(this));
			}
		});
		
	},
	
	numberOfCategories: function() {
	
		var button = $(".fancy-popup .button.add-to-list");
		var has_rtid = false;
		if(button.attr('rtid') != undefined)
		  has_rtid=true;
		if ($('.list-categories li.selected').length == 0 && has_rtid) {
			//button.html('Remove from all lists').addClass('remove');
			button.addClass('remove-me');
		} else {
			//button.html('Update lists').removeClass('remove');
			button.removeClass('remove-me');
		}
	
	},
	
	submit: function() {
		// Prevent Enter
		var form = $('.fancy-popup form');
		form.bind("keypress", function(e) {
			if (e.keyCode === 13) {
				return false;
			}
		});
		
		form.bind("submit", function(e) {
			$('.fancy-popup').dialog('close');
			return false;
		});
	},
	
	commentTyped: function() {
	
		$('.item-categories #comment').live("change", function() {
			var input = $(this);

			if (input.val() !== input.attr('placeholder')) {
				input.addClass('typed');
			} else {
				input.removeClass('typed');
			}
		});
	}
};

// template
jQuery.fn.template = function(args) {
	if(!args) args = {};
	var html = this.html().replace(/##([A-Z0-9_]+)##/g, function(whole,name){
		return args[name] || '';
	});

	return jQuery(html);
};


Fancy.Cart = {
	addItem : function(args) {
		var $popup = $('#cart_popup'), $ul = $popup.find('>ul'), $summary = $popup.find('>.summary');
		var $item  = $('#cartitem-'+args['ITEMCODE']), price, quantity;

		quantity = parseInt(args['QUANTITY']) || 0;
		price    = parseFloat(args['PRICE']) || 0;

		if($item.length) {
			quantity += parseInt($item.data('quantity'));
			price += parseFloat($item.data('price'));

			$item
				.find('span.info span').text(quantity).end()
				.find('span.price').text('$'+price.toFixed(2));
		} else {
			$item = $popup.find('>script[type="fancy/template"]').template(args).appendTo($ul);
		}

		$item.data('options', args['OPTIONS']||'').data('price', price).data('quantity', quantity);

		this.update();
	},

	update : function() {
		var count = 0, price = 0;

		$('#cart_popup > ul > li').each(function(){
			var $this = $(this);
			var q = parseInt($this.data('quantity')) || 0;
			var p = parseFloat($this.data('price')) || 0;

			if(q == 0) {
				$item.remove();
				return;
			}

			count += q;
			price += p;
		});

		if(count) {
			$('#cart em').show().text(count);
			$('#cart_popup > .summary')
				.find('>strong').text(count+' item'+(count>1?'s':'')).end()
				.find('>span').text('Total: $'+price.toFixed(2));
		} else {
			$('#cart em').hide();
			$('#cart_popup > .summary')
				.find('>strong').text('No item').end()
				.find('>span').text('Total: $0');
		}
	},

	openPopup : function() {
		if(!$('#cart').is(':visible')) $('#cart').show();
		if(!$('#cart_popup').is(':visible')) $('#cart').mouseenter();
	},

	hidePopup : function() {
		if($('#cart_popup').is(':visible')) $('#cart').mouseleave();
	}
};

// Cart popup layer
jQuery(function(){
	var $link = $('#cart'), $layer = $('#cart_popup'), timer = null;

	function hideCartPopup(){
		$layer.hide();
		$link.removeClass('active');
	};

	$link
		.mouseenter(function(){
			clearTimeout(timer);
			$link.addClass('active');
			$layer.show().css('top', $link[0].offsetTop + $link[0].offsetHeight + 'px');
		})
		.mouseleave(function(){
			timer = setTimeout(hideCartPopup, 100);
		});

	$layer
		.mouseenter(function(){
			clearTimeout(timer);
		})
		.mouseleave(function(){
			timer = setTimeout(hideCartPopup, 100);
		});

    Fancy.init();
    Fancy.Lists.init();
	Fancy.AddToListPopup.init();
});

// filter
$('#filter').each(function() {
	var filter = $(this);
	filter.find('h3')
	.parent().find('a').click(function() {
		filter.toggleClass("expanded").find('em').text($(this).text());
		location.href = $(this).attr('href');
	    return false;
	});
	//filter.find('li').last().addClass("last").find('a').css({ behavior: "url(_ui/js/PIE.htc)" });
});

function show_overlay_on_timeline() {
	$('#content')
		.delegate(
			'.figure-product',
			{
				mouseover : function(){
					var $this = $(this), $timeline = $this.find('.timeline');
					if (!$timeline.length) return;
					
					var $img = $this.find('img'), pos = $img.position(), w = $img.width(), h = $img.height();
					$timeline
						.filter(':hidden').css('opacity',0).end()
						.show()
						.stop()
						.css({width:(w-14)+'px',height:(h-12)+'px',top:pos.top+'px',left:pos.left+'px'})
						.fadeTo(200,1);
					
					if (h < 110) $timeline.find('.btn-share').hide();
				},
				mouseleave : function(event){
					var $timeline = $(this).find('.timeline').stop().fadeTo(100,0,function(){$timeline.hide()});
				}
			}
		);
}

show_overlay_on_timeline();

//sign-in
jQuery(function($){
	var $signin = $('.mn-joinnow');
	if ($signin.length > 0) {
		$signin.click(function(event){
			var $rloc = $('#require_login_overaly_container').click(function(event){
				var $target = $(event.target);
				if ($target.is('.overlay-bg')) { $rloc.hide(); return false; }
				if ($target.is('a,button') || $target.parent().is('a,button')) return true;
				return false;
			});
			var $rloc = $('#require_login_overaly_container');
			$rloc.show(); 
			return false;
		});
	}
});


// Language selector
jQuery(function($){
	var $link = $('#lang_link'), $popup = $($link.attr('href')), $buttons = $popup.find('li > a'), $doc = $(document), c = 'active';

	function close() { $link.removeClass(c); $popup.removeClass(c); };

	$link.on('click', function(){
		$doc.off('click.lang_popup');
		if ($link.hasClass(c)) {
			close();
		} else {
			$link.addClass(c);
			$popup.addClass(c);
			$doc.one('click.lang_popup', close);
		}
		return false;
	});

	$buttons.on('click', function(){
		var $this = $(this);
		$link[0].firstChild.nodeValue = $this.text();
		document.cookie = 'lang='+$this.attr('href').substr(1)+'; path=/;';
		location.reload();
		return false;
	});

	// close language popup when mouseover main menu
	$('#navigation-new > ul > li > a').mouseover(function(){
		if ($popup.is(':visible')) $link.click();
	});
});

// CSRF
(function($){
	$(document).ajaxSend(function(event, xhr, settings) {
		function getCookie(name) {
			var cookies = document.cookie.split(';');
			for (var i=0,c=cookies.length; i < c; i++) {
				var cookie = $.trim(cookies[i]);
				// Does this cookie string begin with the name we want?
				if (cookie.substring(0, name.length + 1) == (name + '=')) {
					return decodeURIComponent(cookie.substring(name.length + 1));
				}
			}
			return null;
		}
		function sameOrigin(url) {
			// url could be relative or scheme relative or absolute
			var host = document.location.host; // host + port
			var protocol = document.location.protocol;
			var sr_origin = '//' + host;
			var origin = protocol + sr_origin;
			// Allow absolute or scheme relative URLs to same origin
			return (url == origin || url.slice(0, origin.length + 1) == origin + '/') ||
				(url == sr_origin || url.slice(0, sr_origin.length + 1) == sr_origin + '/') ||
				// or any other URL that isn't scheme relative or absolute i.e relative.
				!(/^(\/\/|https?:).*/.test(url));
		}

		if ((settings.type.toUpperCase() == 'POST') && sameOrigin(settings.url)) {
			var val_cookie = getCookie('csrftoken');
			if(typeof(val_cookie) != undefined && val_cookie != null && val_cookie.length > 0 )
				xhr.setRequestHeader("X-CSRFToken", val_cookie);
		}
	});
})(jQuery);

// Infiniteshow
(function($){
	var options;
	var defaults = {
		dataKey : '',
		loaderSelector : '#infscr-loading', // an element to be displayed while calling data via ajax.
                itemSelector : '#content .inside-content .figure-row',
		nextSelector   : 'a.btn-more', // elements which head for next data.
		streamSelector : '.stream',
		prepare   : 4000, // indicates how many it should prepare (in pixel)
		dataType  : 'html', // the type of ajax data.
		success   : function(data){}, // a function to be called when the request succeeds.
		error     : function(){ }, // a function to be called if the request fails.
		comeplete : function(xhr, st){} // a function to be called when the request finishes (after success and error callbacks are executed).
	};

	$.infiniteshow = function(opt) {
		options = $.extend({}, defaults, opt);

		var $win = $(window),
		    $doc = $(document),
		    ih   = $win.innerHeight(),
			$url = $(options.nextSelector).hide(),
			$str = $(options.streamSelector),
			loc  = $str.attr('loc'),
			url  = $url.attr('href'),
			bar  = $('div.pagination'),
			ttl  = 5 * 60 * 1000,
			calling = false;

		var keys = {
			stream  : 'fancy.'+options.dataKey+'.stream.'+loc,
			latest  : 'fancy.'+options.dataKey+'.latest.'+loc,
			nextURL : 'fancy.'+options.dataKey+'.nexturl.'+loc
		};

		(function(){
			var data    = $.jStorage.get(keys.stream, ''),
				latest  = $.jStorage.get(keys.latest, ''),
				nextURL = $.jStorage.get(keys.nextURL, '');

			if(!data || !latest || !nextURL){
				for(var name in keys) $.jStorage.deleteKey(keys[name]);
				return;
			}

			$str.html(data).attr('ts',latest);
			$url.attr('href', url=nextURL);
		})();

		function docHeight() {
			var d = document;
			return Math.max(d.body.scrollHeight, d.documentElement.scrollHeight);
		}

		$win.on('resize.infiniteshow', function(){
			ih = $win.innerHeight();
			$win.trigger('scroll.infiniteshow');
		});

		$win.on('scroll.infiniteshow', function(){
			if (calling || !url || options.disabled) return;

			var rest = docHeight() - $doc.scrollTop();
			if (rest > options.prepare) return;

			var $loader = $(options.loaderSelector).show();
			
			calling = true;

			$.ajax({
				url : url,
				dataType : options.dataType,
				success : function(data, st, xhr) {
					var $sandbox = $('<div>'),
					    $contentBox = $(options.itemSelector).parent(),
						$next, $rows;

					$sandbox[0].innerHTML = data.replace(/^[\s\S]+<body.+?>|<((?:no)?script|header|nav)[\s\S]+?<\/\1>|<\/body>[\s\S]+$/ig, '');
					$next = $sandbox.find(options.nextSelector);
					$rows = $sandbox.find(options.itemSelector);

					$contentBox.append($rows);
					if ($next.length) {
						url = $next.attr('href');
						$url.attr({
							'href' : $next.attr('href'),
							'ts'   : $next.attr('ts')
						});
					} else {
						url = '';
					}

					$loader.hide();
					calling = false;

					// save stream data
					$win.trigger('savestream.infiniteshow');

					// Triggers scroll event again to get more data if the page doesn't have enough data still.
					$win.trigger('scroll.infiniteshow');

                    if (options.post_callback != null) {
                        options.post_callback();
                    }
				},
				error : function(xhr, st, err) {
					$loader.hide();
					calling = false;
					url = '';
				}
			});
		});

		$win.on('savestream.infiniteshow', function(){
			if(!$str.length) return;

			var data = $str.html().replace(/>\s+</g,'><');
			$.jStorage.set(keys.stream, data, {TTL:ttl});
			$.jStorage.set(keys.latest, $str.attr('ts'), {TTL:ttl});
			$.jStorage.set(keys.nextURL, url, {TTL:ttl});
		});

		$win.trigger('scroll.infiniteshow');
	};

	$.infiniteshow.option = function(name, value) {
		if (typeof(value) == 'undefined') return options[name];
		options[name] = value;

		if (name == 'disabled' && !value) $win.trigger('scroll.infiniteshow');
	};
})(jQuery);

// i18n 
jQuery(function($){
	// "Browse" menu
	var $browse_div = $('div.mn-browse-div'), even_max=0, odd_max=0;
	$browse_div
		.find('.things')
			.find('li:even')
				.each(function(){ var w = $(this).css('width','auto').width(); if(even_max < w) even_max = w })
				.each(function(){ $(this).width(even_max); })
			.end()
			.find('li:odd')
				.each(function(){ var w = $(this).css('width','auto').width(); if(odd_max < w) odd_max = w })
				.each(function(){ $(this).width(odd_max); })
			.end()
			.find('ul').width(even_max+odd_max+20).end()
		.end()
		.find('.separator').css('left', (even_max+odd_max+55)+'px').end()
		.width(even_max+odd_max+$browse_div.find('.right').width()+95);
});

// support tap interface
(function($){
	var $links = $('a.mn-browse,a.mn-you');
	$links
		.on('focus', function(){
			this.parentNode.className = 'hover';
		})
		.on('blur', function(){
			this.parentNode.className = '';
		})
		.on('touchstart', function(){
			if(this.parentNode.className == 'hover'){
				return true;
			}else{
				this.focus();
				return false;
			}
		});
})(jQuery);

jQuery(function($){
	// close icon
	(function(){
        var $ir = $('#invitation-reminder');

        if (document.cookie.match(new RegExp('\\b' + 'invitation-reminder' + '=1'))) return;

        $ir.show();

		$('.icon.close_').click(function(){
			var $this = $(this), $target;

			$target = $this.attr('href')?$($this.attr('href')):$this.parent();
			$target.hide();
            var expire = new Date();
            expire.setDate(expire.getDate() + 14);
            document.cookie = 'invitation-reminder' + '=1; path=/; expires='+expire.toUTCString();
			return false;
		});
	})();

	// notification bar
	(function(){
		var $nb = $('#notification-bar'), $fg = $nb.find('>div.for-general'), uid = $fg.attr('uid'), $ir = $('#invitation-reminder');

		if (!$fg.length) return;
		if ($ir.length && $ir.is(':visible')) return;
		if (document.cookie.match(new RegExp('\\b'+uid+'=1'))) return;

		var $container = $('#header+.container').animate({'padding-top':'+=55px'},'fast');

		$nb
			.find('>div').hide().end()
			.find('>div.for-general').show().end()
			.slideDown('fast')
			.find('button.close')
				.click(function(){
					$nb.slideUp('fast').filter(':not(.top)').parent({'padding-top':'-=20px'}, 'fast');
					$container.animate({'padding-top':'-=55px'}, 'fast');

					// set cookie to avoid to bother users
					var expire = new Date();
					expire.setDate(expire.getDate() + 7);
					document.cookie = uid+'=1; path=/; expires='+expire.toUTCString();
				})
			.end()
			.filter(':not(.top)')
			.parent().animate({'padding-top':'+=20px'}, 'fast');
	})();

	// Share button
	(function(){
		var $fancy_share = $('#fancy-share');
		var $comment_share = $('#fancy-share.comment-share');

		$('.timeline .btn-share').live('click', function(){
			$fancy_share.trigger('show', [this]);
			return false;
		});

		$('.btn-comment-share').live('click', function(){
			$comment_share.trigger('show', [this]);
			return false;
		});

		$('#show-someone').click(function(){
			$fancy_share.trigger('show', [this]);
			return false;
		});

		var $frm  = $fancy_share.find('div.email-frm'),
		    $name = $frm.find('b.name').remove(),
		    $list = $fancy_share.find('ul.user-list'),
		    $item = $list.find('> li').remove(),
			$add  = $frm.find('.add'),
			$inp  = $frm.find('input:text'),
			txt_add = $add.text().split('|'),
			timer, prev_val = '';

		$add.text(txt_add[0]);
		$frm.click(function(event){
			if ($(event.target).is('span.add,div.email-frm')) {
				$add.hide();
				$inp.show().val('').focus();
				prev_val = '';
				return false;
			}
		});

		$inp
			.blur(function(){
				$add.show();
				$list.hide();
				$inp.hide().val('');
			})
			.keydown(function(event){
				switch(event.keyCode) {
					case 8: // backspace
						if ($inp.val().length != 0) return true;
						var $names = $frm.find('b.name');
						if ($names.length > 0) $names.eq(-1).remove();
						return false;
					case 9: // tab
					case 13: // enter
					case 32: // space
					case 186: // ';'
					case 188: // ','
						if ($inp.val().indexOf('@') > 0) {
							setTimeout(function(){
								var email = $.trim($inp.val());
								$inp.val('');
								$name.clone()
									.prepend(document.createTextNode(email))
									.attr('email', email)
									.insertBefore($add);

								$add.text(txt_add[1]).click();
							}, 10);
						} else {
							if (event.keyCode == 9 && $list.is(':hidden')) return true;
							$list.trigger('key.enter');
						}
						return false;
					case 38: $list.trigger('key.up'); return false;
					case 40: $list.trigger('key.down'); return false;
				}

				clearTimeout(timer);

				setTimeout(function(){
					var val = $.trim($inp.val());

					if (!val || val == prev_val) return;

					prev_val = val;

					if (val.indexOf('@') >= 0) return $list.hide();

					$list.hide().html('');

					function request() {
						$.ajax({
							type : 'get',
							url  : '/search-users.json',
							data : {'term':val},
							dataType : 'json',
							success  : function(json) {
								if (val != $.trim($inp.val())) return $list.hide();
								if (json && json.length) {
									for(var i=0,c=json.length; i < c; i++) {
										$item.clone()
											.attr('uid', json[i].id)
											.attr('username', json[i].username)
											.find('img').attr('src', json[i].image_url).end()
											.find('b').text(json[i].name).end()
											.find('small').text('@'+json[i].username).end()
											.appendTo($list);
									}
									$list.show().find('>li:first').addClass('on');
								} else {
									$list.hide();
								}
							}
						});
					}

					timer = setTimeout(request, 100);

				}, 0);
			});

		$list
			.on('key.up key.down', function(event){
				if ($list.is(':hidden')) return false;

				var $items = $list.children('li'), up = (event.namespace=='up'), idx = Math.min(Math.max($items.filter('.on').index()+(up?-1:1),0), $items.length-1);
				var $on = $items.removeClass('on').eq(idx).addClass('on'), bottom;

				if (up) {
					if (this.scrollTop > $on[0].offsetTop) this.scrollTop = $on[0].offsetTop;
				} else {
					bottom = $on[0].offsetTop - this.offsetHeight + $on[0].offsetHeight;
					if (this.scrollTop < bottom) this.scrollTop = bottom;
				}
			})
			.on('key.enter', function(){
				$list.children('li.on').mousedown();
			})
			.delegate('li', 'mousedown', function(){
				var $item = $(this);
				$name.clone()
					.prepend(document.createTextNode($item.find('b').text()))
					.attr('uid', $item.attr('uid'))
					.attr('username', $item.attr('username'))
					.insertBefore($add);

				$add.text(txt_add[1]);
				setTimeout(function(){ $inp.val('');$frm.click(); }, 10);

				$list.hide();
			});
		
		$comment_share
			.on('show', function(event, btn){
				var $this=$(this).show(), $btn=$(btn), tid=$btn.attr('tid'), url, enc_url, username, fullname, uimage;
				var cmt_element = $btn.parents('li').children('p');
				var cmt_txt = cmt_element.text();

				uimage = $btn.attr("uimage");
				username = $btn.attr("username");
				fullname = $btn.attr("fullname");
				setTimeout(function(){ $this.addClass('open') }, 0);
				enc_url = encodeURIComponent(url='http://'+location.host+$btn.closest('a').attr('href'));
	            enc_cmt = encodeURIComponent(cmt_txt + ' - ' + username);

				$this
					.find('.section').css({marginTop:'',marginLeft:''}).end()
					.find('>.popup-bg').one('click',function(){ $this.trigger('hide') }).end()
					.find('#direct-link-input').val(url).end()
					.find('.bio').html(cmt_txt).end()
					.find('.fig-info')
						.find('h4').text(fullname).end()
						.find('.from').text(username).end()
				    .end()
					.find('.share-via')
						.find('a.me').attr('href', 'http://me2day.net/plugins/post/new?new_post[body]=%22'+enc_cmt+'%22:'+enc_url+'&new_post[tags]=fancy').end()
						.find('a.tw').attr('href', 'http://twitter.com/share?text='+enc_cmt+'&url='+enc_url+'&via=thefancy').end()
						.find('a.fb').attr('href', 'http://www.facebook.com/sharer.php?u='+enc_url).end()
						.find('a.gg').attr('href', 'https://plus.google.com/share?url='+enc_url).end()
						.find('a.su').attr('href', 'http://www.stumbleupon.com/submit?url='+enc_url+'&title='+enc_cmt).end()
						.find('a.tb').attr('href', 'http://www.tumblr.com/share/link?url='+enc_url+'&name='+enc_cmt+'&description='+enc_cmt).end()
						.find('a.li').attr('href', 'http://www.linkedin.com/shareArticle?mini=true&url='+enc_url+'&title='+enc_cmt+'&source=thefancy.com').end()
						.find('a.vk').attr('href', 'http://vkontakte.ru/share.php?url='+enc_url).end()
						.find('a.wb').attr('href', 'http://service.weibo.com/share/share.php?url='+enc_url+'&appkey=&title='+enc_cmt+'&pic='+encodeURIComponent($btn.attr('timage'))).end()
						.find('a.mx')
							.unbind('click')
							.click(function(){
								try { window.open('http://mixi.jp/share.pl?u='+enc_url+'&k=91966ce7669c34754b21555e4ae88eedce498bf0','share','width=632,height=456').focus(); } catch(e){};
								return false;
							})
						.end()
						.find('a.qz').attr('href', 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+enc_url).end()
						.find('a.rr').attr('href', 'http://share.renren.com/share/buttonshare.do?link='+enc_url+'&title='+enc_cmt).end()
						.find('a.od').attr('href', 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=2&st.noresize=on&st._surl='+enc_url).end()
					.end();

				if(username != $this.data('prev_uname')) $this.find('.thum>img').attr('src', '/_ui/images/common/blank.gif').attr('src', uimage);
				$this.data('prev_uname', username);
			})
			.on('hide', function(){
				var $this = $(this).removeClass('open');
				$frm.find('b.name').remove();
				$list.hide();
				setTimeout(function(){ $this.hide() }, 500);
			})
			.find('.popup-bg')
				.on('mousedown', function(event){
					if (event.target === this) {
						$comment_share.trigger('hide');
						return false;
					}
				})
			.end()
			.find('h3')
				.on('mousedown', function(event){
					var $h3 = $(this), $dlg = $comment_share.find('.section'), mt = parseInt($dlg.css('margin-top')), ml = parseInt($dlg.css('margin-left')), sx = event.clientX, sy = event.clientY;
					$(document)
						.on('mousemove.share', function(event){
							var dx =  event.clientX - sx, dy = event.clientY - sy;
							$dlg.css({marginTop:(mt+dy)+'px',marginLeft:(ml+dx)+'px'});
							return false;
						})
						.on('mouseup.share', function(){
							$(document).unbind('mousemove.share moveup.share');
							return false;
						});
					return false;
				})
			.end()
			.find('.btn-close,.btn-cancel').click(function(){ $comment_share.trigger('hide'); return false;})
			.end();

		$fancy_share
// 			.on('mousewheel', function(){ return false })
			.on('show', function(event, btn){
				var $this=$(this).show(), $btn=$(btn), tid=$btn.attr('tid'), uname=$fancy_share.attr('uname'), iframe_h, embed_code, url, enc_url, tname, enc_tname, img, img_h;

				setTimeout(function(){ $this.addClass('open') }, 0);

				img = $btn.parent().prev('img').get(0);
				if (!img) img = $('.fig-image > img').get(0);
				iframe_h = Math.ceil(454/img.width * img.height) + 105;
				
				enc_url   = encodeURIComponent(url='http://'+location.host+$btn.closest('a').attr('href')+'?ref='+uname);
				enc_tname = encodeURIComponent(tname=$btn.attr('tname'));

				$this.attr({tid:tid,tname:tname,turl:url,ooid:$btn.attr('uid')});

				// load short url
				$.ajax({
					type : 'post',
					url  : '/get_short_url.json',
					data : {thing_id:tid},
					dataType : 'json',
					success  : function(json){
						if(!json.short_url) return;
						var enc_short_url = encodeURIComponent(json.short_url);
						$this
							.find('#share-link-input').val(json.short_url).end()
							.find('.share-via a[href]').each(function(){ this.setAttribute('href', this.getAttribute('href').replace(/([\?&]u(?:rl))=[^&]+/, '$1='+enc_short_url)); }).end();
					}
				});

				$this
					.find('.section').css({marginTop:'',marginLeft:''}).end()
					.find('>.popup-bg').one('click',function(){ $this.trigger('hide') }).end()
					.find('.fig-info')
						.find('#share-link-input').val(url).end()
						.find('#share-embed-input').val(embed_code='<iframe src="http://'+location.host+'/embed/'+tid+'?ref='+uname+'" width="480" height="'+iframe_h+'" allowtransparency="true" frameborder="0" style="width:480px;height:'+iframe_h+'px;margin:0 auto;border:0"></iframe>').end()
					.end()
					.find('.share-via')
						.find('a.me').attr('href', 'http://me2day.net/plugins/post/new?new_post[body]=%22'+enc_tname+'%22:'+enc_url+'&new_post[tags]=fancy').end()
						.find('a.tw').attr('href', 'http://twitter.com/share?text='+enc_tname+'&url='+enc_url+'&via=thefancy').end()
						.find('a.fb').attr('href', 'http://www.facebook.com/sharer.php?u='+enc_url).end()
						.find('a.gg').attr('href', 'https://plus.google.com/share?url='+enc_url).end()
						.find('a.su').attr('href', 'http://www.stumbleupon.com/submit?url='+enc_url+'&title='+enc_tname).end()
						.find('a.tb').attr('href', 'http://www.tumblr.com/share/link?url='+enc_url+'&name='+enc_tname+'&description='+encodeURIComponent(embed_code)).end()
						.find('a.li').attr('href', 'http://www.linkedin.com/shareArticle?mini=true&url='+enc_url+'&title='+enc_tname+'&source=thefancy.com').end()
						.find('a.vk').attr('href', 'http://vkontakte.ru/share.php?url='+enc_url).end()
						.find('a.wb').attr('href', 'http://service.weibo.com/share/share.php?url='+enc_url+'&appkey=&title='+enc_tname+'&pic='+encodeURIComponent($btn.attr('timage'))).end()
						.find('a.mx')
							.attr('href', 'http://mixi.jp/share.pl?u='+enc_url)
							.unbind('click')
							.click(function(){
								try { window.open(this.getAttribute('href')+'&k=91966ce7669c34754b21555e4ae88eedce498bf0','share','width=632,height=456').focus(); } catch(e){};
								return false;
							})
						.end()
						.find('a.qz').attr('href', 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+enc_url).end()
						.find('a.rr').attr('href', 'http://share.renren.com/share/buttonshare.do?link='+enc_url+'&title='+enc_tname).end()
						.find('a.od').attr('href', 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=2&st.noresize=on&st._surl='+enc_url).end()
					.end();

				if(tid != $this.data('prev_tid')) $this.find('.thum>img').attr('src', '/_ui/images/common/blank.gif').attr('src', $btn.attr('timage'));
				if($btn.attr('username')) {
					$this.find('p.from').show().text('from '+$btn.attr('username'));
				} else {
					$this.find('p.from').hide();
				}

				$this.data('prev_tid', tid);
			})
			.on('hide', function(){
				var $this = $(this).removeClass('open');
				$frm.find('b.name').remove();
				$list.hide();
				setTimeout(function(){ $this.hide() }, 500);
			})
			.find('.popup-bg')
				.on('mousedown', function(event){
					if (event.target === this) {
						$fancy_share.trigger('hide');
						return false;
					}
				})
			.end()
			.find('h3')
				.on('mousedown', function(event){
					var $h3 = $(this), $dlg = $fancy_share.find('.section'), mt = parseInt($dlg.css('margin-top')), ml = parseInt($dlg.css('margin-left')), sx = event.clientX, sy = event.clientY;
					$(document)
						.on('mousemove.share', function(event){
							var dx =  event.clientX - sx, dy = event.clientY - sy;
							$dlg.css({marginTop:(mt+dy)+'px',marginLeft:(ml+dx)+'px'});
							return false;
						})
						.on('mouseup.share', function(){
							$(document).unbind('mousemove.share moveup.share');
							return false;
						});
					return false;
				})
			.end()
			.find('.fig-info input:text')
				.on('focus', function(){ var inp=this;setTimeout(function(){ inp.select() }, 100); })
			.end()
			.find('ul.user-list')
				.delegate('li', 'mouseenter', function(){ $(this).addClass('on') })
				.delegate('li', 'mouseleave', function(){ $(this).removeClass('on') })
			.end()
			.delegate('button.btn-del', 'click', function(){
				$(this).parent().remove();
				if ($frm.find('>b.name').length == 0) $add.text(txt_add[0]);
			})
			.find('.btn-close,.btn-cancel').click(function(){ $('#fancy-share').trigger('hide') }).end()
			.find('.btn-share')
				.click(function(){
					var $this = $(this), params, emails=[], users=[];

					$this.addClass('disabled').prop('disabled',true);

					params = {
						type : 'nt',
						url  : $fancy_share.attr('turl'),
						name : $fancy_share.attr('tname'),
						oid  : $fancy_share.attr('tid'),
						ooid : $fancy_share.attr('ooid'),
						message :$.trim( $fancy_share.find('textarea').val())
					};

					$frm.find('>b.name').each(function(){
						var $b = $(this);
						if ($b.attr('email')) {
							emails.push($b.attr('email'));
						} else {
							users.push($b.attr('uid'));
						}
					});

					params.emails = emails.join(',');
					params.users  = users.join(',');

					$.ajax({
						type : 'post',
						url  : '/share-with-someone.json',
						data : params,
						dataType : 'json',
						success  : function(json){
							if(!json) return;
							if(json.status_code) {
								alert('Sent!');
								$fancy_share.trigger('hide');
							} else {
								alert(json.message);
							}
						},
						complete : function(){
							$this.removeClass('disabled').prop('disabled',false);
						}
					});
				})
			.end();
		})();
});
