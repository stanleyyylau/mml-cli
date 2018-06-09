jQuery('document').ready(function($){
	$(".cool-timeline-horizontal").find("a[class^='ctl_prettyPhoto']").prettyPhoto({
		social_tools: false

	});
	$( '.cool-timeline-horizontal' ).each(function( index ) {
		var slider_id ="#"+ $(this).attr('date-slider');
		var slider_nav_id ="#"+ $(this).attr('data-nav');
		$(slider_id).slick({
			slidesToShow 	: 1,
			slidesToScroll 	: 1,
			asNavFor 		:slider_nav_id,
			arrows			:false,
			dots 			: false,
			adaptiveHeight: true,
			responsive 		: [
				{
					breakpoint: 768,
					settings: {
				//		arrows: true,
					//	centerMode: true,
						centerPadding: '10px',
						slidesToShow:1
					}
				},
				{
					breakpoint: 480,
					settings: {
					//	arrows: true,
					//	centerMode: true,
						centerPadding: '10px',
						slidesToShow: 1
					}
				}
			]});
		$(slider_nav_id).slick({
			slidesToShow: 3,
			slidesToScroll: 1,
			asNavFor:slider_id,
			dots: false,
			focusOnSelect: true,

			responsive 		: [
				{
					breakpoint: 768,
					settings: {
						arrows: true,
					//	centerMode: true,
						centerPadding: '10px',
						slidesToShow:2
					}
				},
				{
					breakpoint: 480,
					settings: {
						arrows: true,
					//	centerMode: true,
						centerPadding: '10px',
						slidesToShow: 1
					}
				}
			]
			});

	});
	
});
