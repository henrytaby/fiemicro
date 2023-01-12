<html>
<head>
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>

    <link rel="stylesheet" href="<?php echo $this->config->base_url(); ?>html_public/css/leaflet.css" />	

    <script src="<?php echo $this->config->base_url(); ?>html_public/js/leaflet.js"></script>

</head>
<body>

    <?php echo $map['html']; ?>
    <?php echo $map['js']; ?>

</body>
</html>