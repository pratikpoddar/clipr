function ucwords (str) {
  return (str + '').replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function ($1) {
    return $1.toUpperCase();
  });
}

function getProductUrl(pid){
  return window.location.protocol + "//" + window.location.host + "/product/" + pid;
}

function getOrigImage(image){
  return window.location.protocol + "//clipr.in/prodImage/" + $.md5(image) + "-orig.jpg";
}

function getSmallImage(image){
  return window.location.protocol + "//clipr.in/prodImage/" + $.md5(image) + "-small.jpg";
}

function getSimilarProdString(product){
  var images = product.image.split("$$$");
  var title = product.title;
  var prodid = product.similar;
  var str = "\
  <li class='span2 similar'>\
    <a class='prodlink ajax-link' href='product/"+prodid+"' >\
      <img src='"+getSmallImage(images[0])+"' class='img-rounded center'>\
      <div class='center'>"+title+"\
      </div>\
    </a>\
  </li>";
  return str;
}

function initializeProduct(pid){
  mixpanel.track("open product");
  populateBlandFancyBox();

  $("#productbaseImage").preloader();
  $("#hiddenmodalproductid").text(pid);
  $(".similar").preloader();

  $('#fancyProductPage').hide()
  $('#prodFancyLoadingImage').show();

  $.ajax({
    type: "GET",
    url: "productAjax",
    data: {id: pid}
  }).done(function( data ) {
    var parsedData = JSON.parse( data );
    var pinfo = parsedData.pinfo;
    $('#productTitle').text(pinfo.title);
    $('#smallProductTitle').text(pinfo.title);
    $.each(pinfo.allimages, function(i, image){
      var imgHtml = '<div class="item" id="cliprImage" align="center" style="background-color:transparent;min-height:200px"><img src="'+getOrigImage(image)+'" alt="'+pinfo.title+'"/></div>';
      $('#prodImageContainer').append(imgHtml);

      imgHtml = '<li><img style="cursor:pointer;height:60px;" class="prodimage" onclick="$(\'#cliprCarousel-fancybox\').carousel('+i+');" src="' + getSmallImage(image) + '" alt="'+pinfo.title+'"/></li>';
      $('#mycarousel-fancybox').append(imgHtml);
    })
    $('fb-comments-widget').attr('data-href',getProductUrl(pinfo.productid));
    var visibleimages = 4;
    if(pinfo.allimages.length <= 4)
      visibleimages = pinfo.allimages.length;

    var config = {scroll: 1, visible: visibleimages, size: pinfo.allimages.length, itemFallbackDimension:70 };
    console.log(config);

    var priceHtml = 'Rs. ' + pinfo.price;
    if((pinfo.markprice != '') && (pinfo.markprice != pinfo.price))
      priceHtml = priceHtml + '<span style="text-decoration:line-through">(Rs. ' + pinfo.markprice + ')</span>';
    $('#productPrice').html(priceHtml);
    $('#productViews').text(pinfo.views);
    if(pinfo.clipperslist != ""){
      $('#productClippers').html(pinfo.clipperslist);
      $('#productClippersDiv').after('<br/>');
    }
    else
      $('#productClippersDiv').hide();

    var link = 'cliprtransfer?link='+pinfo.link;
    if(pinfo.link != '')
      $('#productLink').attr('href',link);
    $('#productButtos').append(pinfo.clipbutton);
    $('#productButtos').append(pinfo.buybutton);
    $('#productButtos').append(pinfo.messagebutton);

    if(parsedData.loggedinflag == 1){
      $('#productSeller').show();
      $('#sellerlogo').attr('src',pinfo.seller);
      $('#sellerlink').attr('href',link);
      if(pinfo.removed)
        $('#productSeller').after('<span class="cliprproduct available">(Product Removed from Seller Website)</span><br />');
    }
    else
      $('#productSeller').hide();

    var tags = $.map(pinfo.tags, function(tag,i){
      return "<a href='tag/" + encodeURIComponent(tag) + "' class='ajax-link'>" + ucwords(tag.replace("_", " ")) + "</a>";
    });
    var tagHtml = tags.join(',&nbsp;');
    if(tags.length>0)
      $('#productTags').html(tagHtml);
    else
      $('#productTags').parent().hide();

    if(pinfo.group && pinfo.group.length){
      var groupHtml = "<a href='tag/" + encodeURIComponent(pinfo.group) + "' class='ajax-link'>" + ucwords(pinfo.group.replace("_", " ")) + "</a>";
      $('#productGroup').html(groupHtml);
    }
    else
      $('#productGroup').parent().hide();
    var similarprods = pinfo.similar;
    var similarclips = pinfo.similarclips;

    if(similarprods.length > 0){
      $('#similarprodlist').show();
      $.each(pinfo.similar, function(i, product){
        var str = getSimilarProdString(product);
        $('#similarprodlist ul').append(str);
      });
    }

    if(similarclips.length > 0){
      $('#sameclippers').show();
      $.each(pinfo.similarclips, function(i, product){
        var str = getSimilarProdString(product);
        $('#sameclippers ul').append(str);
      });
    }

    setTimeout(function() {$('#mycarousel-fancybox').jcarousel(config);},500);
    $('#mycarousel-fancybox').jcarousel(config);

    $('#cliprCarousel-fancybox').carousel();
    $('#cliprCarousel-fancybox #cliprImage:first').addClass("active");
    $('.description').attr('style','margin-top:10px;max-height:510px;overflow:hidden;'); 
    
    if( hasSomeText(pinfo.description)){
      $('#productDescription').html(pinfo.description);
      $("#productDescription").condense({
        condensedLength: 500,
        moreSpeed: 'fast',
        lessSpeed: 'slow',
        moreText: '[show more]',
        lessText: '[show less]'
      });
    }
    else
      $('#productDescription').html('<span style="font-weight:lighter;font-style:italic">No Description Available</span>');

    if(pinfo.clipunclip == "Clip")
      changeToUnclipped("product",pid);
    else if(pinfo.clipunclip == "Unclip")
      changeToClipped("product",pid);
    $("[rel=tooltip]").tooltip();
    $('#fancyProductPage').show();
    $('#prodFancyLoadingImage').hide();
    resetbindings();
  });
}

