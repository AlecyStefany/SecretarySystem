import { setupListPage } from '../common/listPage.js';
import { fetchCourse } from './get.js';
import { renderCourse } from './table.js';
import { openCourseModalForCreate } from './form.js';

setupListPage({
  htmlFile: './front/courses.html',
  fetchData: fetchCourse,
  renderTable: renderCourse,
  filterFields: ['Id', 'Name', 'Document'],
  onNewItem: openCourseModalForCreate,
  sortFunction: (a, b) => a.name.localeCompare(b.name) 
});