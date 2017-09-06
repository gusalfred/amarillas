/*!
 * Start Bootstrap - Agency Bootstrap Theme (http://startbootstrap.com)
 * Code licensed under the Apache License v2.0.
 * For details, see http://www.apache.org/licenses/LICENSE-2.0.
 */

// jQuery for page scrolling feature - requires jQuery Easing plugin
/*
$(function() {
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
});

// Highlight the top nav as scrolling occurs
$('body').scrollspy({
    target: '.navbar-fixed-top'
})

// Closes the Responsive Menu on Menu Item Click
$('.navbar-collapse ul li a').click(function() {
    $('.navbar-toggle:visible').click();
});
*/

$(window).scroll(function() {
    if ($(document).scrollTop() > 210) {
        $('#a365-header.inicio').removeClass('home').addClass('global');
    }
    if ($(document).scrollTop() < 200) {
        $('#a365-header.inicio').removeClass('global').addClass('home');
    }
});



function successFunction(position) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;
    codeLatLng(lat, lng)
}

function errorFunction(){
    alert("Geocoder failed");
}

function initialize() {
    geocoder = new google.maps.Geocoder();
}

function codeLatLng(lat, lng) {
    var latlng = new google.maps.LatLng(lat, lng);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            console.log(results)
            if (results[1]) {
                for (var i=0; i<results[0].address_components.length; i++) {
                    for (var b=0;b<results[0].address_components[i].types.length;b++) {
                        if (results[0].address_components[i].types[b] == "administrative_area_level_2") {
                            city= results[0].address_components[i];
                            break;
                        }
                    }
                }
                //alert(city.short_name);
                $('.ciudad').val(city.short_name);
            } else {
                //alert("No results found");
            }
        } else {
            //alert("Geocoder failed due to: " + status);
        }
    });
}