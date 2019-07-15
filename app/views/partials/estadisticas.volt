{% if estadisticasCalculadora is not empty %}
    <div class="container">
        <h2 class="aligner aligner--centerHoritzontal aligner--centerVertical">{{ t._('resultado-otros-usuarios') }}</h2>
        <div class="row aligner aligner--centerHoritzontal aligner--centerVertical">
            {% for estadistica in estadisticasCalculadora %}
                <div class="col-sm-5 contenedor-estadisticas">
                    {% switch calculadoraId %}
                        {% case constant("CAL_EMBARAZO") %}
                            {{ t._('creado-el') }}: <b>{{ estadistica.created }}</b></br>
                            {{ t._('fecha-mestruacion') }}: <b>{{ estadistica.data }}</b></br>
                            {{ t._('fecha-parto') }}: <b>{{ estadistica.result }}</b></br>
                            {% break %}
                        {% case constant("CAL_SEXO_BEBE") %}
                            {{ t._('creado-el') }}: <b>{{ estadistica.created  }}</b></br>
                            {{ t._('edad-mama') }}: <b>{{ estadistica.edad_mama }}</b></br>
                            {{ t._('mes-concepcion') }}: <b>{{ estadistica.mes_concepcion_bebe }}</b></br>
                            {{ t._('sexo') }}: <b>{{ estadistica.result }}</b></br>
                        {% break %}
                        {% case constant("CAL_OJOS_BEBE") %}
                            {{ t._('creado-el') }}: <b>{{ estadistica.created }}</b></br>
                            {{ t._('color-ojos-form-1') }}: <b>{{ estadistica.color_ojos_mama }}</b></br>
                            {{ t._('color-ojos-form-2') }}: <b>{{ estadistica.color_ojos_papa }}</b></br>
                            {{ t._('probabilidad') }}: </br>
                                {{ t._('marron') }}: <b>{{ estadistica.marron }} %</b>,  
                                {{ t._('verde') }}: <b>{{ estadistica.verde }} %</b>,  
                                {{ t._('azul') }}: <b>{{ estadistica.azul }} %</b></br>
                        {% break %}
                        {% case constant("CAL_PESO_BEBE") %}
                            {{ t._('creado-el') }}: <b>{{ estadistica.created  }}</b></br>
                            {{ t._('semana-select') }}: <b>{{ estadistica.semana }}</b></br>
                            {{ t._('peso') }}: <b>{{ estadistica.peso }} {{ t._('gramos') }}</b></br>
                        {% break %}
                    {% endswitch %}
                </div>
            {% endfor %}
        </div>
    </div>
    <div class="container">
    <div class="row aligner aligner--centerHoritzontal aligner--centerVertical">
        <!-- TODO: solo mostrar si hay más de 4 resultados -->
        <a href="{{ usuarioLoginRegistroResultados }}" rel="modal:open" class="button   btn-mas-resultados">Ver más resultados</a>
    </div>
    </div>
{% endif %}
<!-- Link to open the modal -->

{% include 'partials/login.volt' %}
{% include 'partials/registro.volt' %}
{% include 'partials/registro-login.volt' %}
