<div class="modal fade" id="confirmationDelete" tabindex="-1" aria-labelledby="confirmationDeleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationDeleteLabel">Are you sure ? </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Do you really want to delete note "<?=$note->title?>" and alls of its dependecies</p>
        <p>This process cannot be undone</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmExitButton">Yes, delete it !</button>
      </div>
    </div>
  </div>
</div>
