(function(d){
	var logged_in = false;

	var toggleLogin = function() {
		if ( $('body > .navbar').css('display') == 'none' ) {
			$('body').animate({'padding-top':'41px'},500);
			$('body > .navbar').slideDown(500);
		}
		else {
			$('body > .navbar').slideUp(500);
			$('body').animate({'padding-top':'0px'},500);
		}
	};

	var loginStatus = function(s) {
		if ( s == '1' ) {
			$('body > .navbar form input').addClass('success').attr('disabled','disabled');
			$('body > .navbar form button').attr('disabled','disabled');
			$('body > .navbar form input[name="password"]').val('thisissilly');
			logged_in = true;
			if ( $('.search-form.add-movie').length === 0 ) {
				d.location.reload();
			}
		}
		else if ( s == '0' ) {
			$('body > .navbar form input').addClass('error');
		}
	};

	var searchRotten = function(query,page) {
		if ( query.length < 3 ) {
			return false;
		}
		else {
			if ( isNaN(parseInt(page,10)) || parseInt(page,10) < 1 ) { page = 1; }
			$.post('index.php/searchmovie','search='+query+'&page='+page,function(data){
					$('.add-movie .movie-list ul').html('');
					$('.add-movie .movie-list ul').append(data);
					$('.add-movie .movie-list').slideDown(500);
			});
		}
	};

	$(d).on('status_change',function(e,new_status){
		if ( new_status == '1' ) { loginStatus('1'); }
	});

	$(d).on('keyup',function(e){
		if ( $(e.target).hasClass('rotten') ) { searchRotten($(e.target).val()); }
		var k = e.keyCode;
		var ctrl = e.ctrlKey;
		if ( ctrl && k == 67 ) {
			toggleLogin();
		}
	});

	$(d).on('click','body > .navbar .close',function(){
		if ( $('body > .navbar').css('display') == 'block' ) {
			toggleLogin();
		}
	});

	$(d).on('click','.add-movie .movie-list ul li',function(e) {
		$('.add-movie .movie-list').css('display','none');
		var movieid = $(e.currentTarget).attr('data-movie-id');
		$.post('index.php/movieinfo','movie_id='+movieid,function(data){
			console.log(data);
		});
	});

	$(d).on('submit','body > .navbar form',function(e){
		e.preventDefault();
		if ( !logged_in ) {
			var f = $(e.currentTarget);
			$('body > .navbar form input').removeClass('success error');
			var user = f.find('input[name="username"]').val();
			var pass = f.find('input[name="password"]').val();
			if ( user.length === 0 || pass.length === 0 ) {
				$('body > .navbar form input').addClass('error');
			}
			else {
				$.post('index.php/login','user='+user+'&pass='+pass,function(data) {
					loginStatus(data);
				});
			}
		}
		else {
			loginStatus(1);
		}
	});


	$.post('index.php/status',function(data) {
		$(d).trigger('status_change',[data]);
	});

	$(d).ready(function() { $('.search-form input').val(''); });


})(document);