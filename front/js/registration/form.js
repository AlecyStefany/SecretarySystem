import { fetchStudents, fetchCourses, createRegistration, updateRegistration } from './get.js';
import { showAlertModal } from '../modal.js'; 

let registrationForm = document.getElementById('registrationForm');
const modalLabel = document.getElementById('registrationModalLabel');
const registrationFormId = document.getElementById('registrationFormId');
const registrationStudent = document.getElementById('registrationStudent');
const registrationCourse = document.getElementById('registrationCourse');
const registrationFormMessage = document.getElementById('registrationFormMessage');
let registrationModalElement = document.getElementById('registrationModal');
let bootstrapModal;

export async function initForm() {
  registrationModalElement = document.getElementById('registrationModal');
  bootstrapModal = bootstrap.Modal.getOrCreateInstance(registrationModalElement);

  registrationForm = document.getElementById('registrationForm');
  if (!registrationForm) {
    console.error('Elemento #registrationForm não encontrado');
    return;
  }

  await populateStudentsSelect();
  await populateCoursesSelect();

  registrationForm.addEventListener('submit', async (e) => {
    console.log(registrationFormMessage)
    e.preventDefault();
    registrationFormMessage.textContent = ''; 

    const id = registrationFormId.value;
    const studentId = registrationStudent.value;
    const courseId = registrationCourse.value;

    if (!studentId || !courseId) {
      registrationFormMessage.textContent = 'Aluno e Curso são obrigatórios.';
      return;
    }

    try {
      if (id) {
        await updateRegistration(id, { studentId, courseId });
        showAlertModal('Sucesso', 'Matrícula atualizada com sucesso', 'success');
      } else {
        await createRegistration({ studentId, courseId });
        showAlertModal('Sucesso', 'Matrícula criada com sucesso', 'success');
      }

      bootstrapModal.hide();
      if (typeof window.loadPage === 'function') {
        window.loadPage();  
      }
    } catch (error) {
      registrationFormMessage.textContent = error.message;
      showAlertModal('Erro', error.message, 'error');
    }
  });
}

export async function openRegistrationModalForCreate() {
  modalLabel.textContent = 'Nova Matrícula';
  registrationFormId.value = '';  
  registrationStudent.value = '';  
  registrationCourse.value = '';  
  registrationFormMessage.textContent = '';  
  bootstrapModal.show();
}

export async function openRegistrationModalForEdit(registration) {
  modalLabel.textContent = 'Editar Matrícula';
  registrationFormId.value = registration.id;
  registrationStudent.value = registration.studentId;
  registrationCourse.value = registration.courseId;
  registrationFormMessage.textContent = '';
  bootstrapModal.show();
}

async function populateStudentsSelect() {
  try {
    const data = await fetchStudents();
    const students = data.INFO.ALUNOS || data;
    const registrationStudent = document.getElementById('registrationStudent');

    if (!registrationStudent) {
      console.error('Elemento #registrationStudent não encontrado');
      return;
    }

    registrationStudent.innerHTML = '<option value="">Selecione o Aluno</option>';
    students.forEach((student) => {
      const opt = document.createElement('option');
      opt.value = student.id;
      opt.textContent = student.name;
      registrationStudent.appendChild(opt);
    });
  } catch (error) {
    console.error('Erro ao carregar alunos:', error);
  }
}

async function populateCoursesSelect() {
  try {
    const data = await fetchCourses();
    const courses = data.INFO.CURSOS || data;
    const registrationCourse = document.getElementById('registrationCourse');

    if (!registrationCourse) {
      console.error('Elemento #registrationCourse não encontrado');
      return;
    }

    registrationCourse.innerHTML = '<option value="">Selecione o Curso</option>';
    courses.forEach((course) => {
      const opt = document.createElement('option');
      opt.value = course.id;
      opt.textContent = course.name;
      registrationCourse.appendChild(opt);
    });
  } catch (error) {
    console.error('Erro ao carregar cursos:', error);
  }
}