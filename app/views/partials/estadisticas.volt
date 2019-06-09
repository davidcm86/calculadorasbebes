{% if estadisticasCalculadora is not empty %}
    <div class="container">
        <h2 class="aligner aligner--centerHoritzontal aligner--centerVertical">{{ t._('resultado-otros-usuarios') }}</h2>
        <div class="row aligner aligner--centerHoritzontal aligner--centerVertical">
            {% for estadistica in estadisticasCalculadora %}
                <div class="col-sm-5 contenedor-estadisticas">
                    {% switch calculadoraId %}
                        {% case constant("CAL_EMBARAZO") %}
                            {{ t._('creado-el') }}: {{ estadistica.created  }}</br>
                            {{ t._('fecha-mestruacion') }}: {{ estadistica.data }}</br>
                            {{ t._('fecha-parto') }}: {{ estadistica.result }}</br>
                            {% break %}
                        {% case constant("CAL_SEXO_BEBE") %}
                            {{ t._('creado-el') }}: {{ estadistica.created  }}</br>
                            {{ t._('edad-mama') }}: {{ estadistica.edad_mama }}</br>
                            {{ t._('mes-concepcion') }}: {{ estadistica.mes_concepcion_bebe }}</br>
                            {{ t._('sexo') }}: {{ estadistica.result }}</br>
                        {% break %}
                    {% endswitch %}
                </div>
            {% endfor %}
        </div>
    </div>
{% endif %}