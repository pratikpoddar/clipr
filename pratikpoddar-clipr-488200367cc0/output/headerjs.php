<script type="text/javascript">
function tour(replacenav, replacetext, firsttimecall){
  _gaq.push(['_trackEvent', 'User-Action', 'Tour', replacenav]);
  mixpanel.track("tour called");
  var tourSubmitFunc = function(e,v,m,f){
        if(v === -1){
          $.prompt.prevState();
          return false;
        }
        else if(v === 1){
          $.prompt.nextState();
          return false;
        }
        else if(v == 3){
          $("#user-dropdown").show()
          $('#user-dropdown li:nth(1)').addClass('active');
          $.prompt.nextState();
          return false;
        }
        else if(v == -3){
          $("#user-dropdown").css("display","")
          $('#user-dropdown li:nth(1)').removeClass('active');
          $.prompt.prevState();
          return false; 
        }
        else if(v == 4){
          $('#user-dropdown li:nth(1)').removeClass('active');
          $('#user-dropdown li:nth(2)').addClass('active');
          $.prompt.nextState();
          return false;
        }
        else if(v == -4){
          $('#user-dropdown li:nth(1)').addClass('active');
          $('#user-dropdown li:nth(2)').removeClass('active');
          $.prompt.prevState();
          return false;
        }
        else if(v == 5){
          $('#user-dropdown li:nth(2)').removeClass('active');
          $("#user-dropdown").css("display","");
          $('.productcontainer:first .imageContainer').mouseenter();
          $.prompt.nextState();
          return false;
        }
        else if(v == -5){
          $("#user-dropdown").show()
          $('#user-dropdown li:nth(2)').addClass('active');
          $('.productcontainer:first .imageContainer').mouseleave();
          $.prompt.prevState();
          return false;
        }
        else if(v == 6){
          $.prompt.nextState();
          return false;
        }
        else if(v == -6){
          $.prompt.prevState();
          return false;
        }
        else if (v == 2 ){
          $("#user-dropdown").css("display","");
          $('.productcontainer:first .imageContainer').mouseleave();
        }
  };
  var tourStates = [
    {
      title: 'Discover',
      html: 'Go to your home page and discover products personalized just for you',
      buttons: { Next: 1 },
      focus: 1,
      position: { container: '#main-menu-right', x: 525, y: 60, width: 300, arrow: 'tc' },
      submit: tourSubmitFunc
    },
    {
      title: 'Find Gifts',
      html: 'Find gifts for friends and family that they will really like just in a couple of clicks',
      buttons: { Prev: -1, Next: 1 },
      focus: 1,
      position: { container: '#main-menu-right', x: 465, y: 60, width: 300, arrow: 'tc' },
      submit: tourSubmitFunc
    },
    {
      title: 'Your Clipboard',
      html: "Organize and flaunt all the products you have clipped and added",
      buttons: { Prev: -1, Next: 1 },
      focus: 1,
      position: { container: '#main-menu-right', x: 395, y: 60, width: 300, arrow: 'tc' },
      submit: tourSubmitFunc
    },
    {
      title: 'Add products',
      html: "Simply add products from your favourite website in less than 5 seconds",
      buttons: { Prev: -1, Next: 1 },
      focus: 1,
      position: { container: '#main-menu-right', x: 280, y: 60, width: 300, arrow: 'tc' },
      submit: tourSubmitFunc
    },
    {
      title: 'Search',
      html: 'Search for products, people, tags or groups',
      buttons: { Prev: -1, Next: 3 },
      focus: 1,
      position: { container: '#main-menu-right', x: 50, y: 60, width: 300, arrow: 'tc' },
      submit: tourSubmitFunc
    },
    {
      title: 'Activity',
      html: 'See what your friends have been upto - The activity stream of your network',
      buttons: { Prev: -3, Next: 4 },
      focus: 1,
      position: { container: '#top-dropdown', x: -390, y: 65, width: 300, arrow: 'rt' },
      submit: tourSubmitFunc
    },
    {
      title: 'Rewards',
      html: 'See your rewards summary and learn how to earn more rewards - which can be redeemed for discounts on your clips',
      buttons: { Prev: -4, Next: 5 },
      focus: 1,
      position: { container: '#top-dropdown', x: -390, y: 90, width: 300, arrow: 'rt' },
      submit: tourSubmitFunc
    },
    {
      title: 'Clip',
      html: 'Clip products you like. Start by clipping your first product. Enjoy Clipping!',
      buttons: {Prev: -5, Next: 6 },
      focus: 1,
      position: { container: '.productcontainer:first .hover-clip-btn', x: 115, y: 2, width: 300, arrow: 'lt' },
      submit: tourSubmitFunc
    },
    {
      title: 'Tag',
      html: 'Tag friends in products that are made for them!',
      buttons: {Prev: -6, Done: 2 },
      focus: 1,
      position: { container: '.productcontainer:first .hover-tag-btn', x: 115, y: 2, width: 300, arrow: 'lt' },
      submit: tourSubmitFunc
    },
  ];
  $.prompt(
    tourStates,{
      close: function(){
        $("#user-dropdown").css("display","");
        $('.productcontainer:first .imageContainer').mouseleave();
        if(replacenav){
          $('#topnavBar').html(replacetext);
          // bind search again
          resetbindings();
        }
        if (firsttimecall && !replacenav) {
          // Show follower Modal
          dispatchModalAction("following");
          $("#userinfofollowing").click();
        }
      }
    }
  );
}

