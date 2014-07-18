function ucwords (str) {
  return (str + '').replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function ($1) {
    return $1.toUpperCase();
  });
}

function hasSomeText(html){
  return ($('<div>').html(html.trim()).text() != "")
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
    <a class='prodlink' data-product-id='"+prodid+"' href='product/"+prodid+"' >\
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

  $("#hiddenmodalproductid").text(pid);

  $('#fancyProductPage').hide()
  $('#prodFancyLoadingImage').show();

  $.ajax({
    type: "GET",
    url: "productAjax",
    data: {id: pid}
  }).done(function( data ) {
    var parsedData = JSON.parse( data );
    var pinfo = parsedData.pinfo;
    $('#productTitle').html(pinfo.title);
    $('#smallProductTitle').html(pinfo.title);
    $.each(pinfo.allimages, function(i, image){
      var imgHtml = '<div class="item" id="cliprImage" align="center" style="background-color:transparent;min-height:200px"><img src="'+getOrigImage(image)+'" alt="'+pinfo.title+'"/></div>';
      $('#prodImageContainer').append(imgHtml);

      imgHtml = '<li><img style="cursor:pointer;height:60px;min-width:30px;" class="prodimage" onclick="$(\'#cliprCarousel-fancybox\').carousel('+i+');" src="' + getSmallImage(image) + '" alt="'+pinfo.title+'"/></li>';
      $('#mycarousel-fancybox').append(imgHtml);
    })
    $('#fb-comments-widget').attr('data-href',getProductUrl(pinfo.productid));
    $('#fb-comments-widget').attr('href',getProductUrl(pinfo.productid));
    var visibleimages = 4;
    if(pinfo.allimages.length <= 4)
      visibleimages = pinfo.allimages.length;

    var config = {scroll: 1, visible: visibleimages, size: pinfo.allimages.length, itemFallbackDimension:70 };
    console.log(config);

    var priceHtml = 'Rs. ' + pinfo.price;
    if((pinfo.markprice != '') && (pinfo.markprice != pinfo.price))
      priceHtml = priceHtml + '<span style="text-decoration:line-through">(Rs. ' + pinfo.markprice + ')</span>';
    $('#productPrice').html(pinfo.price);
    $('#productViews').text(pinfo.views);
    $('#productClips').text(pinfo.clipcount);
    if( hasSomeText(pinfo.description)){
      $('#productDescription').html(pinfo.description);
      $("#productDescription").condense(
        {
          condensedLength: 200,
          moreSpeed: 'fast',
          lessSpeed: 'slow',
          moreText: '[show more]',
          lessText: '[show less]'
        });
    }
    else
      $('#productDescription').html('<span style="font-weight:lighter;font-style:italic">No Description Available</span>');

    var link = 'cliprtransfer?link='+pinfo.link;
    if(pinfo.link != '')
      $('#productLink').attr('href',link);
    $('#productButtos').append(pinfo.clipbutton);
    $('#productButtos').append(pinfo.tagbutton);
    $('#productButtos').append(pinfo.buybutton);

    $('#productSeller').show();
    $('#sellerlogo').attr('src',pinfo.seller);
    $('#sellerlink').attr('href',link);
    if(pinfo.removed)
      $('#productSeller').after('<span class="cliprproduct available">(Product Removed from Seller Website)</span><br />');

    var tags = $.map(pinfo.tags, function(tag,i){
      return "<a href='tag/" + encodeURIComponent(tag) + "' class='ajax-link'>" + ucwords(tag.replace("_", " ")) + "</a>";
    });
    var tagHtml = tags.join(',&nbsp;');
    if( tags.length>0 ){
      $('#productTags').html(tagHtml);
      $('#productTags').parent().after('<br/>');
    }
    else
      $('#productTags').parent().hide();

    if( pinfo.group && pinfo.group.length ) {
        groupHtml = "<a href='tag/" + encodeURIComponent(pinfo.group) + "' class='ajax-link'>" + ucwords(pinfo.group.replace("_", " ")) + "</a>";
        $('#productGroup').html(groupHtml);  
        $('#productGroup').parent().after('<br/>');
    }
    else
      $('#productGroup').parent().hide();

    if(pinfo.availability && pinfo.availability != ""){
      $('#productAvailability').html(pinfo.availability);
      $('#productAvailability').parent().after("<br/>");
    }
    else
      $('#productAvailability').parent().hide();
    
    if(pinfo.delivery && pinfo.delivery != ""){
      $('#productDelivery').html(pinfo.delivery);
      $('#productDelivery').parent().after("<br/>");
    }
    else
      $('#productDelivery').parent().hide();
    
    if(pinfo.shippingcost && pinfo.shippingcost != ""){
      $('#productShipping').html(pinfo.shippingcost);
      $('#productShipping').parent().after("<br/>");
    }
    else
      $('#productShipping').parent().hide();

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

    // wait and initialize comments and carousel
    setTimeout(function() {
      $('#mycarousel-fancybox').jcarousel(config);
      FB.XFBML.parse();
    },500);
    $('#mycarousel-fancybox').jcarousel(config);

    $('#cliprCarousel-fancybox').carousel();
    $('#cliprCarousel-fancybox #cliprImage:first').addClass("active");
    if(pinfo.clipunclip == "Clip")
      changeToUnclipped("product",pid);
    else if(pinfo.clipunclip == "Unclip")
      changeToClipped("product",pid);
    $("[rel=tooltip]").tooltip();
    $('#fancyProductPage').show();
    $('#prodFancyLoadingImage').hide();
    $('#productPageLink').attr('href','product/'+pid);
    resetbindings();
    $("#productbaseImage").preloader();
    $(".similar").preloader();
    $('a.prodlink').click(function(e){
      initializeProduct($(this).data('product-id'));
      e.preventDefault();
    });
  });
}

