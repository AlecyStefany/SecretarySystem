import { loadGenericPage } from '../common/pageLoader.js';
import { fetchStudents } from './get.js';
import { renderStudents } from './table.js';
import { initForm } from './form.js';
import { openStudentModalForCreate } from './form.js';

function getFilters() {
  return {
    id: document.getElementById('filterId')?.value.trim() || '',
    name: document.getElementById('filterName')?.value.trim() || '',
    document: document.getElementById('filterDocument')?.value.trim() || '',
  };
}

export const loadPage = loadGenericPage({
  htmlFile: './front/students.html',
  fetchFunction: fetchStudents,
  renderFunction: renderStudents,
  openModalFunction: openStudentModalForCreate,
  getFiltersFunction: getFilters,
  initFormFunction: initForm,
  key:"ALUNOS"
});