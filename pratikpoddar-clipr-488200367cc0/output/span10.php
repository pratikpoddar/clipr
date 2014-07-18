<?php 
  require_once 'prodlistutils.php';
  require_once 'utils.php';
?>


<!-- 
- Get variables expected by this file:
-   compulsary 
-     page
-   optional
-     pagination
-     userid 
-     tag
-     friendid
-->
<style type="text/css">
.clip-btn.disabled{
  opacity: 0.8;
}
.thumbnailoverride{
  position: relative;
  border: none;
  padding: 0px;
  border-radius: 0px;
}

.productcontainer{
  background-color: #FFF;
  padding: 8px 8px 0px 8px;
  margin: 10px 10px;
  box-shadow: 2px 2px 2px #aaa;
  float: left;
  width: 354px;
  min-height: 250px;
}

.imageContainer{
  position: relative;
  overflow: hidden;
  /*background-color: #000;*/
}

.popupdesc{
  font-size: 13px;
  position: absolute;
  width: 100%;
  left: 0px;
  background-color: #EEE;
  opacity: 0.87;
  color: black;
  border-radius: 0px;
  top: 2000px;
  height: 120px;
}

.buy-icon{
  position:relative;
  top:20px;
  margin-top:-20px;
  display:none;
  z-index: 2;
}

.product-image{

}

.hover-btn{
  border-radius: 0px;
  position: absolute; 
  z-index: 2;
  height: 40px;
  width: 77px;
  font-size: 18px;
  line-height: 40px;
  text-align: center;
  display: none;
  margin-left: 0px;
  top:60px;
}
.hover-tag-btn{
  right: 35px;
}
.hover-clip-btn{
  left: 35px;
}
.trending-clippers{
  padding: 3px 0px;
  height: 29px;
  line-height: 29px;
  font-size: 13px;
}

.trending-clippers>a{
  font-size: 29px;
}

