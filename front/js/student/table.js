import { renderTable, openEditModal, handleDelete } from '../common/table.js';
import { formatCPF, formatDate } from '../utils.js';
import { fetchStudents } from '../student/get.js';

const resource = 'student';

export async function renderStudents() {
  const result = await fetchStudents();
  renderTable({
    data: result.INFO.ALUNOS,
    elementId: 'studentsList',
    columns: [
      { key: 'id' },
      { key: 'name' },
      { key: 'document', render: formatCPF },
      { key: 'birthDate', render: formatDate }
    ],
    onEdit: id => openEditModal(resource, 'studentForm', 'studentModal', fillStudentForm, id),
    onDelete: id => handleDelete(resource, id, renderStudents),
  });
}

function fillStudentForm(student) {
  document.getElementById('studentFormId').value = student.id;
  document.getElementById('studentName').value = student.name;
  const cpfInput = document.getElementById('studentDocument');
  cpfInput.value = student.document;
  cpfInput.disabled = true;
  document.getElementById('studentBirthDate').value = student.birthDate;
}

document.addEventListener('DOMContentLoaded', () => {
  renderStudents();

});