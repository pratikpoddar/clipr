function initializeCanvasByCid(cid){
  $('#collagemaker-canvas').html('');
  $.ajax({
    type: "GET",
    url: "getCollage",
    data: {cid:cid}
  }).done(function( msg ) {
    data = JSON.parse(msg);
    if(data==false)
      $.gritter.add({
        title: 'Invalid collage',
        text: '<span class="gritterText">Collage has been deleted or moved to a new place.</span>',
        image: 'img/clipr.png',
        sticky: false,
        time: 2000
      });
    else{
      initializeCanvas(JSON.parse(data.collage));
      $('#collage_id').attr('value',cid);
    }
  });
}

function initializeCanvas(objInfos){
  $('#collagemaker-canvas').html('');
  $.each(objInfos, function(i,val){
    var imageObj = $('<img class="draggable-image" data-product-title="'+val.title+'" data-product-id="'+val.pid+'" src="'+val.src+'" alt="'+val.title+'" >');
    imageObj.height(val.height);
    imageObj.width(val.height*val.aspectRatio);
    appendImageToDroppable($(".droppable"),imageObj);
    positionObjectInCanvas(imageObj,val);
  });
}

function resizeObject( obj, height, aspectRatio ){
  obj.height(height);
  obj.width(height*aspectRatio);
  obj.parent().height(height+2);
  obj.parent().width(height*aspectRatio+2);
}

function positionObjectInCanvas( obj, objInfo ){
  resizeObject(obj,objInfo.height,objInfo.aspectRatio);
  obj.parent().css('z-index',objInfo.zindex);
  obj.parent().parent().css('left',objInfo.leftOffset);
  obj.parent().parent().css('top',objInfo.topOffset);
  obj.parent().rotate(objInfo.rotation);
}

function getRotationDegrees(obj) {
    var matrix = obj.css("-webkit-transform") ||
    obj.css("-moz-transform")    ||
    obj.css("-ms-transform")     ||
    obj.css("-o-transform")      ||
    obj.css("transform");
    if(matrix !== 'none') {
        var values = matrix.split('(')[1].split(')')[0].split(',');
        var a = values[0];
        var b = values[1];
        var angle = Math.round(Math.atan2(b, a) * (180/Math.PI));
    } else { var angle = 0; }
    return angle;
}
function jsonifyCollage(){
  var images = $('#collagemaker-canvas>div>div>img');
  var objectInfos = $('#collagemaker-canvas>div>div>img').map( function(){
    var pid = $(this).data('product-id');
    var title = $(this).data('product-title');
    var src = $(this).attr('src');
    var rotation = getRotationDegrees($(this).parent());
    var aspectRatio = $(this).width()/$(this).height();
    var height = $(this).height();
    var zindex = $(this).parent().css('z-index');
    var topOffset = $(this).parent().parent().css('top');
    var leftOffset = $(this).parent().parent().css('left');
    var res =  {
      'pid':pid,
      'title':title,
      'src':src,
      'rotation':rotation,
      'aspectRatio':aspectRatio,
      'height':height,
      'zindex':zindex,
      'topOffset':topOffset,
      'leftOffset':leftOffset
    };
    return res;
  }).get();
  var cid;
  if($('#collage_id').attr('value') && $('#collage_id').attr('value') !== "")
    cid = parseInt($('#collage_id').attr('value'));
  else
    cid=0;
  var uid;
  if($.cookie('loggedUserId'))
    uid = $.cookie('loggedUserId');
  else
    uid = 0;
  return {cid:cid, uid:uid, collage:objectInfos};
}

function initializeCollageForm(collageInfo){
  var collage = collageInfo.collage;
  $("#productbaseImage").preloader();
  $('#fancyCollageForm').hide()
  $('#collageFormLoadingImage').show();
  $.ajax({
    type: "GET",
    url: "previewCollage",
    data: {collage: JSON.stringify(collage)}
  }).done(function( data ) {
    if(collageInfo.cid != 0){
      $.ajax({
        type: "GET",
        url: "getCollage",
        data: {cid: collageInfo.cid}
      }).done(function(msg){
        fetchedCollageInfo = JSON.parse(msg);
        $('#collageDesc').val(fetchedCollageInfo.description);
        $('#collageTitle').val(fetchedCollageInfo.heading);
        $('#collagePreview').attr('src','../tempCollage/'+data+'.png');
        $('#fancyCollageForm').show();
        $('#collageFormLoadingImage').hide();
        $('#submitCollage').unbind('click');
        $('#submitCollage').click(function(e){
          publishCollage();
        })
        resetbindings(collageInfo);
      })
    }else{
      $('#collageDesc').val('');
      $('#collageTitle').val('');
      $('#collagePreview').attr('src','../tempCollage/'+data+'.png');
      $('#fancyCollageForm').show();
      $('#collageFormLoadingImage').hide();
      $('#submitCollage').unbind('click');
      $('#submitCollage').click(function(e){
        publishCollage(collageInfo, $('#collageTitle').val(), $('#collageDesc').val());
      })
      resetbindings();
    }
  });
}

