<div class="row">
    <div class="col-sm-12 aligner aligner--centerHoritzontal aligner--centerVertical header">
        {% if rutaHome is defined %}
            <a href="{{ rutaHome }}">{{ image("img/comun/logo-bebe.png", "alt": "Logo bebé calculadora", "width": "102") }}</a>
        {% else %}
            {{ image("img/comun/logo-bebe.png", "alt": "Logo bebé calculadora", "width": "102") }}
        {% endif %}
    </div>
</div>
<hr class="style-two">