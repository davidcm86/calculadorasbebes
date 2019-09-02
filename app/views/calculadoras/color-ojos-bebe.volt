<ol class="breadcrumb aligner aligner--centerHoritzontal">
    {{ Breadcrumbs.output() }}
</ol>
<h1 class="aligner aligner--centerHoritzontal aligner--centerVertical">{{ t._('calculadora-ojos-bebe') }}</h1>
{{ form('' ~ formAction ~ '', 'method': 'post') }}

    {% if marron is defined %}
        <div class="text-center notification notification--success aligner aligner--centerHoritzontal aligner--centerVertical">
            <p>
                {{ t._('color-ojos-resultado-1') }}
            </p>
        </div>
        <div class="container">
            <div class="row">
                    <div class="col background-azul notification aligner aligner--centerHoritzontal aligner--centerVertical">
                        <p>{{ t._('azul') }} {{ azul }} %</p>
                    </div>
                    <div class="col background-marron notification aligner aligner--centerHoritzontal aligner--centerVertical">
                        <p>{{ t._('marron') }} {{ marron }} %</p>
                    </div>
                    <div class="col background-verde notification aligner aligner--centerHoritzontal aligner--centerVertical">
                        <p>{{ t._('verde') }} {{ verde }} %</p>
                    </div>
            </div>
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
            {{ select("color-ojos-papa", colorOjos, 'using': ['id', 'name'], 'useEmpty': true, 'emptyText': t._('color-ojos-form-2')) }}
        </div>
    </div>

    <div class="aligner aligner--centerHoritzontal aligner--centerVertical">
        {{ submit_button(t._('calcular'), 'class': 'button button--primary button--mobileFul') }}
    </div>
{{ end_form() }}

{{ partial('partials/estadisticas', ['estadisticasCalculadora': estadisticasCalculadora, 'calculadoraId': calculadoraId]) }}