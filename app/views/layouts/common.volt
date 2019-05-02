<html>
    <head>
        {% if titlePagina is defined %}
            <title>{{ t._('' ~ titlePagina ~ '') }} - An example blog</title>
        {% endif %}
        {{ assets.outputCss('localCss') }}
        <link rel="alternate" href="{{ dominioPhp }}/{{ lang }}" hreflang="{{ language }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {% if descriptionMeta is defined %}
            <meta name="description" content="{{ t._('' ~ descriptionMeta ~ '') }}">
        {% endif %}
    </head>
    <body>
        <div class="container-medium">
            {% include 'partials/header.volt' %}
            {{ content() }}
            {% include 'partials/footer.volt' %}
        </div>
    </body>
    {{ assets.outputJs() }}
</html>