jQuery('document').ready(function($){

if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
 // some code..
}else{
//var s = skrollr.init();
var s =skrollr.init({          
        mobileCheck: function() {
            //hack - forces mobile version to be off
            return false;
        }
    });
    } 
  $(".cool_timeline").find("a[class^='ctl_prettyPhoto']").prettyPhoto({
	 social_tools: false
	 
 });

	$(".cool-timeline-horizontal").find("a[class^='ctl_prettyPhoto']").prettyPhoto({
		social_tools: false

	});
	var ele_width=$(".cool_timeline").find('.timeline-content').find(".ctl_info").width();
	ele_width=ele_width-20;
	
	var value =ele_width
      value *= 1;
      var valueHeight = Math.round((value/4)*3);
	var animation= $(".cool_timeline").find('.ctl_flexslider').attr('data-animation');
	var slideshow_op= $(".cool_timeline").find('.ctl_flexslider').attr('data-slideshow');
	
	if(slideshow_op=="true"){
		slideshow=true;
	}else if(slideshow_op=="false"){
			slideshow=false;
	}else{
		slideshow=true;
	}
	var animationSpeed= $(".cool_timeline").find('.ctl_flexslider').attr('data-animationSpeed');
	// $(".cool_timeline").find('.ctl_flexslider').width(ele_width);
 //$(".cool_timeline").find('.ctl_flexslider .slides img ').width(ele_width);

		 $(".cool_timeline").find('.full-width > iframe').height(valueHeight);
	  $(".cool_timeline").find('.ctl_flexslider').flexslider({
		animation:animation,
		slideshow:slideshow,
		slideshowSpeed:animationSpeed,
		  smoothHeight: true
		
	});
  
  
  var pagination= $(".cool_timeline").attr('data-pagination');
  var pagination_position= $(".cool_timeline").attr('data-pagination-position');
	
	var bull_cls='';
	
	if(pagination_position=="left"){
		 bull_cls='section-bullets-left';
		
	}else if(pagination_position=="right"){
		 bull_cls='section-bullets-right';
		
	}
	
	if(pagination=="yes"){
		
		  $('body').sectionScroll({

		  // CSS class for bullet navigation
		  bulletsClass:bull_cls,

		  // CSS class for sectioned content
		  sectionsClass:'scrollable-section',

		  // scroll duration in ms
		  scrollDuration: 1500,

		  // displays titles on hover
		  titles: true,

		  // top offset in pixels
		  topOffset:80,

		  // easing opiton
		  easing: ''
		  
		});
	}
	
	if(pagination=="yes"){
    $('.bullets-container').hide();
    var offset = $('.cool_timeline').offset();
    var t_height = $('.cool_timeline').height();

    $('.bullets-container').hide();
    $(window).scroll(function () {
        // console.log($(this).scrollTop());
        if ($(this).scrollTop() > offset.top) {
            $('.bullets-container').show();
        }
        else {
            $('.bullets-container').hide();
        }

        if ($(window).scrollTop() + $(window).height() > $(document).height() - 200) {
            $('.bullets-container').hide();
        }
    });    
	}

});
