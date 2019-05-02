<div class="row">
    {%for calculadora in calculadoras%}
        <div class="col-sm-4 aligner aligner--centerHoritzontal aligner--centerVertical">
            <div class="container">
                <div class="col-sm-12 aligner aligner--centerHoritzontal aligner--centerVertical">
                    <h3>{{ link_to('/' ~ lang ~ '/' ~ calculadora.slug ~ '', calculadora.nombre_calculadora) }}</h3>
                </div>
                <div class="col-sm-12 aligner aligner--centerHoritzontal aligner--centerVertical">
                    {{ image("img/embarazada.png", "alt": "Logo bebé", "width": "102") }}
                </div>
            </div>
        </div>
    {%endfor%}
</div>