jQuery( document ).ready(function($) {
			$.getScript('//connect.facebook.net/en_US/sdk.js', function(){
				  window.fbAsyncInit = function() {
					  FB.init({
					    appId      : '1718094495145251',
					    version    : 'v2.8' 
					  });

					  FB.getLoginStatus(function(response) {
					    if (response.status === 'connected') {
					      		mainAPI();
						    } else {
						      	$('#status').append('Please log ' + 'into this app.');
						      	$('#main').addClass('disabled');
						    }
					  });
				  };
					(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.9&appId=1718094495145251";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
					
				  function mainAPI() {
				  	$('#main').removeClass('disabled');
				    FB.api('/me', function(response) {
				        $('#status').append('Thanks for logging in, ' + response.name + '!');
				        $.ajax({
					            url: '../db.php',
					            success: function (data) {
					                $('.message_container').html(data);
					            },
					            error: function (data) {
					                console.log('An error occurred.');
					            },
					    });
				    });
				  }
				  function child_comments() {
				  	
				  }
					$('.comment_button').click(function () {
						FB.api('/me', function(response) {
					    	comment_text = $('.comment_text').val();
					    	var date = Math.round($.now()/1000);
					        $.ajax({
					            url: '../commentsajax.php',
					            data: {userid: response.id, comment_date: date, comment_text: comment_text, parentid: 0},
					            success: function (data) {
					                $('.message_container').html(data);
					            },
					            error: function (data) {
					                console.log('An error occurred.');
					                console.log(data);
					            },
					        });
					    });
				    });
					$(document).on("click", ".answer", function(){
						var parentid = $(this).attr('data-parentid');
						var comment_html = '<textarea class="child_comments_text"></textarea><span class="child_comments" data-parentid="'+parentid+'">Подтвердить</span>'
						$(this).after(comment_html);
						$(this).remove();
					});
				    $(document).on("click", ".child_comments", function(){
				    	comment_text = $('.child_comments_text').val();
					    var parentid = $(this).attr('data-parentid');

						FB.api('/me', function(response) {
					    	var date = Math.round($.now()/1000);
					        $.ajax({
					            url: '../commentsajax.php',
					            data: {userid: response.id, comment_date: date, comment_text: comment_text, parentid: parentid},
					            success: function (data) {
					            	$('.message_container').html(data);
					            },
					            error: function (data) {
					                console.log('An error occurred.');
					                console.log(data);
					            },
					        });
					    });
				    });
			});
});