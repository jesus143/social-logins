$(document).ready(function() {
	
	/* off-canvas sidebar toggle */
	$('[data-toggle=offcanvas]').click(function() {
	  	$(this).toggleClass('visible-xs text-center');
	    $(this).find('i').toggleClass('glyphicon-chevron-right glyphicon-chevron-left');
	    $('.row-offcanvas').toggleClass('active');
	    $('#lg-menu').toggleClass('hidden-xs').toggleClass('visible-xs');
	    $('#xs-menu').toggleClass('visible-xs').toggleClass('hidden-xs');
	    $('#btnShow').toggle();
	});

	$('#logo-icon').hover(
	    function(event) {
	    	$(this).addClass('fa-paper-plane').removeClass('fa-paper-plane-o');
	    }, function(event) {
	    	$(this).addClass('fa-paper-plane-o').removeClass('fa-paper-plane');
	    }
	);
	
	$('.triggerTabFederated').click(function(event) {
		event.preventDefault();
		$('#triggerTabFederated').trigger('click');
	});
	
	$('.triggerTabLocal').click(function(event) {
		event.preventDefault();
		$('#triggerTabLocal').trigger('click');
	});	
	
	$('.zocial').click(function(event) {

		// alert("clicked social" + BASEURL);
		event.preventDefault();

		var data	= $(this).data();
		var network = data.network;

		if( typeof network === 'undefined' ) {
			return false;
		}

		switch( network ) {
			default:
				$.blockUI({
					'message': '<img border="0" src="'+ BASEURL + '/images/preloader/tools.gif">'
				});

			window.location.assign( BASEURL + '/login/' + network );
		}
	});
	
	$('.blockUI-trigger').click(function(event) {
		$.blockUI();
	});

});

function percentOfDocumentHeight( percent )
{
	return ( ( $(document).height() / 100 ) * percent );	
}

function percentOfDocumentWidth( percent )
{
	return ( ( $(document).width() / 100 ) * percent );	
}

function percentOfWindowHeight( percent )
{
	return ( ( $(window).height() / 100 ) * percent );	
}

function percentOfWindowWidth( percent )
{
	return ( ( $(window).width() / 100 ) * percent );	
}

Array.prototype.remove = function(value, count) {
    if (this.indexOf(value)!==-1) {
    	if( typeof count === 'undefined' ) {
    		this.splice( this.indexOf(value) );	
    	} else {
    		this.splice( this.indexOf(value), count );    		
    	}

       return true;
   } else {
      return false;
   };
} 

if(!Array.indexOf) { 
    Array.prototype.indexOf = function(obj){
        for(var i=0; i < this.length; i++){
            if(this[i]==obj){
                return i;
            }
        }
        return -1;
    };
}