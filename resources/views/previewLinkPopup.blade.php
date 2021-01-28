<div id="MailPreviewDriverBox" style="
    position:absolute;
    top:0;
    z-index:99999;
    background:#fff;
    border:solid 1px #ccc;
    padding: 15px;
    ">
    An email was just sent: <a href="{{ $previewUrl }}">Preview sent mail</a>
</div>
<script type="text/javascript">
    setTimeout(function () {
        document.body.removeChild(document.getElementById('MailPreviewDriverBox'));
    }, $timeoutInSeconds * 1000);
</script>