function hasSomeText(html){
  return ($('<div>').html(html.trim()).text() != "")
}

function populateBlandFancyBox(){
  var prodModalHtml = '\
    <div class="row" id="productModal" style="display:none;max-height:700px"> \
      <input type="hidden" style="display:none" id = "hiddenmodalproductid">\
      <span class="span12"><h3 id = "productTitle"></h3></span>   \
    </div> \
     \
    <div class="row-fluid" id="productbaseImage"> \
     \
    <span class="span8"> \
      <div class="carousel slide prodimage" id="cliprCarousel-fancybox"> \
        <div class="carousel-inner" id = "prodImageContainer"> \
        </div> \
      </div> \
      <div class="row=fluid" align="center"> \
        <ul id="mycarousel-fancybox" class="jcarousel-skin-tango"> \
        </ul> \
      </div> \
     \
      <div class="row-fluid"> \
     \
        <div id="fb-root" class="clearfix"></div> \
        <script>(function(d, s, id) { \
          var js, fjs = d.getElementsByTagName(s)[0]; \
          if (d.getElementById(id)) return; \
          js = d.createElement(s); js.id = id; \
          js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=170592559743815"; \
          fjs.parentNode.insertBefore(js, fjs); \
        }(document, "script", "facebook-jssdk"));</script> \
        <div id = "fb-comments-widget" class="fb-comments" data-href="" data-num-posts="3" style="margin:auto;text-align:center"></div> \
       \
      </div> \
     \
    </span> \
     \
    <span class="span4"> \
     \
      <div class="row-fluid"> \
        <h4 id = "smallProductTitle"></h4> \
      </div> \
     \
      <div class="row-fluid"> \
        <span class="cliprproduct price" id = "productPrice"> \
        </span> \
      </div> \
     \
      <br/> \
     \
      <div class="row-fluid" id ="productSeller"><span class="cliprprodhead">Seller:</span><span class="cliprproduct seller"> <a id="sellerlink" href="" target="_blank"><img id="sellerlogo" src="" alt="Seller Logo" class="companylogo"/></a></span></div><br/> \
     \
     \
      <div class="row-fluid" id = "productButtos"> \
      </div> \
      <br/>       \
     \
      <div class="row-fluid"> \
        <div class="row-fluid"><span class="cliprprodhead">Group: </span><span id = "productGroup" class="cliprproduct group"> </span></div><br/> \
        <div class="row-fluid"><span class="cliprprodhead">Tag: </span><span id = "productTags" class="cliprproduct tag"> </span></div><br/> \
        <div class="row-fluid"><span class="cliprprodhead">Views: </span><span class="cliprproduct views" id = "productViews"></span></div><br/> \
        <div class="row-fluid" id = "productClippersDiv"><span class="cliprprodhead">Clipped By: </span><br/><span class="cliprproduct clipper" id = "productClippers"></span></div> \
      </div> \
      <div class="row-fluid"> \
        <div class="cliprproduct" align="justify"><span class="cliprprodhead">Description:</span><div class="description" id = "productDescription"></div></div> \
      </div> \
    </span> \
  </div>\
  <div class="well" id ="similarprodlist" style="display:none">\
    <div class="row-fluid">\
      <span class="span12">\
        <h3>Similar products</h3>\
      </span>\
    </div>\
    <div class="row-fluid">\
      <span class="span12">\
        <ul class="inline unstyled">\
        </ul>\
      </span>\
    </div>\
  </div>\
  <div class="well" id ="sameclippers" style="display:none">\
    <div class="row-fluid">\
      <span class="span12">\
        <h3>People who clipped this also clipped</h3>\
      </span>\
    </div>\
    <div class="row-fluid">\
      <span class="span12">\
        <ul class="inline unstyled">\
        </ul>\
      </span>\
    </div>\
  </div>';
  $('#prodFancyLoadingImage').width(1000);
  $('#prodFancyLoadingImage').height(400);
  $('#prodFancyLoadingImage img').css("position","relative");
  $('#prodFancyLoadingImage img').css("top",400/2-$('#prodFancyLoadingImage img').height()/2);
  $('#fancyProductPage').html(prodModalHtml);
}

