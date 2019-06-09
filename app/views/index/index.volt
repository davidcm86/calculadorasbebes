<div class="row">
    {%for calculadora in calculadoras%}
        <div class="col-sm-4 aligner aligner--centerHoritzontal aligner--centerVertical">
            <div class="container">
                <div class="col-sm-12 aligner aligner--centerHoritzontal aligner--centerVertical">
                    <h2>{{ link_to('/' ~ lang ~ '/' ~ calculadora.slug ~ '', calculadora.nombre_calculadora) }}</h2>
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