$(document).ready(function () {
    $("#btnUpdateUser").on("click", function () {
        let form = $('#formUpdateUsuario')[0];
        let formData = new FormData(form);
    
        // Validación extra en el frontend (opcional)
        if (!formData.get('current_password')) {
            showModal("Debes ingresar tu contraseña actual para actualizar los datos.");
            return;
        }
    
        $.ajax({
            url: '/trsi/backend/controllers/UpUserController.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // console.log("Respuesta del servidor:", response);
                let result = JSON.parse(response);
                if (result.success) {
                    showModal(result.success);
                    $('#detalleUsuarioModal').modal('hide');
                    $('.modal-backdrop').remove();
                    document.body.classList.remove('modal-open');
                    document.body.style.removeProperty('padding-right');
                    // Limpiar campos de contraseña
                    $('#modalUserCurrentPassword').val('');
                    $('#modalUserPassword').val('');
                    // Actualizar datos en la tarjeta principal
                    $("#userFirstNameCard").text($("#modalUserFirstName").val());
                    $("#userLastNameCard").text($("#modalUserLastName").val());
                    $("#userUsernameCard").text($("#modalUserUsername").val());
                    $("#userEmailCard").text($("#modalUserEmail").val());
                    $("#userPhoneCard").text($("#modalUserPhone").val());
                    $("#userCityCard").text($("#modalUserCity").val());
                    $("#userCountryCard").text($("#modalUserCountry").val());
                    $("#userBirthdateCard").text($("#modalUserBirthdate").val());
                    $("#userStatusCard").text($("#modalUserStatus").val());
                    $("#userRoleCard").text($("#modalUserRole").val());
                    // Si quieres actualizar la imagen de perfil:
                    let newProfilePicture = $("#modalUserExistingPicture").val();
                    if (newProfilePicture) {
                        // Si el usuario subió una nueva imagen, puedes forzar la recarga de la imagen
                        // (esto solo funciona si el input es tipo file y el backend devuelve el nombre del archivo)
                        $("#userProfilePictureCard").attr("src", "/trsi/uploads/" + newProfilePicture + "?" + new Date().getTime());
                    }
                } else {
                    showModal(result.error || "Error desconocido");
                }
            },
            error: function () {
                showModal("Error conectando con el servidor.");
            }
        });
    });

    $("#btnDetailUser").on("click", function () {
        // Intenta primero con la variable global, si no, busca el input
        let user_id = window.USER_ID || $("#UserUser_id").val();
        if (!user_id) {
            showModal("ID de usuario no válido.");
            return;
        }
        $.ajax({
            url: '/trsi/backend/controllers/DetUserController.php',
            method: 'POST',
            data: { user_id: user_id },
            success: function (response) {
                // console.log(response); // Verifica la respuesta completa
                let user = JSON.parse(response);
                if (user.error) {
                    showModal(user.error);
                } else {
                    //console.log("User Info:", user); // Verifica los datos del usuario

                    // Verifica la existencia de los elementos antes de manipularlos
                    if ($("#modalUserUser_id").length) {
                        $("#modalUserUser_id").val(user_id);
                    }

                    if ($("#modalUserProfilePicture").length) {
                        $("#modalUserProfilePicture").val(user.profile_picture);  // Asegúrate de que este campo sea un <input>, si es una imagen usa attr
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

                    let phone = user.phone;
                    if (phone.startsWith("+00")) {
                        phone = phone.slice(3); // Eliminar el prefijo redundante "+00"
                    } else if (phone.startsWith("+000")) {
                        phone = phone.slice(4); // Eliminar el prefijo redundante "+000"
                    }
                    $("#modalUserPhone").val(phone);

                    document.getElementById("modalUserPhone").addEventListener("input", function () {
                        const prefix = document.getElementById("modalUserPhonePrefix").value;
                        let number = this.value;

                        // Normalizar prefijos como +1-268, +1-876, etc.
                        const normalizedPrefix = prefix.replace(/[^+\d]/g, "");

                        switch (normalizedPrefix) {
                            case "+34": // España
                                if (number.startsWith("0")) {
                                    number = number.slice(1);
                                }
                                break;
                            case "+52": // México
                                if (number.startsWith("044") || number.startsWith("045")) {
                                    number = number.slice(3);
                                } else if (number.startsWith("1") && number.length === 11) {
                                    number = number.slice(1);
                                }
                                break;
                            case "+1": // Estados Unidos y Canadá (incluye +1-xxx)
                            case "+1242": case "+1268": case "+1345": case "+1441":
                            case "+1473": case "+1649": case "+1664": case "+1670":
                            case "+1671": case "+1684": case "+1758": case "+1767":
                            case "+1784": case "+1787": case "+1809": case "+1868":
                            case "+1876": // Países con código +1
                                if (number.startsWith("1") && number.length === 11) {
                                    number = number.slice(1);
                                }
                                break;
                            case "+91": // India
                                if (number.startsWith("0")) {
                                    number = number.slice(1);
                                }
                                break;
                            case "+57": // Colombia
                                if (number.startsWith("03")) {
                                    number = number.slice(1); // Quitar un 0 si lo tiene
                                }
                                break;
                            default:
                                // Para la mayoría de países, si el número empieza con 0, quitarlo
                                if (number.startsWith("0")) {
                                    number = number.slice(1);
                                }
                                break;
                        }

                        const fullNumber = normalizedPrefix + number;
                        //console.log("Número completo:", fullNumber);
                    });

                    if ($("#modalUserCountry").length) {
                        $("#modalUserCountry").val(user.country);
                    }

                    if ($("#modalUserCity").length) {
                        $("#modalUserCity").val(user.city);
                    }

                    if ($("#modalUserBirthdate").length) {
                        $("#modalUserBirthdate").val(user.birthdate);
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
    
                    // console.log("Imagen redimensionada");
                }, file.type, 0.9);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
    $("#modalUserCountry").on("change", function() {
        if ($(this).val() === "Colombia") {
            $("#ciudadColombiaDiv").show();
            $("#ciudadOtroDiv").hide();
            $("#modalUserCityOtro").val('');
        } else if ($(this).val() !== "") {
            $("#ciudadColombiaDiv").hide();
            $("#ciudadOtroDiv").show();
            $("#modalUserCity").val('');
        } else {
            $("#ciudadColombiaDiv").hide();
            $("#ciudadOtroDiv").hide();
            $("#modalUserCity").val('');
            $("#modalUserCityOtro").val('');
        }
      });
});

// Función para mostrar el modal de mensajes
function showModal(message) {
    if ($('#crearUsuarioModal').length) {
        $('#crearUsuarioModal').modal('hide');
    }
    if ($('#detalleUsuarioModal').length) {
        $('#detalleUsuarioModal').modal('hide');
    }
    
    var $modal = $('#manageUsersModal');
    if ($modal.length) {
        $modal.find('.modal-title').text("Respuesta de Gestión de Usuario: ");
        $modal.find('.modal-body').text(message);
        $modal.modal('show');
    } else {
        console.error("El modal no está disponible.");
    }
}