function dispatchModalAction(prop){
  if(prop=="clip" || prop=="add")
  	populateProductModal(prop);
  else if(prop=="follower" || prop=="following")
  	populateUserModal(prop);
  else if(prop=="rewards")
  	populateRewardsModal();
}

function populateProductModal(action)
{
  var uid = $('#hiddenuserid').text();
  $('#hiddenmodalcontainer').css('margin-bottom','30px');
  if(action == "clip")
  	initializeUserModal("Clipped Products");
  else
  	initializeUserModal("Products Added");

  $('#hiddenmodalcontainer').hide()
  $('#modalloadingimg').show();

  $.ajax({
    type: "GET",
    url: "userprodmodal",
    data: {uid: uid, action:action}
  }).done(function( data ) {
  	parsedData = JSON.parse( data );
  	var tablehtml="<table style='font-size:inherit;width:100%'>";
	$.each(parsedData.products, function(i, product){
	  tablehtml = tablehtml + 
		"<tr>\
		  <td style='text-align:center'><img src='" + getSmallImage(product.image) + "' style='max-height:80px;padding:2px 10px;'>\
		  </td>\
		  <td>\
		    <div style='padding-left:30px;word-wrap:break-word;word-break:break-all;'>\
      	      <a href='product/" + product.productid + "' class='ajax-link'>" + product.title + "</a>\
      	    </div>\
      	  </td>\
      	  <td style='min-width:75px'>\
      	    <span style='padding-left:10px;word-wrap:break-word;word-break:break-all;'>"+product.clipbtn+"\
      	    </span>\
      	  </td>\
      	</tr>";
	});
	tablehtml = tablehtml + "</table>";
	$('#hiddenmodalcontainer .modalBody').html(tablehtml);

	$.each(parsedData.products, function(i, product){
      if(product.clipunclip == "Clip")
        changeToUnclipped("modal",product.productid);
      else if(product.clipunclip == "Unclip")
        changeToClipped("modal",product.productid);
	});

	$('#modalloadingimg').hide();
	$('#hiddenmodalcontainer').show();
	resetbindings();
  });
}

