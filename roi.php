<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<style type="text/css">
body{
    font-weight:bold;
    font-family:arial;
}

table{
    font-weight:bold;
    font-family:arial;
    text-transform:uppercase;
    font-size:10px;
}

td{
    font-weight:bold;
    border:1px solid black;
    padding:5px 5px 5px 5px;
}
input[type=text]{
    width:100%;
}
</style>

<table>
<tr>
    <td colspan="9" style="font-size: 20px;font-weight:bold; text-align:left;" >
ROI/ Net Profitability Caculator For iWOR @ 150gal Per Month
</td></tr>
<tr><td colspan="9" style="background:gray;height:10px;">&nbsp;</td></tr>
<tr>
    <td  style="font-size: 15px;font-weight:bold; text-align:left;" >Price per net lb </td>
    <td>&nbsp;</td><td>&nbsp;</td>
    <td>Est Gal</td>
    <td>Est Lbs</td>
    <td>% Shrink</td>
    <td> lbs after shrink</td>
    <td>Monthly gross</td>
    <td>Annual gross</td>
</tr>



<tr>
    <td>Gate Price</td>
    <td><input type="text" id="b4"  style="background: rgb(128,195,84);"/></td>
    <td>&nbsp;</td>
    <td><input type="text" id="d4" style="background: rgb(128,195,84);"/></td>
    <td><input type="text" readonly="" id="e4"/></td>
    <td><input type="text" id="f4" style="background: rgb(128,195,84);"/></td>
    <td><input id="g4" type="text" /></td>
    <td><input id="h4" type="text" style="background:rgb(200,140,140);" readonly=""/></td>
    <td><input type="text" id="i4"  style="background:rgb(200,140,140);" readonly=""/></td>
</tr>

<tr>
    <td>Freight</td>
    <td><input type="text" id="b5"  style="background: rgb(128,195,84);"/></td>
    <td>&nbsp;</td>
    <td>Price Per Gal.</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>Gal. After Shrink</td>
    <td>&nbsp;</td><td>&nbsp;</td>
</tr>

<tr>
    <td>Op cost</td>
    <td><input type="text" id="b6"  style="background: rgb(128,195,84);"/></td>
    <td>&nbsp;</td><td><input type="text" id="d6"  style="background: rgb(128,195,84);"/></td>
    <td>&nbsp;</td><td>&nbsp;</td>
    <td><input type="text" style="background:rgb(200,140,140);" id="g6" readonly=""/></td>
    <td>&nbsp;</td><td>&nbsp;</td>
</tr>

<tr><td>Net profit per lb</td><td><input type="text" id="b7" readonly="" style="background:rgb(200,140,140);" readonly=""/></td><td>Procurement Cost</td><td>One Time Pay</td><td>Container</td><td>Setup</td><td>Annual Payment
</td><td>Monthly Payment
</td><td>Total cost
</td></tr>

<tr><td>&nbsp;</td><td>&nbsp;</td><td><input type="text" id="c8"  style="background: rgb(128,195,84);"/></td>&nbsp;<td><input type="text" id="d8"  style="background: rgb(128,195,84);"/></td><td><input type="text" id="e8" style="background: rgb(128,195,84);"/></td><td><input type="text" id="f8" style="background: rgb(128,195,84);"/></td><td><input type="text" id="g8" style="background:rgb(200,140,140);" readonly=""/></td><td><input type="text" id="h8" style="background:rgb(200,140,140);" readonly=""/></td><td><input type="text" id="i8" style="background:rgb(200,140,140);" readonly=""/></td></tr>

<tr>
    <td>Monthly ROI full container cst</td>
    <td><input type="text" id="b9" style="background: #C3C254;" readonly=""/></td>
    <td>Monthly ROI half container cst</td>
    <td><input type="text" id="d9"  style="background:#C3C254;" readonly=""/></td>
    <td>Annual Net</td>
    <td><input type="text"  id="f9" style="background: #C3C254;"/></td>
    <td><input type="text" style="background: rgb(128,195,84);width:50px;" id="g9" value="" /> year net </td>
    <td><input type="text" id="h9" style="background: #C3C254;"/></td>
    <td><input title="Please make sure 'year net' is filled out"  id="calculate" type="submit" value="Calculate"/></td>
</tr>

</table>


<script>

