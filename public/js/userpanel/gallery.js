// filter tab --- sasini

$(document).ready(function(){
 $('.filter').removeClass('hidden');
 $('.filter').show('1000');
 
            $('#img1').removeClass('gray');
            $('#img1').addClass('zoom');
            $('#img2').addClass('gray');
            $('#img3').addClass('gray');
            $('#img4').addClass('gray');
            $('#img5').addClass('gray');
            $('#img6').addClass('gray');
            $('#img7').addClass('gray');
            $('#img8').addClass('gray');
            $('#img9').addClass('gray');
            
            
 return false;
});


 $(".filter-button").click(function(){
     var value = $(this).attr('data-filter');
       
        if(value == "all")
        {
            $('#img1').removeClass('gray');
            $('#img2').removeClass('gray');
            $('#img3').removeClass('gray');
            $('#img4').removeClass('gray');
            $('#img5').removeClass('gray');
            $('#img6').removeClass('gray');
            $('#img7').removeClass('gray');
            $('#img8').removeClass('gray');
            $('#img9').removeClass('gray');
			$('#img1').addClass('zoom');
            $('#img2').addClass('zoom');
            $('#img3').addClass('zoom');
            $('#img4').addClass('zoom');
            $('#img5').addClass('zoom');
            $('#img6').addClass('zoom');
            $('#img7').addClass('zoom');
            $('#img8').addClass('zoom');
            $('#img9').addClass('zoom');
        }
        
        if(value == "plumbing")
        {
            $('#img1').removeClass('gray');
            $('#img2').addClass('gray');
            $('#img3').addClass('gray');
            $('#img4').addClass('gray');
            $('#img5').addClass('gray');
            $('#img6').addClass('gray');
            $('#img7').addClass('gray');
            $('#img8').addClass('gray');
            $('#img9').addClass('gray');
			
			$('#img1').addClass('zoom');
            $('#img2').removeClass('zoom');
            $('#img3').removeClass('zoom');
            $('#img4').removeClass('zoom');
            $('#img5').removeClass('zoom');
            $('#img6').removeClass('zoom');
            $('#img7').removeClass('zoom');
            $('#img8').removeClass('zoom');
            $('#img9').removeClass('zoom');
        }
        
        if(value == "elec")
        {
            $('#img1').addClass('gray');
            $('#img2').removeClass('gray');
            $('#img3').removeClass('gray');
            $('#img4').addClass('gray');
            $('#img5').addClass('gray');
            $('#img6').addClass('gray');
            $('#img7').addClass('gray');
            $('#img8').addClass('gray');
            $('#img9').addClass('gray');
			
			$('#img1').removeClass('zoom');
            $('#img2').addClass('zoom');
            $('#img3').addClass('zoom');
            $('#img4').removeClass('zoom');
            $('#img5').removeClass('zoom');
            $('#img6').removeClass('zoom');
            $('#img7').removeClass('zoom');
            $('#img8').removeClass('zoom');
            $('#img9').removeClass('zoom');
        }
        
        if(value == "paint")
        {
            $('#img1').addClass('gray');
            $('#img2').addClass('gray');
            $('#img3').addClass('gray');
            $('#img4').addClass('gray');
            $('#img5').removeClass('gray');
            $('#img6').removeClass('gray');
            $('#img7').addClass('gray');
            $('#img8').addClass('gray');
            $('#img9').addClass('gray');
			
			$('#img1').removeClass('zoom');
            $('#img2').removeClass('zoom');
            $('#img3').removeClass('zoom');
            $('#img4').removeClass('zoom');
            $('#img5').addClass('zoom');
            $('#img6').addClass('zoom');
            $('#img7').removeClass('zoom');
            $('#img8').removeClass('zoom');
            $('#img9').removeClass('zoom');
        }
        
        if(value == "gard")
        {
            $('#img1').addClass('gray');
            $('#img2').addClass('gray');
            $('#img3').addClass('gray');
            $('#img4').addClass('gray');
            $('#img5').addClass('gray');
            $('#img6').addClass('gray');
            $('#img7').removeClass('gray');
            $('#img8').addClass('gray');
            $('#img9').addClass('gray');
			
			$('#img1').removeClass('zoom');
            $('#img2').removeClass('zoom');
            $('#img3').removeClass('zoom');
            $('#img4').removeClass('zoom');
            $('#img5').removeClass('zoom');
            $('#img6').removeClass('zoom');
            $('#img7').addClass('zoom');
            $('#img8').removeClass('zoom');
            $('#img9').removeClass('zoom');
        }
        
        if(value == "clean")
        {
            $('#img1').addClass('gray');
            $('#img2').addClass('gray');
            $('#img3').addClass('gray');
            $('#img4').addClass('gray');
            $('#img5').addClass('gray');
            $('#img6').addClass('gray');
            $('#img7').addClass('gray');
            $('#img8').removeClass('gray');
            $('#img9').addClass('gray');
			
			$('#img1').removeClass('zoom');
            $('#img2').removeClass('zoom');
            $('#img3').removeClass('zoom');
            $('#img4').removeClass('zoom');
            $('#img5').removeClass('zoom');
            $('#img6').removeClass('zoom');
            $('#img7').removeClass('zoom');
            $('#img8').addClass('zoom');
            $('#img9').removeClass('zoom');
        }
        
        if(value == "maintain")
        {
           $('#img1').addClass('gray');
            $('#img2').addClass('gray');
            $('#img3').addClass('gray');
            $('#img4').addClass('gray');
            $('#img5').addClass('gray');
            $('#img6').addClass('gray');
            $('#img7').addClass('gray');
            $('#img8').addClass('gray');
            $('#img9').removeClass('gray');
			
			$('#img1').removeClass('zoom');
            $('#img2').removeClass('zoom');
            $('#img3').removeClass('zoom');
            $('#img4').removeClass('zoom');
            $('#img5').removeClass('zoom');
            $('#img6').removeClass('zoom');
            $('#img7').removeClass('zoom');
            $('#img8').removeClass('zoom');
            $('#img9').addClass('zoom');
        }
       
        
        

            if ($(".filter-button").removeClass("active")) {
        $(this).removeClass("active");
        }
        $(this).addClass("active");
        
        
        
        
        
        
        
    });