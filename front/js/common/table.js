import { showMessage } from '../utils.js';
import { showAlertModal, showConfirmModal } from '../modal.js';
import { API_BASE_URL } from './api.js';

export function renderTable({ data, elementId, columns, onEdit, onDelete }) {
  const container = document.getElementById(elementId);
  if (!container) {
    console.error(`Container com id "${elementId}" não encontrado.`);
    return;
  }

  container.innerHTML = '';

  data.forEach(item => {
    const tr = document.createElement('tr');

    columns.forEach(col => {
      const td = document.createElement('td');
      const value = item[col.key];
      td.textContent = col.render ? col.render(value, item) : value ?? '';
      tr.appendChild(td);
    });

    if (onEdit || onDelete) {
      const actionsTd = document.createElement('td');

      if (onEdit) {
        const editBtn = document.createElement('button');
        editBtn.textContent = 'Editar';
        editBtn.className = 'btn btn-sm btn-primary me-2';
        editBtn.addEventListener('click', () => onEdit(item.id));
        actionsTd.appendChild(editBtn);
      }

      if (onDelete) {
        const deleteBtn = document.createElement('button');
        deleteBtn.textContent = 'Excluir';
        deleteBtn.className = 'btn btn-sm btn-danger';
        deleteBtn.addEventListener('click', () => onDelete(item.id));
        actionsTd.appendChild(deleteBtn);
      }

      tr.appendChild(actionsTd);
    }

    container.appendChild(tr);
  });
}

export async function deleteResource(resource, id) {
  try {
    const res = await fetch(`${API_BASE_URL}/${resource}/${id}`, { method: 'DELETE' });
    if (!res.ok) {
      const errorData = await res.json().catch(() => null);
      const errorMessage = errorData?.ERROR || 'Erro ao excluir o registro.';
      throw new Error(errorMessage);
    }
    return await res.json();
  } catch (error) {
    console.error(`Erro ao excluir ${resource} id=${id}:`, error);
    throw error;
  }
}

export async function fetchResourceById(resource, id) {
  const res = await fetch(`${API_BASE_URL}/${resource}?id=${id}`);
  if (!res.ok) throw new Error(`${resource} não encontrado.`);
  const data = await res.json();
  return data.INFO ? data.INFO[Object.keys(data.INFO)[0]][0] : null;
}

export async function openEditModal(resource, formId, modalId, fillForm, id) {
  try {
    const data = await fetchResourceById(resource, id);
    if (!data) throw new Error(`${resource} não encontrado`);

    fillForm(data);

    const modalLabel = document.getElementById(`${modalId}Label`);
    if (modalLabel) modalLabel.textContent = `Editar ${resource.charAt(0).toUpperCase() + resource.slice(1)}`;

    const modalEl = document.getElementById(modalId);
    let modal = bootstrap.Modal.getInstance(modalEl);
    if (!modal) modal = new bootstrap.Modal(modalEl);
    modal.show();

  } catch (error) {
    console.error(error);
    showMessage(error.message, 'danger', `${formId}Message`);
  }
}

export function handleDelete(resource, id, refreshList) {
  showConfirmModal(
    'Confirmar Exclusão',
    `Tem certeza que deseja excluir este registro?`,
    async () => {
      try {
        await deleteResource(resource, id);
        showAlertModal('Sucesso', 'Registro excluído com sucesso!');
        if (refreshList) await refreshList();
      } catch (error) {
        showAlertModal('Erro', error.message, 'error');
      }
    }
  );
}