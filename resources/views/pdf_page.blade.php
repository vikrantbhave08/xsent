<!DOCTYPE html>
<!-- <html> -->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>PDF</title>

        <style>

        </style>
    </head>
    <body>  

    <!-- <div style="width: 100%; height: 800px; position: relative;"> 
 

      <iframe src="https://docs.google.com/gview?url={{ $pdf_url }}&embedded=true"   width="100%" height="800px" frameborder="0"></iframe>

      <div style="width: 50px; height: 50px; position: absolute;right: 12px; top: 12px; background:#fff"> </div>
</div> -->

    
    <iframe sandbox="allow-scripts allow-same-origin" 
      id="myDiv" src="https://docs.google.com/gview?url={{ $pdf_url }}&embedded=true" style="width:100%; height:800px;" frameborder="0"></iframe>
   

<script type='text/javascript'>
    setTimeout(function() {
        var elems = document.getElementsByClassName('ndfHFb-c4YZDc-Wrql6b');
        for (var i=0;i<elems.length;i+=1){
            alert();
        elems[i].style.display = 'none';
        }
        // document.getElementsByClassName("ndfHFb-c4YZDc-Wrql6b").style.display = 'none';    
    }, 6000);

</script>
                  

                   
    </body>
</html>
