<html>
    <head>
        {% if titlePagina is defined %}
            <title>{{ t._('' ~ titlePagina ~ '') }}</title>
        {% endif %}
        {% if jsonld is defined %}
            <script type="application/ld+json">
                {{ jsonld }}
            </script>
        {% endif %}
        {{ assets.outputCss('localCss') }}
        <link rel="alternate" href="{{ dominioPhp }}/{{ lang }}" hreflang="{{ language }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {% if descriptionMeta is defined %}
            <meta name="description" content="{{ t._('' ~ descriptionMeta ~ '') }}">
        {% endif %}
        <link rel="icon" type="image/png" sizes="16x16" href="{{ url('img/comun/favicon-16x16.png') }}">
        {% include 'partials/analytics.volt' %}
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