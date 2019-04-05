<html>
    <head>
        <title>{{ t._('titulo-pagina') }} - An example blog</title>
        {{ assets.outputCss('localCss') }}
        <link rel="alternate" href="<?php echo DOMINIO . '/' . $lang; ?>" hreflang="<?php echo $language; ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div class="container-medium">
            {% include 'partials/header.volt' %}
            <div class="row">
                <div class="col-sm-6 bg-gray"><br><br></div>
                <div class="col-sm-6 bg-gray-light"><br><br></div>
            </div>
            {{ content() }}
            {% include 'partials/footer.volt' %}
        </div>
    </body>
</html>