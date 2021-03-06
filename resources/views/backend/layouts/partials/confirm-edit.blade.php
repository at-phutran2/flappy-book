<div class="modal" id="confirm-edit">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title" id="title-edit-content">{{ __('categories.confirm_edit') }}</h4>
        </div>
        <div class="modal-body" id="body-edit-content"></div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-flat btn-primary btn-confirm-edit" id="edit-btn" data-dismiss="modal">{{ __('categories.edit') }}</button>
            <button type="button" class="btn btn-sm btn-flat btn-warning btn-confirm-edit" id="reset-btn" data-dismiss="modal">{{ __('categories.reset') }}</button>
            <button type="button" class="btn btn-sm btn-flat btn-default btn-confirm-edit" id="cancel-btn" data-dismiss="modal">{{ __('categories.cancel') }}</button>
        </div>
    </div>
  </div>
</div>
