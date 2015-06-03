$(document).ready(function(){
$(function(){ 
var w = $("#headercate").width();
$("#headercate img").each(function(){
var img_w = $(this).width();
var img_h = $(this).height();
if(img_w>w){
var height = (w*img_h)/img_w; 
$(this).css({"width":w,"height":height});
} 
}); 
});})




$(document).ready(function() {
	var mySwiper = new Swiper('.swiper-container', {
		pagination: '.pagination',
		paginationClickable: true
	})

})
