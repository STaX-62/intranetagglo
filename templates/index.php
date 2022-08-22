<?php
// script('intranetagglo', 'intranetagglo');
// style('intranetagglo', 'intranetagglo');
?>

<iframe></iframe>

<script type="text/javascript">
    var html = 'Hello from <img src="http://stackoverflow.com/favicon.ico" alt="SO">';
    var iframe = document.querySelector('iframe');
    iframe.src = 'data:text/html,' + encodeURIComponent(html);
</script>