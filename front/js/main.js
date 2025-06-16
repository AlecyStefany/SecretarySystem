import { loadPage as loadStudentsPage } from './student/loadPage.js';
import { loadPage as loadCoursesPage } from './course/loadPage.js';
import { loadPage as loadRegistrationsPage } from './registration/loadPage.js';

window.loadPage = loadStudentsPage;

const pageLoaders = {
  students: loadStudentsPage,
  courses: loadCoursesPage,
  registrations: loadRegistrationsPage,
};

const navLinks = document.querySelectorAll('.nav-link');

navLinks.forEach(link => {
  link.addEventListener('click', async (e) => {
    e.preventDefault();

    navLinks.forEach(l => l.classList.remove('active'));
    e.target.classList.add('active');

    const page = e.target.dataset.page;
    const loader = pageLoaders[page];

    if (loader) {
      await loader();
    } else {
      document.getElementById('content').innerHTML = `<h3>Funcionalidade ${page} em construção...</h3>`;
    }
  });
});

window.addEventListener('DOMContentLoaded', () => {
  const firstTab = document.querySelector('.nav-link[data-page="students"]');
  if (firstTab) firstTab.classList.add('active');
  loadStudentsPage();
});