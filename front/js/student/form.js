import { openModalForCreate, initGenericForm } from '../common/formHandler.js';
import { fetchStudents } from './get.js';
import { renderStudents } from './table.js';
import { API_BASE_URL } from '../common/api.js';

export function openStudentModalForCreate() {
  openModalForCreate({
    formId: 'studentForm',
    modalId: 'studentModal',
    titleId: 'studentModalLabel',
    titleText: 'Cadastrar Aluno'
  });
}

export function initForm() {
  const documentInput = document.getElementById('studentDocument');
  if (documentInput) {
    documentInput.addEventListener('input', () => {
      documentInput.value = documentInput.value.replace(/\D/g, '');
    });
  }

  initGenericForm({
    formId: 'studentForm',
    modalId: 'studentModal',
    apiBaseUrl: API_BASE_URL,
    resource: 'student',
    formatData: () => {
      const name = document.getElementById('studentName').value.trim();
      const documentValue = document.getElementById('studentDocument').value;
      const birthDate = document.getElementById('studentBirthDate').value;
      return { name, document: documentValue, birthDate };
    },
    refreshList: async () => {
      const result = await fetchStudents();
      renderStudents(result.INFO.ALUNOS);
    }
  });
}
