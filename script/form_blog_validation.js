
$(document).ready(function(){

    $('#submit_blog').click(function(){
    
    var valid = true;
    var expr1 = /^[A-zÀ-ÖØ-öø-ÿ]{5,30}$/;
    var expr2 = /^[A-zÀ-ÖØ-öø-ÿ]{3,27}$/;
    
    if ($("#nome_b").val().length < 5){
        $("#span1").html("Inserisci almeno 5 caratteri!");
        $("#nome_b").val("");
        $("#nome_b").addClass("error");
        valid = false;
        
    } else if(!expr1.test($("#nome_b").val())){
        $("#span1").html("Nome non può contenere spazi, numeri o caratteri speciali!");
        $("#nome_b").val("");
        $("#nome_b").addClass("error");
        valid = false;
    
    } else {
        var nome = $("#nome_b").val();
    }
    
    if(nome != ""){
        $.ajax({
            url: 'validazione_blog.php',
            method: 'POST',
            data:{input: nome},
    
            success:function(response){
                if(response == 2){
                    $("#span1").html("");
                    $("#nome_b").removeClass("error");
                } else if (response == 1){
                    $("#span1").html("Esiste già un blog con questo nome!");
                    $("#nome_b").addClass("error");
                    valid = false;
                }
            }
        });
    };
    
    
    if ($("#argomento").val().length < 3){
        $("#span2").html("Inserisci almeno 3 caratteri!");
        $("#argomento").val("");
        $("#argomento").addClass("error");
        valid = false;
    } else if(!expr2.test($("#argomento").val())){
        $("#span2").html("Argomento non può contenere spazi, numeri o caratteri speciali!");
        $("#argomento").val("");
        $("#argomento").addClass("error");
        valid = false;
    } else {
        $("#span2").html("");
        $("#argomento").removeClass("error");
    }
    
    
    if ($("#sottoargomento").val().length != 0){
        if($("#sottoargomento").val().length < 3 ){
        $("#span3").html("Inserisci almeno 3 caratteri!");
        $("#sottoargomento").val("");
        $("#sottoargomento").addClass("error");
        valid = false;
        } else if(!expr2.test($("#sottoargomento").val())){
            $("#span3").html("Argomento non può contenere spazi, numeri o caratteri speciali!");
            $("#sottoargomento").val("");
            $("#sottoargomento").addClass("error");
            valid = false;
        } else {
            $("#span3").html("");
            $("#sottoargomento").removeClass("error");
        }
    }
    
    
    
    return valid;
    
    });
    });