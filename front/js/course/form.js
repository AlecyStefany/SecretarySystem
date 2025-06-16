import { openModalForCreate, initGenericForm } from '../common/formHandler.js';
import { fetchCourses } from './get.js';
import { renderCourses } from './table.js';
import { API_BASE_URL } from '../common/api.js';

export function openCourseModalForCreate() {
  openModalForCreate({
    formId: 'courseForm',
    modalId: 'courseModal',
    titleId: 'courseModalLabel',
    titleText: 'Cadastrar Curso'
  });
}

export function initForm() {

  initGenericForm({
    formId: 'courseForm',
    modalId: 'courseModal',
    apiBaseUrl: API_BASE_URL,
    resource: 'course',
    formatData: () => {
      const name = document.getElementById('courseName').value.trim();
      const description = document.getElementById('courseDescription').value;
      return { name, description};
    },
    refreshList: async () => {
      const result = await fetchCourses();
      renderCourses(result.INFO.CURSOS);
    }
  });
}
