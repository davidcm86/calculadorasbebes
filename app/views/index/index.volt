<div class="col-sm-12 aligner aligner--centerHoritzontal aligner--centerVertical">
    <h1>{{ t._('h1-inicio') }}</h1>
</div>
<div class="row">
    {%for calculadora in calculadoras%}
        <div class="col-sm-4 aligner aligner--centerHoritzontal aligner--centerVertical fondo-calculadoras-index">
            <div class="container">
                <div class="col-sm-12 aligner aligner--centerHoritzontal aligner--centerVertical">
                    <h2 class="h2-calculadora-index">{{ link_to('/' ~ lang ~ '/' ~ calculadora.slug ~ '', calculadora.nombre_calculadora) }}</h2>
                </div>
                <div class="col-sm-12 aligner aligner--centerHoritzontal aligner--centerVertical">
                    <a href="{{ '/' ~ lang ~ '/' ~ calculadora.slug ~ '' }}">
                        {{ image("" ~ calculadora.ruta_imagen ~ "", "alt": "" ~ calculadora.nombre_calculadora ~ "", "width": "102") }}
                    </a>
                </div>
            </div>
        </div>
    {%endfor%}
</div>