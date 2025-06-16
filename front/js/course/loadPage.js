import { loadGenericPage } from '../common/pageLoader.js';
import { fetchCourses } from './get.js';
import { renderCourses } from './table.js';
import { initForm } from './form.js';
import { openCourseModalForCreate } from './form.js';

function getFilters() {
  return {
    id: document.getElementById('filterId')?.value.trim() || '',
    name: document.getElementById('filterName')?.value.trim() || '',
  };
}

export const loadPage = loadGenericPage({
  htmlFile: './front/courses.html',
  fetchFunction: fetchCourses,
  renderFunction: renderCourses,
  openModalFunction: openCourseModalForCreate,
  getFiltersFunction: getFilters,
  initFormFunction: initForm,
  key:"CURSOS"
});