var siteUrlPrefix = '';
var articleCommentCache = [];

var commentRules = {
  rules: {
    "comment[content]": "required"
  }
}

var messageRules = {
  rules: {
    "sf_message_outbox[to_str]": "required",
    "sf_message_outbox[title]": "required",
    "sf_message_outbox[message]": "required"
  }
}

function cancelEditComment(id){
  var editCtrl = $('div#comment-message-' + id);
  editCtrl.html(articleCommentCache[id]);
  $('div#commentfooter-' + id).show();
}

function editCommentSave(id){
  var url = siteUrlPrefix  + '/comment/ajaxEditComment?commentid=' + id;
  var editCtrl = $('div#comment-message-' + id);
 
  $.post(url, $('form#comment-edit-' + id).serialize(), function(r){
    editCtrl.html(r);
    $('div#commentfooter-' + id).show();
  });
}

function editComment(id){
  var url = siteUrlPrefix  + '/comment/ajaxEditComment?commentid=' + id;
  var editCtrl = $('div#comment-message-' + id);
  
  $.get(url, {}, function(r){
    articleCommentCache[id] = editCtrl.html();
    editCtrl.show();
    editCtrl.html(r);
    $('div#commentfooter-' + id).hide();
  });
}

function reply(id){
  var replybox = $("form#reply-" + id);
  replybox.toggle();
}

function voteUp(element, id){
  var url = siteUrlPrefix  + '/thing/ajaxUpVote?thingid=' + id;
  
  $.getJSON(url, function(data){
	  if (data.action == 'removed'){
		  $('img#link-up-' + id).attr('src', "/images/up.png");
	  } else {
	    $('img#link-up-' + id).attr('src', "/images/mod_up.png");
	  }
	  
	  $('img#link-down-' + id).attr('src', "/images/down.png");
	  $('span#link-score-' + id).text(data.score);
	  $('span#link-score-' + id).css({'color':'red'});
  });
}

function voteDown(element, id){
  var url = siteUrlPrefix  + '/thing/ajaxDownVote?thingid=' + id;
  
  $.getJSON(url, function(data){
    if (data.action == 'removed'){
      $('img#link-down-' + id).attr('src', "/images/down.png");
    } else {
      $('img#link-down-' + id).attr('src', "/images/mod_down.png");
    }
    
    $('img#link-up-' + id).attr('src', "/images/up.png");
    $('span#link-score-' + id).text(data.score);
    $('span#link-score-' + id).css({'color':'red'});
  });
}

