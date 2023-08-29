<div class="flex">
    <div>QRCode : </div>
    <div id="qrcode" style="width: 100px; height: 100px; margin-top: 15px; margin-left: 50px"></div>
</div>

<script src="{{asset('qrcode.js')}}"></script>
<script type="text/javascript">
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        width: 100,
        height: 100,
    });

    function makeCode(){
        var elText = 123    ;

        qrcode.makeCode(elText);
    }
    makeCode();
</script>
