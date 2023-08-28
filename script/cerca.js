$(document).ready(function(){


    //funzione che cerca argomento nel db
    $("#argomento").keyup(function(){
    var input = $(this).val();
    $('#sottoargomento1').css('display', 'block');

    if(input != ""){
        $('#sottoargomento1').css('display', 'block');
        $.ajax({
            url:'cerca_argomento.php',
            method:'POST',
            data:{input:input},

            success:function(data){
                $('#suggerimento_a').html(data);
                $('#suggerimento_a').css('display','block');
            }
        });
    } else {
        $('#suggerimento_a').css('display','none');
        $('#sottoargomento1').css('display', 'none');
    }
    
    });

    //funzione che cerca sottoargomento nel db
    $("#sottoargomento").keyup(function(){
        var input = $(this).val();
        if(input != ""){
            $.ajax({
                url:'cerca_sottoargomento.php',
                method:'POST',
                data:{input:input},
    
                success:function(data){
                    $('#suggerimento_s').html(data);
                    $('#suggerimento_s').css('display','block');
                }
            });
        } else {
            $('#suggerimento_s').css('display','none');
        }
        
        });
    //funzione che cerca coautore nel db
    $("#coautore").keyup(function(){
        var input = $(this).val();
        if(input != ""){
            $.ajax({
                
                url:'cerca_co.php',
                method:'POST',
                data:{input:input},

                success:function(data){
                    $('#suggerimento_c').html(data);
                    $('#suggerimento_c').css('display','block');

                }
            });

        } else {
            $('#suggerimento_c').css('display','none');
        }
    });
});

//funzioni che scelgono il risultato nel db selezionato 

function selectCo(val){
    $('#coautore').val(val);
    $('#suggerimento_c').hide();
};

function selectArg(val){
    $('#argomento').val(val);
    $('#suggerimento_a').hide();
    $('#sottoargomento1').css('display','block');
};

function selectSottoArg(val){
    $('#sottoargomento').val(val);
    $('#suggerimento_s').hide();
};