function publishCollage(collageInfo, heading, description){
  var collage = collageInfo.collage;
  var data;
  if(collageInfo.cid != 0)
    data = { collage: JSON.stringify(collage), heading:heading, description : description, cid: collageInfo.cid };
  else
    data = { collage: JSON.stringify(collage), heading:heading, description : description};
  if($.cookie('loggedUserId') && $.cookie('loggedUserId') !== "")
  {
    $.ajax({
      type: "GET",
      url: "publishCollage",
      data: data
    }).done(function(msg){
      // set collage id in DOM
      $('#collage_id').attr('value',msg);
      saveCollageToCookie();
      executeupdate('collage/'+msg);
    });
  }
  else{
    if(collageInfo.cid != 0)
      showLoginError ( "Publish Collage", "../output/publishCollage?collage="+JSON.stringify(collage)+"&heading="+encodeURIComponent(heading)+"&description="+encodeURIComponent(description)+"&cid="+collageInfo.cid+"&redirect=true" );
    else
      showLoginError ( "Publish Collage", "../output/publishCollage?collage="+JSON.stringify(collage)+"&heading="+encodeURIComponent(heading)+"&description="+encodeURIComponent(description)+"&redirect=true" );
  }
}

function appendImageToDroppable( canvas, draggableImg ){
  var draggableElem = $('<div class="draggable-wrapper"></div>');
  draggableElem.append(draggableImg[0]);
  canvas.append(draggableElem);
  draggableImg.resizable({
    aspectRatio: draggableImg.width()*1.0/draggableImg.height(),
    handles: "se"
  });

  draggableElem.draggable({
    revert:"invalid",
    revertDuration:300,
    tolerance: "pointer",
    containment: "#collagemaker-canvas",
    start: function( event, ui ) {
      $(this).children('div').css('overflow','hidden');
      // unselectElem($('img',this));
    },
    stop: function( event, ui ) {
      $(this).children('div').css('overflow','');
      // selectElem($('img',this));
    }
  });

  draggableImg.parent().rotatable();

  $( ".droppable" ).selectable({
    filter:">div>div>img",
    selected: function( event, ui ){
    },
    unselected: function(event, ui ){
      unselectElem($(ui.unselected));
    }
  });

  // add remove button 
  draggableImg.parent().append('<div class="ui-remove-handle ui-draggable"><i class="icon-remove-sign"></i></div>');
  draggableImg.parent().css('overflow','');

  // bind remove button 
  bindRemoveButton(draggableImg);

  // resize image so that height is at max 250 and width is at max 200
  var ar = draggableImg.width()*1.0/draggableImg.height();
  resizeObject(draggableImg, Math.min(200,150/ar),ar );
  
  // position image randomly near top left
  draggableImg.parent().parent().css('left',Math.floor(Math.random()*200)+'px');
  draggableImg.parent().parent().css('top',Math.floor(Math.random()*200)+'px');

  // bind select action (selectable doesnt work because element is draggable)
  draggableImg.mousedown(function() {
    selectElem($(this));
  });
  selectElem(draggableImg);
}

function populateAlternateImages(controlee){
  $('#collagemaker-info-inner').html('');
  $.ajax({
    type: 'GET',
    url: 'getAllImages',
    data: { pid: controlee.data('product-id') }
  }).done(function(msg){
    var images = JSON.parse(msg);
    $.each(images, function(i,val){
      var thumbnailElem = $('<img class="collage-info-thumbnails" src="'+val.small+'">');
      $('#collagemaker-info-inner').append(thumbnailElem);
      thumbnailElem.click(function(){
        controlee.attr("src", 'img/ajax_loader.gif');
        var img = new Image();
        img.onload = function() {
          controlee.attr("src", val.big);
        }
        img.src = val.big;
      })
    })
  });
  $('#collagemaker-info').jScrollPane({
    autoReinitialise: true,
    autoReinitialiseDelay: 1000,
  });
}