function fbPublish(message,link,image,title,description,retry){

  _gaq.push(['_trackEvent', 'User-Action', 'fb-publish', link]);
  mixpanel.track("published on fb");
  if($.cookie('loggedUserId')=="" || $.cookie('loggedUserId') == null){
    postWithDialog(message,link,image,title,description);
    return;
  }

  if(typeof(retry)==='undefined') retry = false;

  FB.api({ method: 'fql.query', query: 'SELECT publish_stream FROM permissions WHERE uid=me()' }, function(resp) {
    for(var key in resp[0]) {

      if(retry){
        updatePostRequestStatus(resp[0][key],$.cookie('loggedUserId'));
      }
      if(resp[0][key] === "1"){
        $.gritter.add({
              title: 'Posting on FB',
              text: '<span class="gritterText">A post is being shared on Facebook.</span>',
              image: 'img/clipr.png',
              sticky: false,
              time: 2000
        });
        FB.api('/me/feed', 'post',
        {
          message     : message,
          link        : link,
          picture     : image,
          name        : title,
          description : description
        },
        function(response) {
          if (response && !response.error) {
            $.gritter.add({
              title: 'Successfully posted',
              text: '<span class="gritterText">The post was successfully shared on Facebook.</span>',
              image: 'img/clipr.png',
              sticky: false,
              time: ''
            });
          }          
        });
      }
      else{
        $.ajax({
          type: "GET",
          url: "getPostRequestStatus",
          data: {}
        }).done(function(data){
          var updatedPostPermission = JSON.parse(data);
          if(retry || updatedPostPermission )
            postWithDialog(message,link,image,title,description);
          else
            FB.login(function(response) {
              fbPublish(message,link,image,title,description,true);
            }, {scope:'publish_stream'});  
        });
      }
    }
  });
}

function postWithDialog(message,link,image,title,description){
  FB.ui(
    {
      method: 'feed',
      name: title,
      link: link,
      picture: image,
      description: description
    },
    function(response) {
      if (response && response.post_id) {
        $.gritter.add({
          title: 'Successfully posted',
          text: '<span class="gritterText">The post was successfully shared on Facebook</span>',
          image: 'img/clipr.png',
          sticky: false,
          time: ''
        });
      } else {
      }
    }
  );
}

function updatePostRequestStatus(stat,uid){
  $.ajax({
    type: "POST",
    url: "updatePostRequestStatus",
    data: {status: stat}
  }).done(function(data){
  });
}

function clipAction(pid,prefix,e){
  var text = $('#'+prefix+'btn'+pid).text();
  _gaq.push(['_trackEvent', 'User-Action', text, pid]);
  if( text == "Clip" ) {
    $.ajax({
      type: "GET",
      url: "getBoards",
      data: {pid: pid,timestamp:+new Date}
    }).done(function(data){
      updateClippers(pid, (JSON.parse(data)).clipperstring);
      setTimeout(function(){$('#maincontent').masonry( 'reload' );},200);
    });
    setTimeout(function(){changeToClipped(prefix,pid);},500);
  }
  else {
    clipAjax( pid, [], prefix );
  }
}

