'use strict';

(function ($){
	$(document).ready(function(){

		if (window.sessionStorage && window.localStorage) {

			var lastView = localStorage.getItem("brandsPopupLastView"),
				date = Date.now(),
				timeLimit = 86400000,
				popup = $('#brands-popup');

			var needView = parseInt(lastView) + parseInt(timeLimit);

			if(!lastView || (parseInt(needView) < parseInt(date))){
				popup.css({
					display: 'block'
				});

				popup.find('.close').click(function(){
					localStorage.setItem("brandsPopupLastView", date);
					popup.remove();
				});
			}
		}
		else {

		}

	});
})(jQuery);