if(window.innerWidth<601)
{
	//javscript for mobile devices
}
if(window.innerWidth>600 && window.innerWidth<993)
{
	//javscript for tablet devices
}
if(window.innerWidth>992)
{
	/*javscript for PC and other large devices*/
}
jQuery(function($) {

  // MAD-RIPPLE // (jQ+CSS)
  $(document).on("mousedown", "[data-ripple]", function(e) {

    var $self = $(this);

    if($self.is(".w3-disabled")) {
      return;
    }
    if($self.closest("[data-ripple]")) {
      e.stopPropagation();
    }

    var initPos = $self.css("position"),
        offs = $self.offset(),
        x = e.pageX - offs.left,
        y = e.pageY - offs.top,
        dia = Math.min(this.offsetHeight, this.offsetWidth, 100), // start diameter
        $ripple = $('<div/>', {class : "ripple",appendTo : $self });

    if(!initPos || initPos==="static") {
      $self.css({position:"relative"});
    }

    $('<div/>', {
      class : "rippleWave",
      css : {
        background: $self.data("ripple"),
        width: dia,
        height: dia,
        left: x - (dia/2),
        top: y - (dia/2),
      },
      appendTo : $ripple,
      one : {
        animationend : function(){
          $ripple.remove();
        }
      }
    });
  });

});


function toast(content, timeOut) {
	$('.toast').clone().html(content).appendTo('body').fadeIn().delay(timeOut).fadeOut(function () {
				this.remove();
			});
}