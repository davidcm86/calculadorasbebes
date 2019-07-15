<div id="ex2" class="modal">
    <div class="container-medium">
        <h2 class="aligner aligner--centerHoritzontal aligner--centerVertical">Rellena los campos</h2>
        <label class="label formulario-label aligner aligner--centerHoritzontal aligner--centerVertical">Email</label>
        <div class="formCollapsed">
            <div class="input formCollapsed-item formCollapsed-itemPrimary">
                {{ text_field('email', 'id': 'email') }}
            </div>
        </div>
        <label class="label formulario-label aligner aligner--centerHoritzontal aligner--centerVertical">Password</label>
        <div class="formCollapsed">
            <div class="input formCollapsed-item formCollapsed-itemPrimary">
                {{ password_field('password', 'size': 30, 'id': 'password') }}
            </div>
        </div>

        <div id="notification-ajax-login" class="notification notification--error aligner aligner--centerHoritzontal aligner--centerVertical"></div>

        <div class="aligner aligner--centerHoritzontal aligner--centerVertical">
            {{ submit_button(t._('entrar'), 'class': 'button button--primary button--mobileFul', 'id': 'login-ajax') }}
        </div>
        <div class="aligner aligner--centerHoritzontal aligner--centerVertical">
            <a href="#" class="aligner aligner--centerHoritzontal aligner--centerVertical" rel="modal:close">¿Has olvidado tu contraseña?</a>
        </div>
        <div class="aligner aligner--centerHoritzontal aligner--centerVertical">
            <a href="#" class="button button--small aligner--centerHoritzontal aligner--centerVertical" rel="modal:close">Cerrar</a>
        </div>
    </div>
</div>