function clipMag(selector,pid){
  mixpanel.track("clip on magazine");
  _gaq.push(['_trackEvent', 'User-Action', 'clip product on magazine', pid]);

  $.ajax({
    type: "GET",
    url: "getBoards",
    data: {pid: pid}
  }).done(function(data){
    $.gritter.add({
      title: '<a href="product/'+pid+'" class="ajax-link">Clipped the product</a>',
      text: '<span class="gritterText">Product has been successfully clipped.<br/></span>',
      image: 'img/clipr.png',
      time: 2000
    });
  });
  setTimeout(function(){changeToClippedMag(selector,pid);},500);
  $(selector).siblings(".mag-clipcount-btn").text(parseInt($(selector).siblings(".mag-clipcount-btn").text())+1);
}

function changeToClippedMag(selector,pid){
  $(selector+' i').addClass('disabled');
  $(selector+' a').attr('onClick','');
  $(selector).css('cursor','auto');
}

function setBoards(elemSelector,data){
  var boards = data.boards;
  var selectedboards = data.selectedboards;
  $(elemSelector).html('');
  $.each(boards, function(i, val){
    var checked = false;
    $.each(selectedboards, function(i, selectedval){
      if(selectedval == val)
      checked = true;
    });
    if (checked)
      $(elemSelector).append("<li style='padding-left:5px;'><div class='squaredFour'>\
        <input type='checkbox' name='li' value='"+val+"' checked='checked' id='"+elemSelector+"_boards_"+val+"'> \
        <label for='"+elemSelector+"_boards_"+val+"'>\
        <div class='clipboards'>" +val.replace(/_/g,' ')+"</div>\
        </label> \
        </div></li>");
    else
      $(elemSelector).append("<li style='padding-left:5px;'><div class='squaredFour'>\
        <input type='checkbox' name='li' value='"+val+"'  id='"+elemSelector+"_boards_"+val+"'>\
        <label for='"+elemSelector+"_boards_"+val+"'> \
        <div class='clipboards'>" +val.replace(/_/g,' ')+"</div>\
        </label>\
        </div></li>");
  });
  $(elemSelector).append("<li style='padding:5px;'><input type='text' placeholder='Add New Board' class='newboard'></li>");
  bindkeydown(elemSelector);
}

  function following ( uid1, uid2, action ) {
    var a = event.target;
    $.ajax({
      type: "GET",
      url: "follow",
      data: { uid1: uid1, uid2: uid2, action: action }
    }).done(function( msg ) {
      if(msg == "You are succesfully 'Un-following' now\n"){
        $(a).addClass('btn-primary'); 
        $(a).attr('onClick',$(a).attr('onClick').replace('unfollow','follow')); 
        $(a).text('Follow'); 
      }
      else if (msg == "You are succesfully 'Following' now\n"){
        $(a).removeClass('btn-primary');
        $(a).attr('onClick',$(a).attr('onClick').replace("'follow'","'unfollow'"));
        $(a).text('Unfollow'); 
      }
      $.gritter.add({
        title: 'Follow-Unfollow Action',
        text: msg,
        image: 'img/clipr.png',
        sticky: false,
        time: ''
      });
    });
  }

</script>

<script type="text/javascript">

  function showLoginError ( action, pUrl ) {
    $('#modalLoginLink').attr('href',pUrl)
    $('#modalLoginError').text('Login to '+action);
    $('#modalLogin').modal('show');
    return;
  }


</script>

