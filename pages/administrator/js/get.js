
function enviarMatricula(matricula) {
    // Crear un formulario dinámico en JavaScript para enviar los datos con POST
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "student_details.php";

    // Crear un campo oculto con la matrícula
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "matricula";
    input.value = matricula;

    // Agregar el campo oculto al formulario
    form.appendChild(input);

    // Agregar el formulario al cuerpo del documento y enviarlo
    document.body.appendChild(form);
    form.submit();                                                                                          
}


function enviarNumEmpleado(numEmpleado) {
    // Crear un formulario dinámico en JavaScript para enviar los datos con POST
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "teacher_details.php";

    // Crear un campo oculto con la matrícula
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "numEmpleado";
    input.value = numEmpleado;

    // Agregar el campo oculto al formulario
    form.appendChild(input);

    // Agregar el formulario al cuerpo del documento y enviarlo
    document.body.appendChild(form);
    form.submit();
}

function verExamen(matricula) {
    // Crear un formulario dinámico en JavaScript para enviar los datos con POST
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "student_exams.php";

    // Crear un campo oculto con la matrícula
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "matricula";
    input.value = matricula;

    // Agregar el campo oculto al formulario
    form.appendChild(input);

    // Agregar el formulario al cuerpo del documento y enviarlo
    document.body.appendChild(form);
    form.submit();
}

