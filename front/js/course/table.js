import { renderTable, openEditModal, handleDelete } from '../common/table.js';
import { fetchCourses } from '../course/get.js';

const resource = 'course';

export async function renderCourses() {
  const result = await fetchCourses();
  renderTable({
    data: result.INFO.CURSOS,
    elementId: 'coursesList',
    columns: [
      { key: 'id' },
      { key: 'name' },
      { key: 'description' },
    ],
    onEdit: id => openEditModal(resource, 'courseForm', 'courseModal', fillCourseForm, id),
    onDelete: id => handleDelete(resource, id, renderCourses),
  });
}

function fillCourseForm(course) {
  document.getElementById('courseFormId').value = course.id;
  document.getElementById('courseName').value = course.name;
  document.getElementById('courseDescription').value = course.description;
}

document.addEventListener('DOMContentLoaded', () => {
  renderCourses();
});