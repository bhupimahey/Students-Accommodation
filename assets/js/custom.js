// JavaScript Document

$('.sliders').owlCarousel({
    loop:true,
	autoplay:true,
	animateOut: 'fadeOut',
autoplayTimeout:3000,
    margin:10,
    responsiveClass:true,
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:1,
            nav:false
        },
        1000:{
            items:1
        }
    }
});

$('.viewss').owlCarousel({
    loop:true,
	autoplay:true,
	
autoplayTimeout:3000,
	smartSpeed:1000,
    margin:10,
	 lazyLoad : true,
    responsiveClass:true,
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:4,
            nav:true
        },
        1000:{
            items:4
        }
    }
});


$(document).ready(function() {
              var owl = $('');
              owl.owlCarousel({
                margin: 10,
				smartSpeed:3000,
                lazyLoad : true,
				autoplayTimeout:3000,
                loop: true,
				autoplay: true,
                responsive: {
                  0: {
                    items: 1
                  },
                  600: {
                    items: 5
                  },
                  1000: {
                    nav: true,
                    items: 5
                  }
                }
              });
            });			




//stick
$(window).scroll(function(){
    if ($(window).scrollTop() >= 300) {
       $('.navbars').addClass('fixed-header');
    }
    else {
       $('.navbars').removeClass('fixed-header');
    }
}); 

$(window).scroll(function(){
    if ($(window).scrollTop() >= 300) {
       $('.stickys').addClass('fixed-headers');
    }
    else {
       $('.stickys').removeClass('fixed-headers');
    }
});

$(window).scroll(function(){
    if ($(window).scrollTop() >= 300) {
       $('.outer-md2').addClass('fixed-headerss');
    }
    else {
       $('.outer-md2').removeClass('fixed-headerss');
    }
});
//stickyheader function

// $(window).scroll(function() {
//     if ($(this).scrollTop() > 1){  
//         $('header').addClass("sticky");
	
//     }
//     else{
//         $('header').removeClass("sticky");
//     }
// });

//home about tabs


/*Add class when scroll down*/
$(window).scroll(function(event){
  	var scroll = $(window).scrollTop();
    if (scroll >= 50) {
        $(".go-top").addClass("show");
    } else {
        $(".go-top").removeClass("show");
    }
});
/*Animation anchor*/
$('a').click(function(){
    $('html, body').animate({
        scrollTop: $( $(this).attr('href') ).offset().top
    }, 1000);
});

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
  
  
  
  function openCity(cityName,elmnt,color) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].style.backgroundColor = "";
    }
    document.getElementById(cityName).style.display = "block";
    elmnt.style.backgroundColor = color;

}
// Get the element with id="defaultOpen" and click on it
// document.getElementById("defaultOpen").click();