.buy-link-container{
  display: none;
  position: absolute;
  top: 15px;
  right: 15px;
}
.trending-title{
  padding: 3px 0px;
  overflow: hidden;
  display: inline-block;
  text-overflow: ellipsis;
  white-space: nowrap;
  height: 29px;
  line-height: 29px;
  font-size: 13px;
  border-bottom: 1px solid;
  width: 100%;
  border-color: #DDD;
}
.no-clips{
  font-style: italic;
  color: #666;
}
</style>


    <script type="text/javascript">

        $(".nav<?php echo $_GET['page'] ?>").addClass("active");
        function getProductContainerElem(docid, pid, src, alt, clipunclip, clipcount, relevanttag, price, description, link, clippers){
          var width = 354;
          var height = 354;
          var descHover = (description.trim() != "")
          var desctext = '<b>'+alt+'</b><br/>'+description;
          var trimmedDescription  = $.trim(desctext);
          if(clippers == "")
            clippers = '<span class="no-clips">Be the first to clip</span>'
          // <span id="clip-btn'+pid+'" rel="tooltip" data-placement="top" class="btn btn-primary hover-btn hover-clip-btn" data-fancybox-href="#tagbox">Clip</span>\
          src = src.replace('-big.jpg','-crop.jpg');
          return '\
              <div class = "productcontainer" data-pid="'+pid+'"> \
                <div id = "product'+pid+'">\
                <a style="text-decoration:none;min-height:60px;" class="thumbnail thumbnailoverride modalbox" href="product/'+pid+'" data-fancybox-href="#inlineproduct" onClick="initializeProduct('+pid+')" >\
                  <div class = "imageContainer" data-desc-required="'+descHover+'" data-relevant-tag = "'+relevanttag+'" data-initial-clip="'+clipunclip+'">\
                    <span class = "pricetagContainer">\
                      <span style="display:none" id = "pricetag'+pid+'" class="label label-inverse pricetag rotate">Rs. ' + price + '</span>\
                    </span>\
                    <span id="tag-btn'+pid+'"  data-productid='+pid+' rel="tooltip" data-placement="top" data-original-title="Tag a friend" class="btn btn-primary hover-btn hover-tag-btn tag-btn" data-fancybox-href="#tagbox">Tag</span>\
                    <span id="clip-btn'+pid+'" rel="tooltip" data-placement="top" class="btn btn-primary hover-btn hover-clip-btn" data-fancybox-href="#tagbox">Clip</span>\
                    <img style="height:'+height+'px;width:'+width+'px" id="img'+pid+'" class="product-image" data-product-title="'+alt+'" data-product-id="'+pid+'" src="'+src+'" alt="'+alt+'"/>\
                    <div class="popupdesc" id = "desc'+pid+'">\
                      <div style="padding:8px 0px" id ="desctext'+pid+'">'+trimmedDescription+'</div>\
                    </div>\
                  </div>\
                </a>\
                <a id="buylink'+pid+'" class="buy-link-container" style="text-decoration:none;" target="_blank" href="cliprtransfer?link='+link+'"><i class="icon-shopping-cart" style="font-size:20px;"></i></a>\
                </div>\
                <div id="trending-title'+pid+'" class="trending-title"><a href="product/'+pid+'" class="ajax-link"><b>'+alt+'</b></a></div>\
                <div id="clippers'+pid+'" class="left trending-clippers">'+clippers+'</div>\
              </div>';
        }

        function addProduct(elem){

          var pid = elem.data('pid');
          elem.find('.product-image').preloader();
          elem.find('.product-image').css({opacity:0});
          elem.find('.imageContainer').imagesLoaded(function(){
            console.log(pid);
            elem.find('.imageContainer').css({'background-color':'#fff'});
            elem.find('.product-image').animate({opacity:1});
          });
          setTimeout(function(){elem.find('.product-image').animate({opacity:1});},500);
          var $product = $('.imageContainer',elem);
          $("#product"+pid).hover(
            function() {
              $("#pricetag"+pid).show(); 
              $("#tag-btn"+pid).show(); 
              $("#clip-btn"+pid).show()
            },
            function() {
              $("#pricetag"+pid).hide(); 
              $("#tag-btn"+pid).hide(); 
              $("#clip-btn"+pid).hide();
            }
          );

          $("#product"+pid).hover(
            function() { 
              $("#img"+pid).css({opacity:0.6});
            },
            function() { 
              $("#img"+pid).css({opacity:1});
            }
          );

          $("#desctext"+pid).ready(function() {
            $("#desctext"+pid).condense(
              {
                condensedLength: 180,
                moreText: '',
                lessText: ''
              });
          });

          // attach hover description event
          if( $product.data('desc-required') ) {
            $("#product"+pid).hover(function(){
              var descOffset = parseInt($(this).height()-100) + 'px';
              $("#desc"+pid, this).stop().animate({top:descOffset},{queue:false,duration:160});
            }, function() {
              var descOffset = parseInt($(this).height()+40) + 'px';
              $("#desc"+pid, this).stop().animate({top:descOffset},{queue:false,duration:160});
            });
          }

          // set initial hidden location of onhover description after image has loaded
          $("#img"+pid).load(function(){
            var descOffset = parseInt($("#product"+pid).height()+1040) + 'px';
            $("#desc"+pid).css('top',descOffset);
          });

          // bind hover event to display relevant interest
          $("#product"+pid).hover(
            function() {
              $("#buylink"+pid).show(); 
            },
            function() {
              $("#buylink"+pid).hide(); 
            });

          // set correct clip/unclip button
          if($product.data('initial-clip') == "Clip")
            changeToUnclippedTrending("clip-",pid);
          else if($product.data('initial-clip') == "Unclip")
            changeToClippedTrending("clip-",pid);
          else
            changeToUnclippedTrending("clip-",pid);
          return elem;
        }

        function addProductInfo(docid, pid, src, alt, clipunclip, clipcount, relevanttag, price, description, link, clippers) {
          var elem = $(getProductContainerElem(docid, pid, src, alt, clipunclip, clipcount, relevanttag, price, description, link, clippers));
          $("#maincontent").append(elem);
          addProduct(elem);
        }
    </script>

    <script type="text/javascript">
  function changeToClippedTrending(prefix, pid){
    var btnid = '#' + prefix + 'btn' + pid;
    $(btnid).toggleClass('disabled', true);
    $(btnid).html("<i class='icon-ok-sign'></i> Clipped");  
    $(btnid).css({'opacity':0.9, 'font-size':'16px'});
    $(btnid).attr('data-original-title','Already clipped');
    bindClippedActionTrending(prefix,pid);
  }

  function bindClippedActionTrending(prefix,pid){
    var btnSelector = '#'+prefix+'btn'+pid;
    $(btnSelector).unbind();
    $(btnSelector).unbind('click');
    $(btnSelector).click(function(e){
      e.stopPropagation();
      e.preventDefault();
    });
  }

  function changeToUnclippedTrending(prefix, pid){
    var btnid = '#' + prefix + 'btn' + pid;
    $(btnid).toggleClass('disabled', false);
    $(btnid).css({'opacity':1, 'font-size':'18px'});
    $(btnid).html("Clip");
    $(btnid).attr('data-original-title','You \"Clip\" what you \"Like\"');
    bindUnclippedActionTrending(prefix,pid);
  }

  function bindUnclippedActionTrending(prefix,pid){
    var btnSelector = '#'+prefix+'btn'+pid;
    $(btnSelector).unbind();
    $(btnSelector).click(function(event){
      if($.cookie('loggedUserId') && $.cookie('loggedUserId') !== null)
        clipActionTrending(pid, prefix, this);
      else
        showLoginError("clip","http://"+window.location.host+"/output/product/"+pid );
      event.stopPropagation();
      event.preventDefault();
    });
  }

  function clipActionTrending(pid,prefix,e){
    var text = $('#'+prefix+'btn'+pid).text();
    _gaq.push(['_trackEvent', 'User-Action', text, pid]);
    $.ajax({
      type: "GET",
      url: "getBoards",
      data: {pid: pid,timestamp:+new Date}
    }).done(function(data){
      updateClippers(pid, (JSON.parse(data)).clipperstring);
      setTimeout(function(){$('#maincontent').masonry( 'reload' );},200);
    });
    setTimeout(function(){changeToClippedTrending(prefix,pid);},500);
  }

      <?php
        if (count($productIds) > 0) {
          foreach ($listOfGroups as $groupName) {
            $i = 1;
            $nproduct=0;
            foreach ($productIds as $pid) {
              if ($groupNames[$nproduct] == $groupName)
              {
                echo addProduct($con, $pid, $groupName."col".$i, $loggedUserID);
                $i=($i%2)+1;
              }
              $nproduct++;
            }
          }
        }
      ?>
      var $container = $('#maincontent');
      $container.masonry({
        itemSelector : '.productcontainer',
        columnWidth : 390,
        isAnimated: true
      });

    </script>
  <!-- Code for preloading -->

  <script type="text/javascript">

  </script>
<!-- End Code for preloading -->
