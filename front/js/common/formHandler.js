import { showMessage } from '../utils.js';

export function openModalForCreate({ formId, modalId, titleId, titleText }) {
  const form = document.getElementById(formId);
  if (!form) return;

  form.reset();

  const idInput = document.getElementById(`${formId}Id`);
  if (idInput) idInput.value = '';

  if (titleId && titleText) {
    const titleEl = document.getElementById(titleId);
    if (titleEl) titleEl.textContent = titleText;
  }

  const modalEl = document.getElementById(modalId);
  if (!modalEl) return;

  const modal = new bootstrap.Modal(modalEl);
  modal.show();
}

export function initGenericForm({
  formId,
  modalId,
  apiBaseUrl,
  resource,
  formatData,
  refreshList
}) {
  const form = document.getElementById(formId);
  if (!form) return;

  form.addEventListener('submit', async e => {
    e.preventDefault();

    const idInput = document.getElementById(`${formId}Id`);
    const id = idInput ? idInput.value : '';

    const data = formatData();

    let url = `${apiBaseUrl}/${resource}`;
    let method = 'POST';

    if (id) {
      url += `/${id}`;
      method = 'PUT';
    }

    try {
      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
      });

      const result = await res.json();

      if (!res.ok) throw new Error(result.ERROR + '. [' + result.INFO + ']');

      showMessage('Registro salvo com sucesso!', 'success', `${formId}Message`);

      form.reset();

      const modalEl = document.getElementById(modalId);
      const modal = bootstrap.Modal.getInstance(modalEl);
      modal.hide();

      if (refreshList) refreshList();

    } catch (error) {
      showMessage(error.message, 'danger', `${formId}Message`);
    }
  });
}