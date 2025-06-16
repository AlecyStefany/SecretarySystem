const API_BASE_URL = "http://localhost:8000";

export async function fetchRegistrations(filters = {}) {
  const params = new URLSearchParams();

  if (filters.student) params.append('student', filters.student);
  if (filters.course) params.append('courseId', filters.course);
  if (filters.page) params.append('page', filters.page);

  const url = `${API_BASE_URL}/registration-by-course?${params.toString()}`;

  const response = await fetch(url);
    if (!response.ok) {
    const err = await response.json();
    throw new Error(err.message || 'Erro ao buscar matrículas');
  }
    return await response.json();

}

export async function fetchStudents() {
  const response = await fetch(`${API_BASE_URL}/student`);
  if (!response.ok) {
    const err = await response.json();
    throw new Error(err.message || 'Erro ao buscar alunos');
  }
    return await response.json();

}

export async function fetchCourses() {
  const response = await fetch(`${API_BASE_URL}/course`);
   if (!response.ok) {
    const err = await response.json();
    throw new Error(err.message || 'Erro ao buscar cursos');
  }
  return await response.json();
}

export async function createRegistration(data) {
  const response = await fetch(`${API_BASE_URL}/registrations`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data),
  });

  if (!response.ok) {
    const err = await response.json();
    throw new Error(err.message || 'Erro ao criar matrícula');
  }

  return await response.json();
}

export async function updateRegistration(id, data) {
  const response = await fetch(`${API_BASE_URL}/registration/${id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data),
  });

  if (!response.ok) {
    const err = await response.json();
    throw new Error(err.message || 'Erro ao atualizar matrícula');
  }

  return await response.json();
}

export async function deleteRegistration(id) {
  const response = await fetch(`${API_BASE_URL}/registration/${id}`, {
    method: 'DELETE',
  });

  if (!response.ok) {
    const err = await response.json();
    throw new Error(err.message || 'Erro ao excluir matrícula');
  }

  return await response.json();
}