<div id="MailPreviewDriverBox" style="
    position:absolute;
    top:0;
    z-index:99999;
    background:#fff;
    border:solid 1px #ccc;
    padding: 15px;
">
    An email was just sent! Open sent mail
    <ul>
        <li> <a href="{{ $previewUrl }}&file_type=html">in browser</a> </li>
        <li> <a href="{{ $previewUrl }}&file_type=eml">in email client</a></li>
    </ul>
</div>
<script type="text/javascript">
    setTimeout(function () {
        document.body.removeChild(document.getElementById('MailPreviewDriverBox'));
    }, $timeoutInSeconds * 1000);
</script>
