import { openRegistrationModalForEdit } from './form.js';
import { deleteRegistration } from './get.js';
import { showAlertModal } from '../modal.js';

export function renderRegistrations(registrations) {
  const tbody = document.getElementById('registrationsList');
  tbody.innerHTML = '';

  if (!registrations.length) {
    tbody.innerHTML = `<tr><td colspan="4" class="text-center">Nenhuma matrícula encontrada.</td></tr>`;
    return;
  }

  registrations.forEach((reg) => {
    const tr = document.createElement('tr');

    tr.innerHTML = `
      <td>${reg.id}</td>
      <td>${reg.studentName || reg.student?.name || '—'}</td>
      <td>${reg.courseName || reg.course?.name || '—'}</td>
      <td>
        <button class="btn btn-sm btn-primary btn-edit" data-id="${reg.id}">Editar</button>
        <button class="btn btn-sm btn-danger btn-delete" data-id="${reg.id}">Excluir</button>
      </td>
    `;

    tbody.appendChild(tr);
  });

  tbody.querySelectorAll('.btn-edit').forEach((btn) => {
    btn.addEventListener('click', async (e) => {
      const id = e.target.dataset.id;
      try {
        const data = await fetchRegistrations({ id });
        const reg = data.INFO?.MATRICULAS?.find(r => r.id == id) || data.INFO?.[0];
        if (reg) openRegistrationModalForEdit(reg);
      } catch {
        showAlertModal('Erro', 'Não foi possível carregar a matrícula para edição.', 'error');
      }
    });
  });

  tbody.querySelectorAll('.btn-delete').forEach((btn) => {
    btn.addEventListener('click', async (e) => {
      const id = e.target.dataset.id;
      if (confirm('Deseja realmente excluir esta matrícula?')) {
        try {
          await deleteRegistration(id);
          showAlertModal('Sucesso', 'Matrícula excluída com sucesso.', 'success');
          if (typeof window.loadPage === 'function') window.loadPage();
        } catch (error) {
          showAlertModal('Erro', error.message || 'Erro ao excluir matrícula.', 'error');
        }
      }
    });
  });
}