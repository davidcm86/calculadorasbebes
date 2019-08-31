<!-- Modal HTML embedded directly into document -->
<div id="ex3" class="modal">
    <div class="container-medium">
        <h2 class="aligner aligner--centerHoritzontal aligner--centerVertical">Rellena los campos</h2>
        <label class="label formulario-label aligner aligner--centerHoritzontal aligner--centerVertical">Email</label>
        <div class="formCollapsed">
            <div class="input formCollapsed-item formCollapsed-itemPrimary">
                {{ text_field('email', 'id': 'email_registro') }}
            </div>
        </div>
        
        <label class="label formulario-label aligner aligner--centerHoritzontal aligner--centerVertical">Contraseña</label>
        <div class="formCollapsed">
            <div class="input formCollapsed-item formCollapsed-itemPrimary">
                {{ password_field('password', 'size': 30, 'id': 'password_registro') }}
            </div>
        </div>

        <label class="label formulario-label aligner aligner--centerHoritzontal aligner--centerVertical">País</label>
        <div class="formCollapsed">
            <div class="select select-fullWidth">
                {{ select("select-pais", paises, 'using': ['id', 'country_name'], 'useEmpty': true,'emptyText': t._('elige-pais')) }}
            </div>
        </div>

        <div id="notification-ajax-registro" class="notification notification--error aligner aligner--centerHoritzontal aligner--centerVertical"></div>

        <div class="aligner aligner--centerHoritzontal aligner--centerVertical">
            {{ submit_button(t._('registrarse'), 'class': 'button button--primary button--mobileFul', 'id': 'registrate-ajax') }}
        </div>

        <div class="aligner aligner--centerHoritzontal aligner--centerVertical">
            <a href="#" class="button button--small aligner--centerHoritzontal aligner--centerVertical" rel="modal:close">Cerrar</a>
        </div>
    </div>
</div>