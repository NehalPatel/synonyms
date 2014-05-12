<html>
<head>
    <title>bighugelabs.com synonyms api demo</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>

<body>

<p>List of Keywords</p>

<form>
    <textarea id="input" name="input" rows="15" cols="140">yoga
shiatsu</textarea>
    <br />
    <input type="button" name="getdata" id="getdata" value="Get relevant keywords" />
    <div class="loader" style="display: none"><img src="ajax-loader.gif">&nbsp;Please wait...</div>
</form>

<script type="text/javascript">

    function get_related_keywords_server(keywords){
        $(".loader").show();
        $.ajax({
            url : "bighugelabs_handler.php",
            dataType : "json",
            method : 'post',
            timeout : 600000,   //10 min max
            data : {query : keywords, 'format' : 'file'},
            success : function(response){
                if(typeof response != "undefined"){
                    if(response.status == true){
                        document.location.href = 'download.php?filename='+response.file;
                    } else {
                        console.log(response.error_message);
                    }
                } else {
                    alert('ajax failed');
                }
            },
            complete : function(xhr, status){
                $(".loader").hide();
            }
        });
    }

    $(document).on('click',"#getdata",function(){
        var str = $("#input").val();
        res = str.split("\n");
        get_related_keywords_server(res);
    });
</script>

<pre>
    <div id="append_div_all">
    </div>
</pre>

<div id="append_div">
</div>

</body>
</html>