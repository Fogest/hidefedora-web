$(function() {
    var title = $("#pageTitle").text();
    $("ul.nav li a").each(function(){
    	if($(this).text() == title) {
    		$(this).parent().addClass("active");
            return false;
    	}
    });
    $("#login").on("shown",function(){
        $("#loginBtn").focus();
    });
    $("#register").on("shown",function(){
        $("#registerBtn").focus();
    });
    $("#submit").click(function(){
        $.post("html/submit/submit.php",
            {submit:1,
                profileUrl:$("#profileUrl").val(),
                comment: null,
                youtubeUrl: 'Manual'},
            function(result){
            $("#status").html("<div class='alert alert-info'><button type='button' class='close' data-dismiss='alert'> " + 
                "<i class='icon-remove'></i></button><strong>"+ result +"</strong></div>").fadeIn().delay(2000).fadeOut("slow");
        });  
    });
    $("button.approve").click(function(){
        var id = $(this).attr("profileid");
        var button = $(this);
        $.post("ajax.php",{status:1,id:id},function(result){
            button.closest("tr").removeClass("error").addClass("success");
        });                            
    });
    $("button.reject").click(function(){
        var id = $(this).attr("profileid");
        var button = $(this);
        $.post("ajax.php",{status:-1,id:id},function(result){
            button.closest("tr").removeClass("success").addClass("error");
        });    
    });
    $("#approveAll").click(function(){
        $("table.review").find("tbody tr:not(.success,.error)").find(".approve").click();
    });
    $("#rejectAll").click(function(){
        $("table.review").find("tbody tr:not(.success,.error)").find(".reject").click();
    });

    $("#submit-unban").click(function(){
        $.post("../review/ajax.php",
            {   status:-1,
                id:$("#profileId").val()
            },
            function(result){
            $("#status").html("<div class='alert alert-info'><button type='button' class='close' data-dismiss='alert'> " + 
                "<i class='icon-remove'></i></button><strong>"+ result +"</strong></div>").fadeIn().delay(2000).fadeOut("slow");
        });  
    });
    $("button.unban").click(function(){
        var id = $(this).closest("tr").find("td:first a:first").text();
        var button = $(this);
        $.post("../review/ajax.php",{status:0,id:id,unban:1},function(result){
            button.closest("tr").addClass("success");
        });                            
    });
    $("button.unbanDecline").click(function(){
        var id = $(this).closest("tr").find("td:first a:first").text();
        var button = $(this);
        $.post("../review/ajax.php",{status:1,id:id,unban:1},function(result){
            button.closest("tr").addClass("error");
        });                            
    });
});