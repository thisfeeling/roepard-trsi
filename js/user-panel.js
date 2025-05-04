$(document).ready(function () {
    $("#btnUpdateUser").on("click", function () {
        let form = $('#formUsuario')[0];
        let formData = new FormData(form);
    
        $.ajax({
            url: '../backend/UpUserController.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log("Respuesta del servidor:", response);
                let result = JSON.parse(response);
                if (result.success) {
                    showModal(result.success);
                    $('#detalleUsuarioModal').modal('hide');
                    $('.modal-backdrop').remove();
                    document.body.classList.remove('modal-open');
                    document.body.style.removeProperty('padding-right');
                } else {
                    showModal(result.error || "Error desconocido");
                }
            },
            error: function () {
                showModal("Error conectando con el servidor.");
            }
        });
    });

    let user_id = $("#UserUser_id").val();
    console.log("User ID:", user_id); // Verifica que el valor es correcto
    if (!user_id) {
        showModal("ID de usuario no válido.");
        return; // Evita que el código continúe si no se tiene un `user_id`
    }

    $("#btnDetailUser").on("click", function () {
        $.ajax({
            url: '../backend/DetUserController.php',
            method: 'POST',
            data: { user_id: user_id },
            success: function (response) {
                console.log(response); // Verifica la respuesta completa
                let user = JSON.parse(response);
                if (user.error) {
                    showModal(user.error);
                } else {
                    console.log("User Info:", user); // Verifica los datos del usuario

                    // Verifica la existencia de los elementos antes de manipularlos
                    if ($("#modalUserUser_id").length) {
                        $("#modalUserUser_id").val(user_id);
                    }

                    if ($("#modalUserProfilePicture").length) {
                        $("#modalUserProfilePicture").val(user.profile_picture);  // Asegúrate de que este campo sea un <input>, si es una imagen usa attr
                    }

                    if ($("#modalUserWebsite").length) {
                        $("#modalUserWebsite").val(user.website);
                    }

                    if ($("#modalUserFirstName").length) {
                        $("#modalUserFirstName").val(user.first_name);
                    }

                    if ($("#modalUserLastName").length) {
                        $("#modalUserLastName").val(user.last_name);
                    }

                    if ($("#modalUserUsername").length) {
                        $("#modalUserUsername").val(user.username);
                    }

                    if ($("#modalUserEmail").length) {
                        $("#modalUserEmail").val(user.email);
                    }

                    if ($("#modalUserPhone").length) {
                        $("#modalUserPhone").val(user.phone);
                    }

                    if ($("#modalUserPassword").length) {
                        $("#modalUserPassword").val(user.password);
                    }

                    if ($("#modalUserCountry").length) {
                        $("#modalUserCountry").val(user.country);
                    }

                    if ($("#modalUserCity").length) {
                        $("#modalUserCity").val(user.city);
                    }

                    if ($("#modalUserBirthdate").length) {
                        $("#modalUserBirthdate").val(user.birthdate);
                    }

                    if ($("#modalUserBio").length) {
                        $("#modalUserBio").val(user.bio);
                    }

                    if ($("#modalUserStatus").length) {
                        $("#modalUserStatus").val(user.status_id);
                    }

                    if ($("#modalUserRole").length) {
                        $("#modalUserRole").val(user.role_id);
                    }

                    // Verifica que la imagen del perfil esté disponible antes de manipularla
                    if ($("#modalUserExistingPicture").length) {
                        $("#modalUserExistingPicture").attr("src", user.profile_picture);
                    }

                    // Abre el modal
                    $("#detalleUsuarioModal").modal('show');
                }
            },            
            error: function () {
                showModal("Error obteniendo detalles del usuario.");
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
                        window.location.href = "../backend/LogoutController.php";  // Solo redirige si la eliminación fue exitosa
                        $('#confirmDeleteModal').modal('hide'); // Cerrar el modal de confirmación
                    } else {
                        showModal(result.error || "Error desconocido al eliminar el usuario.");
                    }
                },
                error: function () {
                    showModal("Error conectando con el servidor.");
                }
            });
        });
    });
    $("#modalUserExistingPicture").on("change", function (event) {
        const file = event.target.files[0];
        if (!file || !file.type.startsWith("image/")) return;
    
        // Ancho y largo
        const maxWidth = 200;
        const maxHeight = 200;

        const reader = new FileReader();
        reader.onload = function (e) {
            const img = new Image();
            img.onload = function () {
                let width = img.width;
                let height = img.height;
    
                if (width > maxWidth || height > maxHeight) {
                    const scale = Math.min(maxWidth / width, maxHeight / height);
                    width = Math.round(width * scale);
                    height = Math.round(height * scale);
                }
    
                const canvas = document.createElement("canvas");
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0, width, height);
    
                canvas.toBlob(function (blob) {
                    const resizedFile = new File([blob], file.name, {
                        type: file.type,
                        lastModified: Date.now()
                    });
    
                    // Reemplaza el archivo en el input
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(resizedFile);
                    event.target.files = dataTransfer.files;
    
                    console.log("Imagen redimensionada");
                }, file.type, 0.9);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
});

// Función para mostrar el modal de mensajes
function showModal(message) {
    // Asegúrate de que el modal esté disponible antes de intentar ocultarlo
    if ($('#crearUsuarioModal').length) {
        $('#crearUsuarioModal').modal('hide');
    }
    if ($('#detalleUsuarioModal').length) {
        $('#detalleUsuarioModal').modal('hide');
    }
    
    var $modal = $('#manageUsersModal');
    
    // Verificar que el modal esté disponible antes de cambiar su contenido
    if ($modal.length) {
        $modal.find('.modal-title').text("Respuesta de Gestión de Usuario: ");
        $modal.find('.modal-body').text(message);
        $modal.modal('show');
        
        // Manejo del evento cuando el modal se cierra
        $modal.on('hidden.bs.modal', function () {
            // Recargar la página solo si es necesario
            window.location.reload();
        });
    } else {
        console.error("El modal no está disponible.");
    }
    
    
};