function populateUserModal(relation)
{
  var uid = $('#hiddenuserid').text();
  if(relation == "follower")
  	initializeUserModal("Followers");
  else
  	initializeUserModal("Following");

  $('#hiddenmodalcontainer').hide()
  $('#modalloadingimg').show();

  $.ajax({
    type: "GET",
    url: "usermodal",
    data: {uid: uid, relation:relation}
  }).done(function( data ) {
  	parsedData = JSON.parse( data );
    var usersHtml = "";
    $.each(parsedData.users, function(i, user){
   	  usersHtml = 
   	  	usersHtml + "\
   	  	  <div style='margin: 10px;padding: 10px;'>\
          	<img class='pull-left' src='http://graph.facebook.com/"+ user.userid +"/picture?type=square' style='max-height:40px;padding: 2px 10px;'>\
            <span class='pull-left' style='vertical-align: baseline;padding: 2px 10px;word-wrap:break-word;word-break:break-all;'>\
              <a href='clips/"+user.userid+"' class='ajax-link'>"+ user.name +"</a>\
            </span>";
	  if (uid != 0 && $('#loggedUserName').html() != "<img src='./img/facebook-connect.png'>")
        usersHtml = usersHtml + "\
          <span class='pull-right' style='width:120px;margin-right: 20px;'>\
            "+user.followbtn+"\
          </span>";
      usersHtml = usersHtml+"</div><br>";
    });
  	$('#hiddenmodalcontainer .modalBody').html(usersHtml);
	$('#modalloadingimg').hide();
	$('#hiddenmodalcontainer').show();
	resetbindings();
  });
}

function initializeUserModal(title){
  var initial =  "\
  <div class='row'>\
    <span class='span10'><h3 id='modalHeading' align='center'>"+title+"</h3></span>\
  </div>\
  <div id='modalloadingimg' style='vertical-align:center;text-align:center;'>\
    <img src='img/loader-rect.gif'>\
  </div>\
  <div id='hiddenmodalcontainer' style=''>\
    <div class='modalBody'>\
    </div>\
  </div>";
  
  // Setting correct position for loading image
  $('#modalloadingimg').width(600);
  $('#modalloadingimg').height(400);
  $('#modalloadingimg img').css("position","relative");
  $('#modalloadingimg img').css("top",300/2-$('#modalloadingimg img').height()/2);

  $('#userinfo').html(initial);
}

function populateRewardsModal(){
  initializeUserModal("What are reward points?");
  $('#modalloadingimg').hide();
  $('#hiddenmodalcontainer').show();
  var rewardsHtml = "<div class='row'><span class='span10'><p>As you clip and add products on Clipr, you earn reward points. \
	The more you clip and add products, the more points you earn. \
	</p><p>Soon, you will be able to exchange these reward points for discounts on your favourite products. \
	Go <a href='add' class='ajax-link'> add products </a> to earn more rewards or see your detailed <a href='rewards' class='ajax-link'>reward summary</a>. <p></span></div>";
  $('#hiddenmodalcontainer .modalBody').html(rewardsHtml);
  resetbindings();
}