function populateBlandFancyBox(){
  var prodModalHtml = '\
    <div class="row" id="productModal" style="display:none;max-height:700px"> \
      <input type="hidden" style="display:none" id = "hiddenmodalproductid">\
      <span class="span12"><h3 id = "productTitle" style="font-size:180%"></h3></span>   \
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
      </div>\
     \
    </span> \
     \
    <span class="span4"> \
     \
      <div class="row-fluid"> \
        <h4 id = "smallProductTitle" style="font-size:180%"></h4> \
      </div> \
      <br/> \
     \
      <div class="row-fluid" id="newProdInfo">        \
        <span class="span4 newProdInfoCount" style="text-align:left;"><i class="icon-eye-open"></i> <span id="productViews"></span></span>        \
        <span class="span4 newProdInfoCount" ><i class="icon-paper-clip"></i> <span id="productClips"></span></span>        \
        <span class="span4" style="display: inline-block;"><span style="display: inline-block;  "><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" width="13px" viewBox="39.5 -0.5 169.756 250" enable-background="new 39.5 -0.5 169.756 250" xml:space="preserve" xmlns:xml="http://www.w3.org/XML/1998/namespace" height="24px"> <path fill="#010101" d="M152.511,23.119h41.031L209.256-0.5H55.214L39.5,23.119h26.739c27.086,0,52.084,2.092,62.081,24.743H55.214  L39.5,71.482h91.769c-0.002,0.053-0.002,0.102-0.002,0.155c0,16.974-14.106,43.01-60.685,43.01l-22.537-0.026l0.025,22.068  L138.329,249.5h40.195l-93.42-116.709c38.456-2.074,74.523-23.563,79.722-61.309h28.716l15.714-23.62h-44.84  C162.606,38.761,158.674,29.958,152.511,23.119z" style="fill: #444;"></path></svg></span> <span id="productPrice" style="font-size:180%;vertical-align: top;margin-top: 5%;display: inline-block;"></span></span>\
      </div>\
      <br/> \
      <div class="row-fluid" id ="productSeller"><span class="cliprprodhead">Seller:</span><span class="cliprproduct seller"> <a id="sellerlink" href="" target="_blank"><img id="sellerlogo" src="" alt="Seller Logo" class="companylogo"/></a></span></div><br/> \
     \
     \
      <div class="row-fluid" id = "productButtos"> \
      </div> \
      <br/>       \
     \
      <div class="row-fluid"> \
        <div class="row-fluid"> <span class="cliprprodhead availability">Group: </span> <span id = "productGroup" class="cliprproduct tag"> </span></div> \
        <div class="row-fluid"><span class="cliprprodhead availability">Tags: </span> <span id = "productTags" class="cliprproduct tag"> </span></div> \
      </div> \
      <div class="row-fluid"> \
        <div class="cliprproduct" align="justify"><span class="cliprprodhead availability">Description: </span><div class="description" id = "productDescription"></div></div> \
        <div class="cliprproduct"><a class="ajax-link cliprprodhead" id="productPageLink" href="">Go to Product Page</a></div>\
      </div> \
      <div style="display:none;">\
        <div class="row-fluid" style="text-transform:capitalize"><span class="cliprprodhead availability">Availability: </span> <span id="productAvailability"></span></div>\
        <div class="row-fluid" style="text-transform:capitalize"><span class="cliprprodhead delivery">Delivery: </span> <span id="productDelivery"></span></div>\
        <div class="row-fluid" style="text-transform:capitalize"><span class="cliprprodhead shipping">Shipping Cost: </span><span id="productShipping"></span></div>\
      </div>\
    </span> \
  </div>\
  <div class="well" id ="similarprodlist" style="display:none">\
    <div class="row-fluid">\
      <span class="span12">\
        <h3 style="margin:0px auto;">Similar products</h3>\
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
        <h3 style="margin:0px auto;">People who clipped this also clipped</h3>\
      </span>\
    </div>\
    <div class="row-fluid">\
      <span class="span12">\
        <ul class="inline unstyled">\
        </ul>\
      </span>\
    </div>\
  </div>';
  $('#prodFancyLoadingImage').width(900);
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

function populateClippers(pid){
  populateUserModal("clipped",pid);
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

function populateUserModal(relation,pid)
{
  var data;
  if(relation == "follower"){
    var uid = $('#hiddenuserid').text();
  	initializeUserModal("Followers");
    data = {uid: uid, relation:relation}; 
  }
  else if(relation == "following"){
    var uid = $('#hiddenuserid').text();
    initializeUserModal("Following");
    data = {uid: uid, relation:relation};
  }
  else if(relation == "clipped"){
    initializeUserModal("This product is clipped by");
    data = {pid: pid, relation:relation};
  }

  $('#hiddenmodalcontainer').hide()
  $('#modalloadingimg').show();

  $.ajax({
    type: "GET",
    url: "usermodal",
    data: data
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
	</p><p>You will be able to exchange these reward points for discounts on your favourite products on collecting more than 5000 points . \
	Go <a href='add' class='ajax-link'> add products </a> to earn more rewards or see your detailed <a href='rewards' class='ajax-link'>reward summary</a>. <p></span></div>";
  $('#hiddenmodalcontainer .modalBody').html(rewardsHtml);
  resetbindings();
}