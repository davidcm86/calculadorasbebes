<!-- Modal HTML embedded directly into document -->
<div id="ex3" class="modal">
    <div class="container-medium">
        <h2 class="aligner aligner--centerHoritzontal aligner--centerVertical">Rellena los campos</h2>
        <label class="label formulario-label aligner aligner--centerHoritzontal aligner--centerVertical">Email</label>
        <div class="formCollapsed">
            <div class="input formCollapsed-item formCollapsed-itemPrimary">
                {{ text_field('email', 'id': 'email') }}
            </div>
        </div>
        <label class="label formulario-label aligner aligner--centerHoritzontal aligner--centerVertical">Repite el Email</label>
        <div class="formCollapsed">
            <div class="input formCollapsed-item formCollapsed-itemPrimary">
                {{ text_field('email_repite', 'id': 'email_repite') }}
            </div>
        </div>

        <label class="label formulario-label aligner aligner--centerHoritzontal aligner--centerVertical">Contraseña</label>
        <div class="formCollapsed">
            <div class="input formCollapsed-item formCollapsed-itemPrimary">
                {{ password_field('password', 'size': 30, 'id': 'password') }}
            </div>
        </div>
        <label class="label formulario-label aligner aligner--centerHoritzontal aligner--centerVertical">Repite contraseña</label>
        <div class="formCollapsed">
            <div class="input formCollapsed-item formCollapsed-itemPrimary">
                {{ password_field('password_repite', 'size': 30, 'id': 'password_repite') }}
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