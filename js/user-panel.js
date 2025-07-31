var iti;
$(document).ready(function () {
    // Inicialización del input para el teléfono con el plugin intlTelInput
    var input = document.querySelector("#modalUserPhone");
    iti = window.intlTelInput(input, {
        initialCountry: "auto", // País por defecto
        nationalMode: false, // Siempre incluye el prefijo
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/utils.js"
    });

    // Manejo del clic en el botón de actualización de usuario
    $("#btnUpdateUser").on("click", function (e) {
        e.preventDefault(); // Prevenir el comportamiento por defecto del formulario
        let form = $('#formUpdateUsuario')[0]; // Obtener el formulario
        let formData = new FormData(form); // Crear un objeto FormData para enviar los datos
    
        // Validación extra en el frontend (opcional)
        if (!formData.get('current_password')) {
            showModal("Debes ingresar tu contraseña actual para actualizar los datos.");
            return;
        }
    
        // Usa el valor internacional del plugin
        let fullPhone = iti.getNumber();
        formData.set('phone', fullPhone);

        // Enviar datos de actualización al servidor
        $.ajax({
            url: '../api/up_user.php',  // URL del API
            method: 'POST', // Método HTTP para la petición
            data: formData, // Datos a enviar en la petición
            processData: false, // No procesar los datos como datos de formulario
            contentType: false, // No establecer el tipo de contenido como formulario
            dataType: 'json', // Tipo de dato esperado en la respuesta
            success: function (response) {
                if (response.status === "success") {
                    showModal(response.message); // Mostrar mensaje de éxito    
                    $('#detalleUsuarioModal').modal('hide'); // Cerrar el modal de detalles
                    $('.modal-backdrop').remove(); // Eliminar el fondo modal
                    document.body.classList.remove('modal-open'); // Eliminar la clase modal-open
                    document.body.style.removeProperty('padding-right'); // Eliminar el padding derecho
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
                    // Actualizar la imagen de perfil
                    let newProfilePicture = $("#modalUserExistingPicture").val();
                    if (newProfilePicture) {
                        // Si el usuario subió una nueva imagen, se puede forzar la recarga de la imagen
                        // (esto solo funciona si el input es tipo file y el backend devuelve el nombre del archivo)
                        $("#userProfilePictureCard").attr("src", "../uploads/" + newProfilePicture + "?" + new Date().getTime());
                    }
                } else {
                    showModal(response.message || "Error desconocido");
                }
            },
            error: function (xhr, status, error) {
                let msg = "Error conectando con el servidor: " + error;
                // Intenta extraer el mensaje del backend si existe
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        let json = JSON.parse(xhr.responseText);
                        if (json.message) msg = json.message;
                    } catch (e) {
                        // Si no es JSON, muestra el texto plano (puede ser un error de PHP)
                        if (xhr.responseText.trim() !== "") {
                            msg = xhr.responseText;
                        }
                    }
                }
                showModal(msg);
            }
        });
    });
    // Manejo del clic en el botón de detalles del usuario
    $("#btnDetailUser").on("click", function () {

        // Obtener el ID del usuario
        let user_id = window.USER_ID || $("#UserUser_id").val();
        if (!user_id) {
            showModal("ID de usuario no válido."); // Mensaje de error
            return;
        }
        // Enviar solicitud para obtener detalles del usuario
        $.ajax({
            url: '../api/det_user.php',
            method: 'POST',
            data: { user_id: user_id },
            dataType: 'json',
            success: function (response) {
                if (response.status === "success") {
                    let user = response.data; // Obtener datos del usuario
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

                    let phone = user.phone || "";
                    let prefix = "";
                    let number = phone;

                    let match = phone.match(/^(\+\d{1,4})/);
                    if (match) {
                        prefix = match[1];
                        number = phone.slice(prefix.length);
                    }

                    $("#modalUserPhonePrefix").val(prefix);
                    $("#modalUserPhone").val(number);

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

                    iti.setNumber(user.phone);
                } else {
                    showModal(response.message || "Error al obtener los detalles del usuario.");
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
