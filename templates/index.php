

<iframe></iframe>

<script type="text/javascript">
    var html = "<?php script('intranetagglo', 'intranetagglo'); style('intranetagglo', 'intranetagglo'); ?>";
    var iframe = document.querySelector('iframe');
    iframe.src = 'data:text/html,' + encodeURIComponent(html);
</script>