$("#h9").blur(function(){
    var hk =  ( ($("#i4").val() *5) - ( ( $("#i8").val() *1 ) + (  $("#g8").val() *1 ) ) );
    $("#h9").val(hk.toFixed(2));
}).change(function(){
    var hk =  ( ($("#i4").val() *5) - ( ( $("#i8").val() *1 ) + (  $("#g8").val() *1 ) ) );
    $("#h9").val(hk.toFixed(2));
});

$("#d9").blur(function(){
    
    var ty =  ($("#d8").val()*1) +  ($("#f8").val()*1) + ($("#g8").val()*1)   +  ($("#c8").val() *1) 
     var yt = ( ($("#e8").val()/2) + ty )/ $("#h4").val()  ; 
    $("#d9").val(  yt.toFixed(2)  );
}).change(function(){
    var ty =  ($("#d8").val()*1) +  ($("#f8").val()*1) + ($("#g8").val()*1)   +  ($("#c8").val() *1);
    var yt = ( ($("#e8").val()/2) + ty )/ $("#h4").val()  ; 
    $("#d9").val(  yt.toFixed(2)  );
});

$("#f9").blur(function(){
    var ky =  ( $("#i4").val() *1 ) - ( $("#i8").val() *1 );
    $("#f9").val( ky.toFixed(2)  );
})

$("#d8,#e8,#f8,#g8,#c8").change(function(){
    var t = ($("#c8").val() *1) +  ($("#d8").val()*1) +  ($("#e8").val()*1 ) +  ($("#f8").val()*1)  + ($("#g8").val()*1);     
    $("#i8").val( t.toFixed(2)  );
}).blur(function(){
    var t = ($("#c8").val() *1) +  ($("#d8").val()*1) +  ($("#e8").val()*1 ) +  ($("#f8").val()*1)  + ($("#g8").val()*1);     
    $("#i8").val( t.toFixed(2)  );
});

$("#i8").change(function(){
    var t = ($("#c8").val() *1) +  ($("#d8").val()*1) +  ($("#e8").val()*1 ) +  ($("#f8").val()*1)  + ($("#g8").val()*1);     
    $("#i8").val( t.toFixed(2)  );
}).blur(function(){
    var t = ($("#c8").val() *1) +  ($("#d8").val()*1) +  ($("#e8").val()*1 ) +  ($("#f8").val()*1)  + ($("#g8").val()*1);     
    $("#i8").val( t.toFixed(2)  );
});

$("#f4").change(function(){
    
    // alert(       );
    var vk =  (1-  ($("#f4").val()/100) )  * ( $("#e4").val() *1 );
   $("#g4").val( vk.toFixed(2)  );
}).blur(function(){
    var vk =  (1-  ($("#f4").val()/100) )  * ( $("#e4").val() *1 );
   $("#g4").val( vk.toFixed(2)  );
});

$("#g4").change(function(){
    var nbv = $("#g4").val()/7.56;
    $("#g6").val( nbv.toFixed(2) );    
}).blur(function(){
      var nbv = $("#g4").val()/7.56;
    $("#g6").val( nbv.toFixed(2) );
});


$("#g6").blur(function(){
    var qwx =$("#g4").val()/7.56;
    $("#g6").val( qwx.toFixed(2) );
}).change(function(){
    var qwx =$("#g4").val()/7.56;
    $("#g6").val( qwx.toFixed(2) );
});

$("#g8").blur(function(){
   var op =  $("#h8").val() * 12;
   $("#g8").val(op.toFixed(2)); 
}).change(function(){
    var op =  $("#h8").val() * 12;
   $("#g8").val(op.toFixed(2));
});

$("#g4,#b7").blur(function(){
     var jhw =  $("#g4").val() * $("#b7").val();
    $("#h4").val( jhw.toFixed(2) );
}).change(function(){
    var jhw =  $("#g4").val() * $("#b7").val();
    $("#h4").val( jhw.toFixed(2) );
});


$("#i8,#h4").change(function(){
    if( $("#h4").val() !=""  ){
          var uyt = $("#i8").val() / $("#h4").val() ;
        $("#b9").val( uyt.toFixed(2)   );
    }    
}).blur(function(){
    if( $("#h4").val() !=""  ){
        var uyt = $("#i8").val() / $("#h4").val() ;
        $("#b9").val( uyt.toFixed(2)  );
    }
});


$("#h8").blur(function(){
    var iu = $("#g6").val() * $("#d6").val();
   $("#h8").val( iu.toFixed(2) );
}).change(function(){
    var iu = $("#g6").val() * $("#d6").val();
   $("#h8").val( iu.toFixed(2) );
});

