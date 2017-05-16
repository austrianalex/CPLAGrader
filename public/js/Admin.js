$(function(){
	$(".tabs").tabs();
    $('form input[name*="Test1"], form input[name*="Test2"],form input[name*="TestRetake1"], form input[name*="TestRetake2"]').focus(function(){	
		$(this).addClass("ui-state-active");
		$(this).select();
    }).focusout(function(){
		$(this).removeClass("ui-state-active");
	}).change(function(){
    	var base = "#"+$(this).parents("form").attr("id");
    	var baseElement = $(this);
    	var formAction = $(base).attr("action");
    	var test1 = $(base+"-Test1").val();
    	var test2 = $(base+"-Test2").val();
    	var testRetake1 = $(base+"-TestRetake1").is(":checked") ? 1 : 0;
    	var testRetake2 = $(base+"-TestRetake2").is(":checked") ? 1 : 0;
    	var user_id = $(base+"-UserId").val();
    	var dataString = {'Test1': test1,'Test2':test2,'TestRetake1': testRetake1,'TestRetake2':testRetake2,'UserId':user_id,'ajax':true};
    	$.ajax({
    	      type: "POST",
    	      url: formAction,
    	      data: dataString,
    	      //processData: false,
    	      success: function(msg) {
    	    	  if (msg == "ok")
	    		  {
    	    		  baseElement.removeClass("ui-state-error");
    	    		  baseElement.removeClass("ui-state-highlight");
    	    		  baseElement.addClass("ui-state-ok");
	    		  }
    	    	  else if (msg == "gradewarn")
    	    	  {
    	    		  baseElement.removeClass("ui-state-ok");
    	    		  baseElement.addClass("ui-state-highlight");
    	    	  }
    	    	  else
	    		  {
    	    		  baseElement.addClass("ui-state-error");
    	    		  $("#dialog").text(msg);
    	    		  $("#dialog").dialog({
    	    			 modal: true 
    	    		  });
	    		  }
    	      }
    	});

    });
});