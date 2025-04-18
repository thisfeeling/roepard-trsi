$(document).ready(function () {
    $("#btnUpdateUser").on("click", function () {
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
    let user_id = $("#UserUser_id").val();
    $("#btnDetailUser").on("click", function () {
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
    });
    $("#btnDeleteUser").on("click", function () {
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
                        window.location.href = "../backend/LogoutController.php";
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
    });
});

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
