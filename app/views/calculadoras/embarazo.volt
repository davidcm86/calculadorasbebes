<ol class="breadcrumb aligner aligner--centerHoritzontal">
    {{ Breadcrumbs.output() }}
</ol>
<h1 class="aligner aligner--centerHoritzontal aligner--centerVertical">{{ t._('calculadora-embarazo') }}</h1>
{{ form('' ~ formAction ~ '', 'method': 'post') }}

    {% if fechaPrevistaParto is defined %}
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

    <p>{{ t._('calculadora-embarazo-texto-1') }}</p>

    <label class="label">{{ t._('embarazada-form-1') }}</label>
    <div class="formCollapsed">
        <div class="select formCollapsed-item formCollapsed-itemPrimary">
            {{ select("dia-seleccion-regla", dias, 'using': ['id', 'name'], 'useEmpty': true, 'emptyText': t._('dia')) }}
        </div>
        <div class="select formCollapsed-item formCollapsed-itemPrimary">
            {{ select("mes-seleccion-regla", meses, 'using': ['id', 'name'], 'useEmpty': true, 'emptyText': t._('mes')) }}
        </div>
        <div class="select formCollapsed-item formCollapsed-itemPrimary">
            {{ select("anio-seleccion-regla", anios, 'using': ['id', 'name'], 'useEmpty': true, 'emptyText': t._('anio')) }}
        </div>
    </div>

    <div class="aligner aligner--centerHoritzontal aligner--centerVertical">
        {{ submit_button(t._('calcular'), 'class': 'button button--primary button--mobileFul') }}
    </div>
{{ end_form() }}