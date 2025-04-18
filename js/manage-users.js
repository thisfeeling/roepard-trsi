$(document).ready(function () {
    $("#btnActualizarUsuario").on("click", function () {
        // Obtener los datos del formulario
        let formData = $("#formUsuario").serialize();
        // Enviar los datos al servidor con AJAX
        $.ajax({
            url: '../backend/UpUserController.php',
            method: 'POST',
            data: formData,
            success: function (response) {
                console.log(response);
                let result = JSON.parse(response);
                if (result.success) {
                    showModal(result.success);
                    ListUsers();
                    $('#detalleUsuarioModal').modal('hide'); // Cerrar modal
                    $('.modal-backdrop').remove(); // Eliminar fondo residual
                    document.body.classList.remove('modal-open'); // Remover clase residual
                    document.body.style.removeProperty('padding-right'); // Eliminar padding añadido por Bootstrap 
                } else {
                    showModal(result.error);
                }
            },
            error: function () {
                showModal("Error connecting to the server.");
            }
        });
    });
    $("#btnCrearUsuario").on("click", function () {
        const formData = {
            profile_picture: $('#createUserProfilePicture').val(),
            website: $('#createUserWebsite').val(),
            first_name: $('#createUserFirstName').val(),
            last_name: $('#createUserLastName').val(),
            username: $('#createUserUsername').val(),
            email: $('#createUserEmail').val(),
            phone: $('#createUserPhone').val(),
            password: $('#createUserPassword').val(),
            country: $('#createUserCountry').val(),
            city: $('#createUserCity').val(),
            birthdate: $('#createUserBirthdate').val(),
            bio: $('#createUserBio').val(),
            status_id: $('#createUserStatus').val(),
            role_id: $('#createUserRole').val()
        };
        // Validación rápida de campos
        if (Object.values(formData).some(field => field.trim() === "")) {
            showModal("Please complete all fields.");
            return;
        }
        // Llamada AJAX para crear usuario
        $.ajax({
            url: '../backend/CrUserController.php',
            method: 'POST',
            data: formData,
            success: function (response) {
                const res = JSON.parse(response);
                if (res.success) {
                    showModal("User created successfully.");
                    // Recargar la lista de usuarios
                    ListUsers();
                    // Cierra el modal
                    $('#crearUsuarioModal').modal('hide');
                    $('.modal-backdrop').remove(); // Eliminar el fondo
                } else {
                    showModal("Error creating user: " + res.message);
                }
            },
            error: function () {
                showModal("Request error.");
            }
        });
    });
    ListUsers();
});

function ListUsers() {
    $.ajax({
        url: '../backend/LiUserController.php',
        method: 'POST',
        success: function (response) {
            // console.log("Respuesta ");
            // console.log(response);
            // console.log("User list loaded.");
            $("#ListUsers").html(response);
        }
    })
};

function mostrarDetallesUsuario(user_id) {
    $.ajax({
        url: '../backend/DetUserController.php',
        method: 'POST',
        data: { user_id: user_id },
        success: function (response) {
            console.log(response);
            let user = JSON.parse(response);
            if (user.error) {
                showModal(user.error);
            } else {
                // Llena los campos del formulario con los datos del usuario
                $("#modalUserUser_id").val(user_id);
                $("#modalUserProfilePicture").val(user.profile_picture);
                $("#modalUserWebsite").val(user.website);
                $("#modalUserFirstName").val(user.first_name);
                $("#modalUserLastName").val(user.last_name);
                $("#modalUserUsername").val(user.username);
                $("#modalUserEmail").val(user.email);
                $("#modalUserPhone").val(user.phone);
                $("#modalUserPassword").val(user.password);
                $("#modalUserCountry").val(user.country);
                $("#modalUserCity").val(user.city);
                $("#modalUserBirthdate").val(user.birthdate);
                $("#modalUserBio").val(user.bio);
                $("#modalUserStatus").val(user.status_id);
                $("#modalUserRole").val(user.role_id);
                // Abre el modal
                $("#detalleUsuarioModal").modal('show');
            }
        },
        error: function () {
            showModal("Error getting user details.");
        }
    });
};

function eliminarUsuario(user_id) {
    // Mostrar el modal de confirmación
    $('#confirmDeleteModal').modal('show');

    // Asignar la acción de eliminar al botón de confirmación dentro del modal
    $('#confirmDeleteBtn').off('click').on('click', function () {
        $.ajax({
            url: '../backend/DelUserController.php',
            method: 'POST',
            data: { user_id: user_id },
            success: function (response) {
                console.log("Respuesta del servidor:", response);
                let result = JSON.parse(response);
                if (result.success) {
                    showModal(result.success);
                    // Recargar la lista de usuarios
                    ListUsers();
                    $('#confirmDeleteModal').modal('hide'); // Cerrar el modal de confirmación
                } else {
                    showModal(result.error);
                }
            },
            error: function () {
                showModal("Error connecting to the server.");
            }
        });
    });
};

// Función para mostrar el modal de mensajes
function showModal(message) {
    $('#crearUsuarioModal').modal('hide');
    $("#detalleUsuarioModal").modal('hide');
    var $modal = $('#manageUsersModal');
    $modal.find('.modal-title').text("User Management response: ");
    $modal.find('.modal-body').text(message);
    $modal.modal('show');
    $modal.on('hidden.bs.modal', function () {
        // El modal primario no permite controlar la pagina por eso esta linea 
        window.location.reload();
    });
};