function selectElem(elem){
  populateAlternateImages(elem);
  bringToFront(elem);
  if (!elem.hasClass("ui-selected")) {
    elem.addClass("ui-selected");
    elem.parent().parent().siblings().children().children().removeClass("ui-selected");
  }
  elem.siblings(".ui-rotatable-handle").show();
  elem.siblings(".ui-remove-handle").show();
  elem.parent().parent().siblings().children().children(".ui-rotatable-handle").hide();
  elem.parent().parent().siblings().children().children(".ui-remove-handle").hide();

}

function bindRemoveButton(imageObj){
  imageObj.siblings('.ui-remove-handle').click(function(){
    $(this).parent().parent().remove();
    // Also remove alternate images
    $('#collagemaker-info-inner').html('');
  });
}

function bringToFront(e){
  var index_highest = 0;
  e.parent().parent().siblings().children().each(function() {
  // always use a radix when using parseInt
    var index_current = parseInt($(this).css("z-index"), 10);
    if (index_current > index_highest) {
        index_highest = index_current;
    }
  });
  e.parent().css('z-index',index_highest+1);
}

function unselectElem(unselected){
  unselected.siblings(".ui-rotatable-handle").hide();
  unselected.siblings(".ui-remove-handle").hide();
  unselected.removeClass('ui-selected');
}

function saveCollageToCookie(){
  var currCollage = jsonifyCollage();
  var collageStr = JSON.stringify(currCollage);
  if(collageStr != '' && collageStr!= "")
    $.cookie("temp-collage",collageStr ,{ path: '/'});
}

function cookieCollageValidForUser(){
  return (
    $.cookie("temp-collage") && 
    (
      JSON.parse($.cookie("temp-collage")).uid == 0 || 
      !$.cookie('loggedUserId') || 
      (
        JSON.parse($.cookie("temp-collage")).uid == $.cookie('loggedUserId')
      )
    ) 
  );
}

function deleteCollage(){
  if(!$('#collage_id').attr('value') || parseInt($('#collage_id').attr('value')) < 1 || !$.cookie('loggedUserId'))
    $.gritter.add({
      title: 'Deleting unsaved collage',
      text: '<span class="gritterText">This collage is not yet saved. Use Clear to start afresh</span>',
      image: 'img/clipr.png',
      sticky: false,
      time: 2000
    });
  else{
    $.ajax({
      type: "GET",
      url: "deleteCollage",
      data: {cid:$('#collage_id').attr('value')}
    }).done(function( msg ) {
      if(msg)
        $.gritter.add({
          title: 'Collage Deleted',
          text: '<span class="gritterText">This collage has been deleted</span>',
          image: 'img/clipr.png',
          sticky: false,
          time: 2000
        });
    });
    clearCanvas();
  }
}

function clearCanvas(){
  $.cookie("temp-collage", '',{ path: '/',expires:-100 });
  $('#collagemaker-canvas').html('');
  $('#collagemaker-info-inner').html('');
  $('#collage_id').attr('value','');
}

function loadCollageFromCookie(){
  if($.cookie("temp-collage") && cookieCollageValidForUser()){
    var savedCollage = JSON.parse($.cookie("temp-collage"));
    $('#collage_id').attr('value',savedCollage.cid);
    initializeCanvas(savedCollage.collage);
  }
}

function headingAndDescriptionFancyBox(msg, id){

}
var saveCollage;

function collageInit(){
  // load current collage
  loadCollageFromCookie();

  // clear save collage interval
  if(typeof(saveCollage)!=='undefined')
    clearInterval(saveCollage);

  // save collage every second
  saveCollage = setInterval(
    function(){
      saveCollageToCookie();
    },
    1000
  );

  // make collage window draggable
  $('#collagemaker').draggable();

  // make canvas draggable
  $(".droppable").droppable({
    drop: function (event, ui) {
      if(ui.draggable[0].id){
        var jqimg = $(ui.helper).clone().draggable().children().eq(2);
        appendImageToDroppable($(this), jqimg);
      }
    },
    tolerance: "pointer"
  });

  // make images draggable
  $('.imageContainer').draggable({helper:'clone', revert:"invalid",revertDuration:300,scroll: true});

  // bind clear canvas button in a functional manner
  $('#clearCanvas').unbind('click');
  $('#clearCanvas').click(function(){
    clearCanvas();
  });

  // bind publish collage button in a functional manner
  $('#publishCollage').unbind('click');
  $('#publishCollage').click(function(){
    initializeCollageForm(jsonifyCollage());
  });

  $('#deleteCollage').unbind('click');
  $('#deleteCollage').click(function(){
    deleteCollage();
  });

  $('#minimize-info').unbind('click');
  $('#minimize-info').click(function(){
    $(this).toggleClass('icon-chevron-left icon-chevron-right');
    $('#collagemaker-info').animate({width:'toggle'});
  });
}

