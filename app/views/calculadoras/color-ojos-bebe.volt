<ol class="breadcrumb aligner aligner--centerHoritzontal">
    {{ Breadcrumbs.output() }}
</ol>
<h1 class="aligner aligner--centerHoritzontal aligner--centerVertical">{{ t._('calculadora-ojos-bebe') }}</h1>
{{ form('' ~ formAction ~ '', 'method': 'post') }}

    {% if colorOjosResult is defined %}
        <div class="notification notification--success aligner aligner--centerHoritzontal aligner--centerVertical">
            <p>
                {{ t._('embarazada-resultado-1') }}{{ fechaPrevistaParto }}
            </p>
        </div>
    {% endif %}

    {% if mensajesError is defined%}
        <div class="notification notification--error aligner aligner--centerHoritzontal aligner--centerVertical">
            <p>
                {% for mensaje in mensajesError %}
                    {{ t._(mensaje) }}</br>
                {% endfor %}
            </p>
        </div>
    {% endif %}

    <p>{{ t._('calculadora-ojos-bebe-texto-1') }}</p>

    <label class="label formulario-label aligner aligner--centerHoritzontal aligner--centerVertical">{{ t._('color-ojos-form') }}</label>
    <div class="{{ form }}">
        <div class="{{ class }}">
            {{ select("color-ojos-mama", colorOjos, 'using': ['id', 'name'], 'useEmpty': true, 'emptyText': t._('color-ojos-form-1')) }}
        </div>
        <div class="{{ class }}">
            {{ select("color-ojos-padre", colorOjos, 'using': ['id', 'name'], 'useEmpty': true, 'emptyText': t._('color-ojos-form-2')) }}
        </div>
    </div>

    <div class="aligner aligner--centerHoritzontal aligner--centerVertical">
        {{ submit_button(t._('calcular'), 'class': 'button button--primary button--mobileFul') }}
    </div>
{{ end_form() }}

{{ partial('partials/estadisticas', ['estadisticasCalculadora': estadisticasCalculadora, 'calculadoraId': calculadoraId]) }}