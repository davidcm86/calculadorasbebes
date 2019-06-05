{% if estadisticasCalculadora is defined %}
    <h2>Resultados de otros usuarios</h2>
    {% for estadistica in estadisticasCalculadora %}
        Fecha: {{ estadistica.created  }}</br>
        Fecha concepción: {{ estadistica.data  }}</br>
        Fecha parto: {{ estadistica.result  }}</br>
    {% endfor %}
{% endif %}