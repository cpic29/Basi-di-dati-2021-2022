
$(document).ready(function(){
    $('#submit').click(function(){
  
      var valid = true;

      var us =  /^[a-zA-Z]+[a-zA-Z0-9]*$/;

      if ($("#username").val().length < 3){
        $("#username").addClass("error");
        $("#span1").html("Inserisci almeno 4 caratteri");
        $("#username").val("")
        valid = false;
      } else if (!us.test($("#username").val())){
        $("#username").addClass("error");
        $("#span1").html("Inserisci lettere e numeri!");
        $("#username").val("")
      } else {
        var us = $("#username").val();;
        var cosa = 'username';
        
        if(username != ""){
        $.ajax({
          url: 'validazione_registrazione.php',
          method: 'POST',
          data:{input1: us, input2: cosa},
  
          success:function(response){
            if(response == 2){
              $("#span1").html("");
              $("#username").removeClass("error");
              } else if (response == 1){
                $("#span1").html("Utente già esistente!");
                $("#username").addClass("error");
                valid = false;
              }
            }
          })
        };
      };
  
      var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
  
      if ($('#email').val() == ""){
        $("#email").addClass("error");
        $("#span2").html("Inserisci un indirizzo email");
        $("#email").val("");
        valid = false;
  
      } else if(!expr.test($('#email').val())){
        $("#email").addClass("error");
        $("#span2").html("Indirizzo email non valido");
        $("#email").val("");
        valid = false;
  
      } else {
        var email = $('#email').val();
        var cosa = 'email';
        $.ajax({
          url: 'validazione_registrazione.php',
          method: 'POST',
          data:{input1: email, input2: cosa},
  
          success:function(response){
            if(response == 2){
              $("#email").removeClass("error");
              $('#span2').html("");
              } else if (response == 1){
                $("#span2").html("Email già esistente!");
                $("#email").addClass("error");
                valid = false;
              }
          }
        })
      };

      var mob = /^3\d{9}$/;
      var fis = /^0\d{9}$/;

      if($("#telefono").val() == ""){
        $("#telefono").addClass("error");
        $("#span5").html("Inserisci un numero di telefono");
        $("#telefono").val("");
        valid = false;
      } else if ((!mob.test($('#telefono').val())) && (!fis.test($('#telefono').val()))){
        $("#telefono").addClass("error");
        $("#span5").html("Telefono non valido");
        valid = false;
      } else {
        $("#telefono").removeClass("error");
        $('#span5').html("");
      }

      var cod = /^(?:[A-Z][AEIOU][AEIOUX]|[AEIOU]X{2}|[B-DF-HJ-NP-TV-Z]{2}[A-Z]){2}(?:[\dLMNP-V]{2}(?:[A-EHLMPR-T](?:[04LQ][1-9MNP-V]|[15MR][\dLMNP-V]|[26NS][0-8LMNP-U])|[DHPS][37PT][0L]|[ACELMRT][37PT][01LM]|[AC-EHLMPR-T][26NS][9V])|(?:[02468LNQSU][048LQU]|[13579MPRTV][26NS])B[26NS][9V])(?:[A-MZ][1-9MNP-V][\dLMNP-V]{2}|[A-M][0L](?:[1-9MNP-V][\dLMNP-V]|[0L][1-9MNP-V]))[A-Z]$/;

      if($("#codiceFiscale").val() == ""){
        $("#codiceFiscale").addClass("error");
        $("#span6").html("Inserisci il codice fiscale");
        $("#codiceFiscale").val("");
        valid = false;
      } else if ((!cod.test($('#codiceFiscale').val()))){
        $("#codiceFiscale").addClass("error");
        $("#span6").html("Documento non valido");
        valid = false;
      } else {
        $("#codiceFiscale").removeClass("error");
        $('#span6').html("");
      }
  
      if ($("#password").val().length < 8){
        $("#password").addClass("error");
        $("#span3").html("Inserisci almeno 8 caratteri");
        $("#password").val("");
        valid = false;
      } else {
        $("#password").removeClass("error");
        $('#span3').html("");
      } 
  
      if ($("#password2").val().length < 1) {
        $("#password2").addClass("error");
        $("#span4").html("Ripeti la password");
         valid = false;
      }else if($("#password").val() != $("#password2").val()){
        $("#password").addClass("error");
        $("#password2").addClass("error");
        $("#span4").html("Le password non coincidono");
        $("#password2").val("");
        valid = false;
      } else {
        $("#password").removeClass("error");
        $("#password2").removeClass("error");
        $("#span3").html("");
        $("#span4").html("");
      }
      
      return valid;
  
    });
  });
  