<script type="text/javascript">

  if (typeof String.prototype.endsWith !== 'function') {
      String.prototype.endsWith = function(suffix) {
          return this.indexOf(suffix, this.length - suffix.length) !== -1;
      };
  }

  function getBoards(pid,prefix){
    var elemSelector = '#'+prefix+'btngroup'+pid+' ul';
    $(elemSelector).show();
    $(elemSelector).html('<li style="text-align:center"><img style="max-height:40px" src="img/loader-rect.gif"></li>');
    $.ajax({
      type: "GET",
      url: "getBoards",
      data: {pid:pid}
    }).done(function( data ) {
      setBoards(elemSelector, JSON.parse(data));
      setTimeout(function(){$('#maincontent').masonry( 'reload' );},200);
    });
  }

  function submitBoards(pid,prefix){
    var elemSelector = '#'+prefix+'btngroup'+pid+' ul';
    if($(elemSelector+' li img').length == 0){
      var boards  = $(elemSelector+' input:checkbox[name="li"]:checked').map(function () {
        return this.value;
      }).get();
      clipAjax(pid, boards,prefix);
    }
    $(elemSelector).hide();
  }

  function changeToClipped(prefix, pid){
    var btnid = '#' + prefix + 'btn' + pid;
    $(btnid).toggleClass('disabled', true);
    $(btnid).html("<i class='icon-ok-sign'></i> Clipped");  
    $(btnid).parent().toggleClass('unclip-btn', true);
    $(btnid).attr('data-original-title','Already clipped');
    bindClippedAction(prefix,pid);
    // Also change clip button in trending feed
    if(prefix!="prodlist" &&  $('#prodlistbtn' + pid).length > 0)
      changeToClipped("prodlist", pid);
    if(prefix=="prodlist")
      setTimeout(function(){$('#maincontent').masonry( 'reload' );},200);

  }

  function bindClippedAction(prefix,pid){
    var btnSelector = '#'+prefix+'btngroup'+pid;
    $(btnSelector).unbind();
    // if(prefix != "modal"){
    //   $(btnSelector).hoverIntent({
    //     over:function(){
    //       getBoards(pid,prefix);
    //     },
    //     timeout:600,
    //     out:function(){
    //       submitBoards(pid,prefix);
    //     }
    //   });
    //   $(btnSelector).trigger('mouseenter');
    // }
  }

  function bindkeydown(parentselector){
    var fieldselector = parentselector + ' li .newboard';
    $(fieldselector).keydown(function(event) {  
      if(event.which == '13') {
        event.preventDefault()
        var boardname = $(fieldselector).val().trim();
        $(fieldselector).val("");
        var found = false;
        var existingBoards = $(fieldselector + ' input:checkbox[name="li"]').map(function () {
          return this.value;
        }).get();
        $.each(existingBoards, function (i, val) {
          var isMatch = (val.replace(/_/g,' ').toUpperCase() === boardname.toUpperCase());
          found = found || isMatch;
          if ( isMatch )
            $(fieldselector + ' input:checkbox[value='+val+']').attr('checked', true);
        });
        if(!found) {
          var boardHolder = boardname.toLowerCase().replace(/ /g,'_');
          $(parentselector + ' li:last').before("<li style='padding-left:5px;'><div class='squaredFour'>\
            <input type='checkbox' name='li' value='"+boardHolder+"' checked='checked' id='"+parentselector+"_boards_"+boardHolder+"'> \
            <label for='"+parentselector+"_boards_"+boardHolder+"'>\
            <div class='clipboards'>" +boardname.toLowerCase()+"</div>\
            </label> \
            </div></li>");
        }
      }
    });
  }

  function changeToUnclipped(prefix, pid){
    var btnid = '#' + prefix + 'btn' + pid;
    $(btnid).toggleClass('disabled', false);
    $(btnid).html("Clip");
    $(btnid).parent().removeClass('unclip-btn', false);
    $(btnid).attr('data-original-title','You \"Clip\" what you \"Like\"');
    bindUnclippedAction(prefix,pid);
    if(prefix!="prodlist" &&  $('#prodlistbtn' + pid).length > 0)
      changeToUnclipped("prodlist", pid);
    if(prefix=="prodlist")
      setTimeout(function(){$('#maincontent').masonry( 'reload' );},200);
  }

  function bindUnclippedAction(prefix,pid){
    var btnSelector = '#'+prefix+'btngroup'+pid;
    $(btnSelector).unbind();
    $(btnSelector).click(function(){
      clipAction(pid,prefix, this);
    });
  }

  function updateClippers(pid, clipperstring){
    // Normal Page
    $('#clippers'+pid).html(clipperstring);

    //Contest Page
    $('#contestclipper'+pid).html(clipperstring);
    bindProductFancyBox();
    resetbindings();
  }

  function clipAjax( pid, clipTags, prefix ) {

    var oldstate;
    if (clipTags.length > 0) {
      mixpanel.track("clip product");
      oldstate = "Clip";
    }
    else {
      mixpanel.track("unclip product");
      oldstate = "Unclip";
    }
    $.ajax({
      type: "GET",
      url: "clip",
      data: { userId: $.cookie('loggedUserId'), productId : pid, clipTags: clipTags, oldstate: oldstate ,timestamp:+new Date }
    }).done(function( rawdata ) {
      var result = JSON.parse(rawdata);
      var msg = result.message;
      if ((oldstate == "Clip") && (msg == "Success"))
        changeToClipped(prefix, pid);
      if ((oldstate == "Unclip") && (msg == "Success"))
        changeToUnclipped(prefix, pid);
      updateClippers(pid, result.clipperstring);
      resetbindings();
    });
  }
  function reportAjaxHelp( pid, title, action ) {
    $.ajax({
      type: "GET",
      url: "report",
      data: { userId: $.cookie('loggedUserId'), productId : pid}
    }).done(function( msg ) {
      var fullMsg = "";
      if (msg === "success\n")
      {
        fullMsg = 'Successfully reported <a href="product/'+pid+'" class="ajax-link" style="color:#ccc"> ' + title +' </a>';
      }
      else 
      {
        fullMsg = 'You have already reported <a href="product/'+pid+'" class="ajax-link" style="color:#ccc"> ' + title +' </a> once. We will take an action shortly.';
      }
      $.gritter.add({
        title: action + ' a Product ',
        text: fullMsg,
        image: 'img/clipr.png',
        sticky: false,
        time: ''
      });
    });
  }

  function reportAjax( pid, title) {
    var action = "Reported";
    reportAjaxHelp(pid, title, action);  
  }
  function referInfo(){
    var tourSubmitFunc = function(e,v,m,f){
      if(v === -1){
        $.prompt.prevState();
        return false;
      }
      else if(v === 1){
        $.prompt.nextState();
        return false;
      }
      };
    var tourStates = [
      {
        title: 'Refer \'n Win',
        html: 'Now there is one more reason to share the love. Tell your friends about us and win some sweet stuff',
        buttons: { 'Ok, Got it!': 2 },
        focus: 1,
        position: { container: '#top-dropdown', x: -390, y: 115, width: 300, arrow: 'rt' },
        submit: tourSubmitFunc
      },
    ];
    $.prompt(
      tourStates,{
        close: function(){
          $("#user-dropdown").css("display","");
          $('#user-dropdown li:nth(1)').removeClass('active');
        }
      }
    );
    $("#user-dropdown").show()
    $('#user-dropdown li:nth(3)').addClass('active');
    document.cookie="refershown=1;expires="+getExpirationDateString(30);
  }

  function partial(func /*, 0..n args */) {
    var args = Array.prototype.slice.call(arguments, 1);
    return function() {
      var allArguments = args.concat(Array.prototype.slice.call(arguments));
      return func.apply(this, allArguments);
    };
  }

  function daysInMilliseconds(numdays)
  {
    return numdays*60*60*1000*24;
  }

  function getExpirationDateString(numdays){
    var currdate = new Date();
    var expiremilliseconds = daysInMilliseconds(numdays);
    var expirationdate = new Date(currdate.getTime() + expiremilliseconds);
    return expirationdate.toGMTString();
  }

  function openPopup(link){
    window.open(link,"fbwindow", 'width=580,height=400');
  }

  function createCookie(name, value, days) {
      var expires;
      if (days) {
          var date = new Date();
          date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
          expires = "; expires=" + date.toGMTString();
      }
      else expires = "";
      document.cookie = name + "=" + value + expires + "; path=/";
  }

  function readCookie(name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(';');
      for (var i = 0; i < ca.length; i++) {
          var c = ca[i];
          while (c.charAt(0) == ' ') c = c.substring(1, c.length);
          if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
      }
      return null;
  }

  function eraseCookie(name) {
      createCookie(name, "", -1);
  }

  function areCookiesEnabled() {
      var r = false;
      createCookie("testing", "Hello", 1);
      if (readCookie("testing") != null) {
          r = true;
          eraseCookie("testing");
      }
      return r;
  }

  function bindProductFancyBox(){
    $(".modalbox").fancybox({ 
      width: '400px' ,
      beforeShow: function(){
        $("body").css({'overflow-y':'hidden'});
      },
      afterShow: function(){ 
        if($("#inlineproduct").css('display') != "none")
          history.pushState({source:'product-modal'}, 'New URL: ', 'product/'+$("#hiddenmodalproductid").text());
        resetbindings();
      }, 
      onUpdate: function(){
        $('.fancybox-inner').jScrollPane({
          autoReinitialise: true,
          autoReinitialiseDelay: 500,
          showArrows: true
        });
      },
      beforeClose: function(){
        if(window.location.href.indexOf('/product/')>=0)
          history.back();
      },
      afterClose: function(){
        $("body").css({'overflow-y':'visible'});
      },
      openEffect: "elastic",
      closeEffect: "fade",
      closeSpeed: "fast",
      live: false
    });
  }

  function bindSearch(){
    var mappedProducts,mappedUsers,labels,resultType,data2;
    $('#cliprsearch').typeahead({
      contentType: "application/json; charset=utf-8",
      source: function (query, process) 
      {
        $.getJSON('typeahead', { query: query } , function (data)
        {
          mappedProducts = {};
          mappedUsers = {};
          labels = [];
          resultType = {};
          score= {};
          data2 = $.parseJSON(data);
          $.each(data2.products, function (i, item) {
            mappedProducts[item.title] = item.productid;
            resultType[item.title] = "Product";
            score[item.title] = item.score;
            labels.push(item.title);
          });
          $.each(data2.tags, function (i, item) {
            resultType[item.name] = "Tag";
            score[item.name] = item.score;
            labels.push(item.name);
          });
          $.each(data2.groups, function (i, item) {
            resultType[item.groupname] = "Group";
            score[item.groupname] = item.score;
            labels.push(item.groupname);
          });
          $.each(data2.users, function (i, item) {
            mappedUsers[item.name] = item.userid;
            resultType[item.name] = "User";
            score[item.name] = item.score;
            labels.push(item.name);
          });
          process(labels);
        });
    },
    minLength: 3,
    items:20,
    updater: function(item){
      if ( resultType[item] === "Product") {
        getProductPage(mappedProducts[item]);}
      else if ( resultType[item] === "Tag") {
        getTaggedPage(item);}
      else if (resultType[item] === "Group") {
        getTaggedPage(item);}
      else if (resultType[item] === "User") {
        getClipboard(mappedUsers[item]);}
      return '';
    },
    highlighter: function(item){
      //var regex = new RegExp( '(' + this.query + ')', 'gi' );
      //var result = item.replace( regex, "<strong>$1</strong>" );
      var result = item;
      var returnresult = '<div class="row" style="position:relative; z-index:1000;"><div class="span4" style="text-transform:capitalize;word-wrap:break-word;word-break:break-all;overflow:hidden">'+result.replace("_"," ")+'</div>'+'<div class="span1" style="font-size:90%;color:#6F91A8;">'+resultType[item]+'</div></div>';
      return returnresult;
    },
    matcher: function(item){
      return true;
    },
    sorter: function(items){
      items.sort(function(a,b){return score[b]-score[a]});
      console.log(items);
      return items;
    }

  });
}


  function getPagination(page, userid, pagination){
    var params = { page: page, userId: userid, pagination: pagination };
    updateListPage(params);
  }

  function getPaginationTagged(page, userid, pagination, tag){
    var params = { page: page, userId: userid, pagination: pagination, tag: tag };
    updateListPage(params);
  }

  function gototop(time) {
    $('body,html').animate({
        scrollTop: 0
      }, time);
    return false;
  }

  function updateListPage(params){
    // This is a hack to hide all tooltips before reloading ajax
    $('a').tooltip('hide');
      $.ajax({
        type: "GET",
        url: "prodlistbase",
        data: params
      }).done(function( msg ) {
        $('#toplevelcontainer').html(msg);
        $("[rel=tooltip]").tooltip();
        gototop(100);
        FB.XFBML.parse();
      });
  }

  // avoid popstate execution on page-load
  function executeupdate(url){
    if(($('#pagename').text()) != url)
      updateTopLevelContainer(url);
  }

  function updateTopLevelContainer(url){
    // This is a hack to hide all tooltips before reloading ajax
    $('a').tooltip('hide');
    // This hides all modals before reloading ajax
    $('.modal').modal("hide");
    $.fancybox.close();
    if($("#toplevelcontainer").length == 0 || refreshPage()) 
      window.location.href = url;
    else {
      $.ajax({
        type: "GET",
        url: "ajax/"+url
      }).done(function( msg,status,req ) {
        var location = req.getResponseHeader('Location');
        if (location){
          window.location.href = location;
        }
        $('#toplevelcontainer').html(msg);
        $("[rel=tooltip]").tooltip();
        $('#pagename').html(url);
        gototop(100);
      });
      history.pushState({source:'ajax-link'}, 'New URL: '+url, url);
    }
  }

  function getProductPage(productid){
    var params = { id: productid, pagetype: "product" };
    updateTopLevelContainer('product/'+productid);
  }

  function getTaggedPage(interest){
    updateTopLevelContainer('tag/'+interest);
  }

  function getFriendSuggestions(friendid){
    updateTopLevelContainer("gifts/"+friendid);
  }

  function getClipboard(uid){
    if(uid == 0)
      updateTopLevelContainer('clips');
    else
      updateTopLevelContainer('clips/'+uid);
  }

  function getTrending(){
    updateTopLevelContainer('home');
  }

  function closeModal(modalid){
    $('#'+modalid).modal('hide');
    return false;
  }

  // Hack to bind my event as first event
  $.fn.bindFirst = function(name, fn) {
      // bind as you normally would
      // don't want to miss out on any jQuery magic
      this.on(name, fn);

      // Thanks to a comment by @Martin, adding support for
      // namespaced events too.
      this.each(function() {
          var handlers = $._data(this, 'events')[name.split('.')[0]];
          console.log(handlers);
          // take out the handler we just inserted from the end
          var handler = handlers.pop();
          // move it at the beginning
          handlers.splice(0, 0, handler);
      });
  };

  function resetbindings(){
    $('a.ajax-link').unbind('click');
    $(function() {
      $('a.ajax-link').click(function(e) {
        var href = $(this).attr("href");
        if(href.indexOf("clips") >= 0) {
          mixpanel.track("clipboard opened");
          _gaq.push(["_trackEvent", "User-Action", "clipboard-opened", href]);
        }
        if(href.indexOf("refer") >= 0) {
          mixpanel.track("referral page opened");
          _gaq.push(["_trackEvent", "User-Action", "clipboard-opened", href]);
        }
        if($('.fancybox-inner').length>0 && window.location.href.indexOf('/product/')>=0){
          history.back();
          setTimeout(function(){
            executeupdate(href);
            popstateReady = true;
          }, 500);
        }
        else{
          executeupdate(href);
          // HISTORY.PUSHSTATE
          popstateReady = true;
        }
        e.preventDefault();
      });
      // THIS EVENT MAKES SURE THAT THE BACK/FORWARD BUTTONS WORK AS WELL
    });
    $("[rel=tooltip]").tooltip();
    bindSearch();
    $('#newboard').unbind('keydown');
    addLoginUrls();
    $('.fancybox-inner').jScrollPane({
      autoReinitialise: true,
      autoReinitialiseDelay: 500,
      showArrows: true
    });

    // bind comment button
    $(".tag-btn").unbind('click');
    $(".tag-btn").click(function(event){
      var pid = $(this).data('productid');
      $('.as-input').val('');
      $('.as-selection-item').remove()
      $('.as-values').val('');
      $('#tagmessage').val('');
      $('#hiddenproductid').text(pid);
      if($.cookie('loggedUserId') && $.cookie('loggedUserId')!== null)
        $('#tagbox').modal('show');
      else
        showLoginError("Tag","http://"+window.location.host+"/output/product/"+pid );
      if($('#addTagText a').data('state') == 'expanded'){
        $('#tagmessage').hide();
        $('#addTagText a').data('state','collapsed'); 
        $('#addTagText a').html('Add a Message <i class="icon-arrow-down"></i>');
      }
      event.stopPropagation();
      event.preventDefault();
    });

    $(".as-input").bindFirst('keydown',function(event){
      if (event.which == 13 && ($('.as-result-item.active').length == 0 || !$('.as-result-item').is(':visible')) && $('.as-input').val()=="") {
        $('#tagsubmit').click();
        event.preventDefault();
      }
    });

    $('.icon-shopping-cart').parent().click( function(){
      _gaq.push(['_trackEvent', 'User-Action', 'buy']);
      mixpanel.track("clicked on buy");
    });
    $('.icon-envelope').parent().click( function(){
      _gaq.push(['_trackEvent', 'User-Action', 'message']);
      mixpanel.track("messaged to a friend");
    });
    $('.icon-facebook-sign').parent().click( function(){
      _gaq.push(['_trackEvent', 'User-Action', 'share']);
      mixpanel.track("shared on fb");
    });

    // making all prodlist images draggable
    // $('.imageContainer').draggable({helper:'clone', revert:"invalid",revertDuration:300,scroll: true});
  }
</script>
