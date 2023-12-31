<div class="modal fade" id="modal-achievement" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog{{ modal_style() }} modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header mx-auto">
                <div class="text-center">
                    <p style="font-size: 27px;">{{ __('common.achievement-title') }}!</p>
                </div>
            </div>
            <div class="modal-body">
                <div class="py-3 text-center">
                    <h3>
                        {{ __('common.unlocked-achievement', ['achievement' => Session::get('achievement')]) }}
                    </h3>

                    <span class="modal-icon display-1-lg">
                        <i class="fas fa-trophy-alt fa-4x text-gold"></i>
                    </span>

                    <h3>Well done!</h3>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('users.achievements.index', ['user' => auth()->user()]) }}" type="button" class="btn btn-primary pull-left">Vsi
                    Doseški</a>
                <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Zapri">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>
    </div>
</div>
