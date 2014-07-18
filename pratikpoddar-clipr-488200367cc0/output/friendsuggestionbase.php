<?php
  require_once 'utils.php';
  require 'img/interestsImage.php';
?>

<style type="text/css">

.interest-btn {
  margin:7px;
  width: 15%;
  min-width: 130px;
}

.interest-btn.btn-primary svg {
  fill: #eff6ff;
}

.interest-btn.btn-default svg {
  fill: #000;
}

</style>

<script type="text/javascript">

function submitInterests() {

  interestsList = $(".btn-primary.interest-btn");
  interests = [];
  $.each(interestsList, function(key, value) { 
    interests.push(parseInt($(value).val()))
  });

  console.log("submitting " + interests.length + " interests");

  if (interests.length < 5 )
  {
    alert("Please select at least 5 interests");
    return;
  }
  
  if ($('#hiddenfriendid').text() == "")
    $('#hiddenfriendid').text(Math.random() * (1000000000000000000 - 100000000000000000) + 100000000000000000)
  $(".interest-btn").addClass('disabled');
  $.ajax({
    type: "GET",
    url: "submitinterests",
    data: { userId:$('#hiddenfriendid').text(),source: <?php echo $loggedUserID ?>, interests: interests  }
  }).done(function( msg ) {
    getFriendSuggestions($('#hiddenfriendid').text());
  });
  mixpanel.track("find gift suggestion");
  _gaq.push(["_trackEvent", "Auto-Action", "find-gift-suggestion"]);
}

</script>

<span align="center">

  <h4 id = "friendsuggestionheading">Looking for a gift? Search for a friend or add interests</h4>
  <div class="well" align="center">
    <input class="span4" style="height:25px" id="friendsearch" type="text" placeholder="Enter Friend's Name">
    <input type="hidden" style="display:none" id = "hiddenfriendid">
    <div class="row-fluid" align="center">
      <?php 
      $sql = "SELECT * FROM allTags order by name";
      $result = mysql_query($sql,$con);
      if (!$result) { error_log(mysql_error()); die('Error: ' . $sql . mysql_error());};   
      while ($allTag = mysql_fetch_row($result)) {
        $image = $representativeImage[$allTag[1]];
        echo "<button class='btn interest-btn btn-default' id=interestid".$allTag[0]." value=".$allTag[0]." onClick='if(! $(this).hasClass(\"disabled\") ) $(this).toggleClass(\"btn-primary btn-default\")'><div>".$image."</div>".ucwords(str_replace("_", " ", $allTag[1]))."</button>";
      }
      ?>
    </div>
    <button class="btn btn-large btn-primary" onClick="javascript:submitInterests()">Submit</button>
  </div>
  <span style="font-size:90%">* Interests are prefilled as per information provided by the friend</span>

</span>

<script type="text/javascript">
  $('#friendsearch').typeahead({
    source: function (query, process)
    {
      $('#hiddenfriendid').text("");
      $.get('findfriend', { userid: <?php echo $loggedUserID ?>, partial: query }, function (rawdata)
      {
        names = [];
        mappednames = {};
        mappedgender = {};
        data = JSON.parse(rawdata);
        $.each(data.names, function (i, item) {
          mappednames[item.name] = item.userid;
          mappedgender[item.name] = item.gender;
          names.push(item.name);
        });
        process(names);
      });
    },
    minLength: 2,
    items:5,
    //TODO: is toggle the right way?
    updater: function(item){
      $('#hiddenfriendid').text(mappednames[item]);
      found = 0;
      $.get('getinterests', { userid: mappednames[item] }, function (rawdata)
        {
          var pronoun = 'her';
          if(mappedgender[item] == 'male')
            pronoun = 'his';
          data = (rawdata);
          if ($.isEmptyObject(data)){
            $("#friendsuggestionheading").text("Unfortunately "+ item + " has not indicated "+pronoun+" interests on Clipr. Please select a few interests")
          }
          else {
            $("#friendsuggestionheading").text("Looks like this is going to be a piece of cake! " + item + " is already on Clipr and has selected " + pronoun + " interests.");
            $('.interest-btn').removeClass("btn-primary");
            $('.interest-btn').addClass("btn-default");
            $.each(data, function (i, elem) {
              found=1;
              $('#interestid'+elem.interest).toggleClass("btn-primary btn-default");
            });
          }
        }, "json");
      return item;
    },
    highlighter: function(item){
      var itm = ''
               + "<div class='typeahead_wrapper'>"
               + "<img class='typeahead_photo' src='http://graph.facebook.com/" + mappednames[item] + "/picture?type=square' />"
               + "<div class='typeahead_labels'>"
               + "<div class='typeahead_primary'>" + item + "</div>"
               + "</div>"
               + "</div>";
      return itm;
    }
  });
</script>

