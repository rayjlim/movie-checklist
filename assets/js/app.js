(function(d){
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

	$(d).on('keyup',function(e){
		var k = e.keyCode;
		var ctrl = e.ctrlKey;
		if ( ctrl && k == 67 ) {
			toggleLogin();
		}
	});

	$(d).on('click',$('body > .navbar .close'),function(){
		if ( $('body > .navbar').css('display') == 'block' ) {
			toggleLogin();
		}
	});
})(document);