$("#b4").change(function(){
    if($("#b5").val() !="" || $("#b6").val() !=""){
        var hgfdd =($("#b4").val() - $("#b5").val()) - $("#b6").val()  ;
        $("#b7").val( hgfdd.toFixed(2) );
    } 
}).blur(function(){
    if($("#b5").val() !="" || $("#b6").val() !=""){
        var hgfdd =( $("#b4").val() - $("#b5").val()) - $("#b6").val()  ;
        $("#b7").val( hgfdd.toFixed(2) );
    }
});

$("#i8,#h4").change(function(){
    var mo = $("#i8").val() / $("#h4").val();
    $("#b9").val( mo.toFixed(2)  );    
}).blur(function(){
    var mo = $("#i8").val() / $("#h4").val();
    $("#b9").val( mo.toFixed(2)  );
})



$("#b5").change(function(){
    if($("#b4").val() !="" || $("#b6").val() !=""){
        $("#b7").val( ($("#b4").val() - $("#b5").val()) - $("#b6").val()  );
    }
}).blur(function(){
    if($("#b4").val() !="" || $("#b6").val() !=""){
        $("#b7").val( ($("#b4").val() - $("#b5").val()) - $("#b6").val()  );
    }
});

$("#d4").change(function(){
   $("#e4").val($("#d4").val() * 7.56); 
}).blur(function(){
    $("#e4").val($("#d4").val() * 7.56);
});

//$("#e4").val( $("#d4").val() * 7.56  );

$("#b6").change(function(){
    if($("#b4").val() !="" || $("#b5").val() !=""){
        var nio = ($("#b4").val() - $("#b5").val()) - $("#b6").val() ;
        
        $("#b7").val( nio.toFixed(2) );
    }
}).blur(function(){
      
    if($("#b4").val() !="" || $("#b5").val() !=""){
        var nio = ($("#b4").val() - $("#b5").val()) - $("#b6").val() ;
        $("#b7").val( nio.toFixed(2) );
    }
});

$("#i4").blur(function(){
    $(this).val( $("#h4").val() *12 ); 
}).change(function(){
    $(this).val( $("#h4").val() *12 );
});

$("#calculate").click(function(){
  
    
    if( $("input#g9").val().length  !== 0  ){
              
        var t = ($("#c8").val() *1) +  ($("#d8").val()*1) +  ($("#e8").val()*1 ) +  ($("#f8").val()*1)  + ($("#g8").val()*1);     
        $("#i8").val( t.toFixed(2)  );
        
        var vk =  (1-  ($("#f4").val()/100) )  * ( $("#e4").val() *1 );
        $("#g4").val( vk.toFixed(2)  );
        
        
        var bty =$("#g4").val()/7.56;
        $("#g6").val( bty.toFixed(2)   );  
      
        var asdz =( $("#g4").val() * $("#b7").val() );
        $("#h4").val(   asdz.toFixed(2)    );
        
        
        var ncx = $("#i8").val() / $("#h4").val();
        $("#b9").val( ncx.toFixed(2)   );
         
        var iu = $("#g6").val() * $("#d6").val();
        $("#h8").val( iu.toFixed(2) );
        
        var mnb = ($("#b4").val() - $("#b5").val()) - $("#b6").val();
        $("#b7").val( mnb.toFixed(2)  );
      
        
        var opi = $("#d4").val() * 7.56;
        $("#e4").val( opi.toFixed(2));
          
        var jk = $("#h4").val() *12; 
        $("#i4").val( jk.toFixed(2) ); 
           
        var op =  $("#h8").val() * 12;
        $("#g8").val(op.toFixed(2)); 
        
        var ty =  ($("#d8").val()*1) +  ($("#f8").val()*1) + ($("#g8").val()*1)   +  ($("#c8").val() *1) 
        var yt = ( ($("#e8").val()/2) + ty )/ $("#h4").val()  ; 
        $("#d9").val(  yt.toFixed(2)  );
        
        var mo = $("#i8").val() / $("#h4").val();
        $("#b9").val( mo.toFixed(2)  );  
        
        var ky =  ( $("#i4").val() *1 ) - ( $("#i8").val() *1 );
        $("#f9").val( ky.toFixed(2)  ); 
        
        var hk =  ( ($("#i4").val() * $("#g9").val() ) - ( ( $("#i8").val() *1 ) + (  $("#g8").val() *1 ) ) );
        $("#h9").val(hk.toFixed(2));
        
    } else {
        alert('Please make sure "year net" is filled out');
    }
    
});
</script>