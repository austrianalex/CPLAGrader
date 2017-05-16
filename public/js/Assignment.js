$(function(){
	$('.button').button();
	$('.commentButton').click(function(){
		$(this).hide();
		var base = "#"+$(this).parents("form").attr("id");
		$(base+"-Comment").parent().parent().show();
		$(base+"-Comment").tinymce({
			script_url : baseurl+'/js/tiny_mce/tiny_mce.js',
	        editor_selector : "wysiwyg",
	        theme : "advanced",
	        plugins: "save",
	        setup : function(ed)
	        {
	        	ed.onSaveContent.add(function(ed, o){
	            	var formAction = $(base).attr("action");
	            	var grade_id = $(base+"-Id").val();
	            	var assignment_id = $(base+"-AssignmentId").val();
	            	var user_id = $(base+"-UserId").val();
	            	var score = $(base+"-Score").val();
	            	var comment = $(base+"-Comment").val();
	            	var dataString = {'Id': grade_id,'AssignmentId':assignment_id,'UserId':user_id,'Comment':comment,'ajax':true};
	            	$.ajax({
	            	      type: "POST",
	            	      url: formAction,
	            	      data: dataString
	            	      /*success: function(msg){
	            	    	  //$("#dialog").text("Comment saved");
	            	    	  //$("#dialog").dialog({
	            	    		//  modal: false
	            	    	  //});
	            	      }*/
	            	      //processData: false,
	            	});
	        	});
	        },
	        theme_advanced_toolbar_location: "top",
	        theme_advanced_toolbar_align: "left",
	        theme_advanced_buttons1 : "save,|,bold,italic,underline,strikethrough,|,bullist,numlist,|,link,unlink,image,code",
	        theme_advanced_buttons2         : "",
	        theme_advanced_buttons3         : ""
		});
		
		//$("#dialog").dialog({
		//	modal: false
		//});
	});
	$('textarea.tinymce').tinymce({
        script_url : baseurl+'/js/tiny_mce/tiny_mce.js',
        editor_selector : "wysiwyg",
        theme : "advanced",
        plugins: "save",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_buttons1 : "save,|,bold,italic,underline,strikethrough,|,bullist,numlist,|,link,unlink,image,code",
        theme_advanced_buttons2         : "",
        theme_advanced_buttons3         : ""
	});
	$(".submitLink").button().click(function(){
		document.location.href = $(this).find("a").attr("href");
	});
	$("#createEntry").click(function(){
		alert($(this).find("a").attr("href")+'/user/'+$('select#userEntries option:selected').val());
		document.location.href = $(this).find("a").attr("href")+'/user/'+$('select#userEntries option:selected').val();
	});
	$( "#assignments" ).accordion({
		active: false,
		collapsible: true/*,
		animated:false*/
	});
	
	$('#Due').datetime({
        userLang: 'en',
        americanMode: true
    });
    $('form.nosubmit').submit(function(e){
    	e.preventDefault();
    });
    $('.html_url').click(function(){
    	$(this).select();
    	window.open($(this).val());
    });
    $('.td-score input, .td-comment textarea').focus(function(){
		$(this).addClass("ui-state-active");
		$(this).select();
	}).focusout(function(){
		$(this).removeClass("ui-state-active");
	}).change(function(){
    	var base = "#"+$(this).parents("form").attr("id");
    	var baseElement = $(this);
    	var formAction = $(base).attr("action");
    	var grade_id = $(base+"-Id").val();
    	var assignment_id = $(base+"-AssignmentId").val();
    	var user_id = $(base+"-UserId").val();
    	var score = $(base+"-Score").val();
    	var comment = $(base+"-Comment").val();
    	var dataString = {'Id': grade_id,'AssignmentId':assignment_id,'UserId':user_id,'Score':score,'ajax':true};
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
    	    		  baseElement.removeClass("ui-state-ok");
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