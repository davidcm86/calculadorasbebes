<ol class="breadcrumb aligner aligner--centerHoritzontal">
    {{ Breadcrumbs.output() }}
</ol>
<h1 class="aligner aligner--centerHoritzontal aligner--centerVertical">{{ t._('calculadora-pelo-bebe') }}</h1>
{{ form('' ~ formAction ~ '', 'method': 'post') }}

    {% if porcentajes is defined %}
        <div class="text-center notification notification--success aligner aligner--centerHoritzontal aligner--centerVertical">
            <p>
                {{ t._('color-pelo-bebe-resultado-texto') }} </br>
                {% for index, porcentaje in porcentajes %}
                    {{ t._(index) }}: {{ porcentaje }}%
                {% endfor %}
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

    <p>{{ t._('calculadora-pelo-bebe-texto-1') }}</p>
    <p>{{ t._('calculadora-pelo-bebe-texto-2') }}</p>

    <label class="label formulario-label aligner aligner--centerHoritzontal aligner--centerVertical">{{ t._('color-pelo-bebe-form') }}</label>
    <div class="formCollapsed">
        <div class="select formCollapsed-item formCollapsed-itemPrimary">
            {{ select("color-pelo-mama", coloresPelo, 'using': ['id', 'name'], 'useEmpty': true, 'emptyText': t._('color-pelo-mama')) }}
        </div>
        <div class="select formCollapsed-item formCollapsed-itemPrimary">
            {{ select("color-pelo-papa", coloresPelo, 'using': ['id', 'name'], 'useEmpty': true, 'emptyText': t._('color-pelo-papa')) }}
        </div>
    </div>

    <div class="aligner aligner--centerHoritzontal aligner--centerVertical">
        {{ submit_button(t._('calcular'), 'class': 'button button--primary button--mobileFul') }}
    </div>
{{ end_form() }}

{{ partial('partials/estadisticas', ['estadisticasCalculadora': estadisticasCalculadora, 'calculadoraId': calculadoraId]) }}