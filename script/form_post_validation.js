
$(document).ready(function(){

    $('#submit_post').click(function(){
    
    var valid = true;

    var reg1 = /^([A-zÀ-ú]{1,})(\s?\'?[A-zÀ-ú])*$/;

    if ($('#titolo_post').val().length < 3){
        $("#span4").html("Inserisci almeno 3 caratteri!");
        $("#titolo_post").val("");
        $("#titolo_post").addClass("error");
        valid = false;
    
    } else if(!reg1.test($("#titolo_post").val())){
        $("#span4").html("Nome non può contenere numeri o caratteri speciali!");
        $("#titolo_post").val("");
        $("#titolo_post").addClass("error");
        valid = false;
    
    } else {
        $("#span4").html("");
        $("#titolo_post").removeClass("error");
    }


    if ($('#testo_post').val().length < 100){
        $("#span6").html("Inserisci almeno 100 caratteri!");
        $("#testo_post").val("");
        $("#testo_post").addClass("error");
        valid = false;
    } else {
        $("#span6").html("");
        $("#testo_post").removeClass("error");

    }

    return valid;

}); 
});