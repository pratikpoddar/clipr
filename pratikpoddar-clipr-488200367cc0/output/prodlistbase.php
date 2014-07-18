<style type="text/css">
  .companylogo {
    max-height : 30px;
    max-width : 75px;
  }

  .iclassUnclip {
    font-size: 30;
    margin-left: -20;
  }

  .iclassClip {
    font-size: 0;
  }

  .iclassLogClip {
    font-size: 0;
  }

  .productInfo .right {
    text-align: right;

  }

  .productInfo .left {
    text-align: left;
  }

  .font11 {
    font-size: 10pt;
  }

  .font12 {
    font-size: 11pt;
  }

  .font13 {
    font-size: 12pt;
  }

  .font15 {
    font-size: 14pt;
  }

  .sidebarlabel {
    width: 100%;
    margin-top: 2px;
    margin-bottom: 2px;
    padding: 2px;
  }

  .sidebarlabel:hover{
    background-color: #eaeaea;
  }

.rotate {

  /* Safari */
  -webkit-transform: rotate(-45deg);

  /* Firefox */
  -moz-transform: rotate(-45deg);

  /* IE */
  -ms-transform: rotate(-45deg);

  /* Opera */
  -o-transform: rotate(-45deg);

  filter: progid:DXImageTransform.Microsoft.Matrix(sizingMethod='auto expand', M11=0.7071067811865476, M12=-0.7071067811865475, M21=0.7071067811865475, M22=0.7071067811865476); /* IE6,IE7 */
  -ms-filter: "progid:DXImageTransform.Microsoft.Matrix(SizingMethod='auto expand', M11=0.7071067811865476, M12=-0.7071067811865475, M21=0.7071067811865475, M22=0.7071067811865476)"; /* IE8 */
  /* Internet Explorer */
  }

  .pricetagContainer {
    overflow: hidden;
    width: 80px;
    height: 80px;
    position: absolute;
    z-index: 3;
  }

  .pricetag {
    background-color: #DDD;
    color: #222;
    font-size: 14px;
    font-weight: normal;
    left: -50px;
    padding: 3px 52px;
    position: absolute;
    top: 24px;
    text-shadow: none;
    border-radius: 0px;
  }

  .fade {
    opacity:0.2;
    filter:alpha(opacity=20); /* For IE8 and earlier */
  }
</style>
    <div class="row-fluid" id = "sidebar">
      <?php 
        require 'span2bar.php';
      ?>
    </div>
    <div class="row-fluid" id="heading">
      <?php 
        require 'heading.php';
      ?>
    </div>
      <!-- <div class="span2" id = "sidebar">
        <?php 
          //require 'span2.php';
        ?>
      </div> -->
    <div id = "maincontent">
      <?php 
        require 'span10.php';
      ?>
    </div>

    <div id = "paginationdiv">
      <?php 
        require 'pagination.php';
      ?>
    </div>

<script>
</script>