const modalEl = document.getElementById('alertModal');
const modalHeader = document.getElementById('alertModalHeader');
const modalTitle = document.getElementById('alertModalTitle');
const modalMessage = document.getElementById('alertModalMessage');
const modalFooter = document.getElementById('alertModalFooter');

const bootstrapModal = new bootstrap.Modal(modalEl);

let lastFocusedElement = null;

modalEl.addEventListener('show.bs.modal', () => {
  lastFocusedElement = document.activeElement;
});

modalEl.addEventListener('hidden.bs.modal', () => {
  const inputs = document.querySelectorAll('.filter-input');
  inputs.forEach(input => (input.value = ''));
});

export function showAlertModal(title, message, type = 'info') {
  lastFocusedElement = document.activeElement;

  modalTitle.textContent = title;
  modalMessage.textContent = message;

  modalHeader.className = 'modal-header';

  switch (type) {
    case 'error':
      modalHeader.classList.add('bg-danger', 'text-white');
      break;
    case 'success':
      modalHeader.classList.add('bg-success', 'text-white');
      break;
    case 'warning':
      modalHeader.classList.add('bg-warning');
      break;
    default:
      modalHeader.classList.add('bg-primary', 'text-white');
      break;
  }

  modalFooter.innerHTML = `
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
  `;

  bootstrapModal.show();
}

export function showConfirmModal(title, message, confirmCallback) {
  lastFocusedElement = document.activeElement;

  modalTitle.textContent = title;
  modalMessage.textContent = message;

  modalHeader.className = 'modal-header';
  modalHeader.classList.add('bg-warning');

  modalFooter.innerHTML = `
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
    <button type="button" class="btn btn-danger" id="confirmModalButton">Confirmar</button>
  `;

  const confirmButton = document.getElementById('confirmModalButton');
  confirmButton.addEventListener('click', () => {
    confirmButton.blur();
    document.body.focus();
    bootstrapModal.hide();

    confirmCallback();
  }, { once: true });

  bootstrapModal.show();
}