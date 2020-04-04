<div class="os-ticket-button">
    <button class="btn btn-primary" data-toggle="modal" data-target="#osTicketModal">{{ trans( 'osticket::feedback.feedback_button' ) }}</button>
</div>

<div class="modal fade" id="osTicketModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form name="osticket-send" action="{{ route( 'osticket.send-ticket' ) }}" method="post" enctype="multipart/form-data">
                <div class="modal-header">{{ trans( 'osticket::feedback.modal.title' ) }}</div>
                <div class="modal-body">

                    <div class="form-group @error('name') has-error @enderror">
                        <label class="form-control-label">{{ __( 'osticket::feedback.name' ) }}</label>
                        <input type="text" name="name" placeholder="{{ __( 'osticket::feedback.name' ) }}" class="form-control form-control-plain" {{ ( \Illuminate\Support\Facades\Auth::guest() ? "" : "disabled" ) }} value="{{ old('name', ( \Illuminate\Support\Facades\Auth::guest() ? "" : \Illuminate\Support\Facades\Auth::user()->name ) ) }}" >
                    </div>

                    <div class="form-group @error('email') has-error @enderror">
                        <label class="form-control-label">{{ __( 'osticket::feedback.email' ) }}</label>
                        <input type="text" name="email" placeholder="{{ __( 'osticket::feedback.email' ) }}" class="form-control form-control-plain" {{ ( \Illuminate\Support\Facades\Auth::guest() ? "" : "disabled" ) }} value="{{ old('email', ( \Illuminate\Support\Facades\Auth::guest() ? "" : \Illuminate\Support\Facades\Auth::user()->email ) ) }}" >
                    </div>

                    <div class="form-group @error('subject') has-error @enderror">
                        <label class="form-control-label">{{ __( 'osticket::feedback.subject' ) }}</label>
                        <input type="text" name="subject" placeholder="{{ __( 'osticket::feedback.subject' ) }}" class="form-control form-control-plain" value="{{ old('subject' ) }}" >
                    </div>

                    <div class="form-group @error('message') has-error @enderror">
                        <label class="form-control-label">{{ __( 'osticket::feedback.message' ) }}</label>
                        <textarea name="message" placeholder="{{ __( 'osticket::feedback.message' ) }}" class="form-control form-control-plain">{{ old('message' ) }}</textarea>
                    </div>

                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

                    <script src="https://www.google.com/recaptcha/api.js?render={{ config( 'osticket.recaptcha_v3.site_key' ) }}"></script>
                    <script>
                    grecaptcha.ready(function() {
                        grecaptcha.execute('{{ config( 'osticket.recaptcha_v3.site_key' ) }}', {action: 'osticket'}).then(function(token) {
                            var recaptchaResponse = document.getElementById('recaptchaResponse');
                            recaptchaResponse.value = token;
                        });
                    });
                    </script>

                </div>
                <div class="modal-footer">
                    <a class="btn btn-simple" data-dismiss="modal">{{ trans( 'osticket::feedback.modal.close' ) }}</a>
                    <button type="submit" class="btn btn-primary">{{ trans( 'osticket::feedback.modal.send' ) }}</button>
                </div>
            </form>
        </div>
        <script>
            $( document ).ready(function(){
                $( 'form[name="osticket-send"]' ).ajaxLaravelValidation({
                    onSuccess: function( response )
                    {
                        showSuccessMessage( '{{ __( 'osticket::feedback.ticket-created' ) }}' );
                        $('#osTicketModal').modal( 'hide' );
                    }
                });
            });
        </script>
    </div>
</div>
