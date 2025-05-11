<?php
// Incluir el archivo de conexión a la base de datos
require_once __DIR__ . '/../../backend/core/DBConfig.php';
// Crear una instancia de la clase de conexión
$auth = new DBConfig();
$db = $auth->getConnection();
// Verifica si la sesión está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Verifica si el usuario está autenticado
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Recupera el ID del usuario desde la sesión
    $user_id = $_SESSION['user_id']; // Asumiendo que 'user_id' está guardado en la sesión
    // Consulta para obtener los datos del usuario
    $stmt = $db->prepare("SELECT profile_picture, first_name, last_name, email, phone, username, country, city, birthdate, role_id, status_id FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    // Si el usuario existe, obtenemos sus datos
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $profile_picture = $user['profile_picture'];
        $first_name = $user['first_name'];
        $last_name = $user['last_name'];
        $email = $user['email'];
        $phone = $user['phone'];
        $username = $user['username'];
        $country = $user['country'];
        $city = $user['city'];
        $birthdate = $user['birthdate'];
        $role_id = $user['role_id'];
        $status_id = $user['status_id'];
        $name = $first_name . ' ' . $last_name;
        // Si se encuentra autenticado , mantiene en la pagina
    } else {
        // Si no se encuentra el usuario en la base de datos
        header("Location: /trsi/index.php");
        exit();
    }
} else {
    // El usuario no está autenticado, redirige a la página de index.php
    header("Location: /trsi/index.php");
    exit();
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>user-panel</title>
    <!-- Bootstrap CSS -->
    <link href="/trsi/frontend/dist/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="/trsi/frontend/dist/fontawesome/css/fontawesome.min.css"> 
    <!-- Styles -->
    <link rel="stylesheet" href="/trsi/frontend/css/style.css">
</head>

<body style="background-color: var(--olive-green) !important; color: var(--soft-green);">
    <!-- Navbar -->
    <?php include __DIR__ . '/../../frontend/components/navbar.php'; ?>

    <!-- Contenido principal -->
    <main class="container my-5">

        <!-- Modal alert -->
        <div class="modal fade" id="manageUsersModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true"
            data-bs-target="#detalleUsuarioModal" data-bs-theme="white" style="z-index: 1051 !important;">
            <div class="modal-dialog">
                <div class="modal-content bg-white text-dark" style="background-color: var(--olive-green);">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">title</h5>
                        <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        content
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación para eliminar usuario -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-dark">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-dark">
                        ¿Estás seguro de que deseas eliminar tu cuenta?
                    </div>
                    <div class="modal-footer text-dark">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de detalles de usuario -->
        <div class="modal fade" id="detalleUsuarioModal" tabindex="-1" aria-labelledby="detalleUsuarioLabel"
            aria-hidden="true" style="background-color: var(--soft-green); color: var(--dark-green);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detalleUsuarioLabel">Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formUpdateUsuario" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" id="modalUserUser_id" name="user_id" />
                            <input type="hidden" id="modalUserStatus" name="status_id"/>
                            <input type="hidden" id="modalUserRole" name="role_id"/>
                            <!-- Fila 1: 3 columnas -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="modalUserExistingPicture" class="form-label">Profile Picture</label>
                                    <input type="file" name="profile_picture" accept="image/png,image/jpeg,image/heic"
                                        class="form-control" id="modalUserExistingPicture">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="modalUserFirstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="modalUserFirstName" name="first_name"
                                        placeholder="Enter a first name" required autocomplete="off" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="modalUserLastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="modalUserLastName" name="last_name"
                                        placeholder="Enter a last name" required autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 2: 3 columnas -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="modalUserUsername" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="modalUserUsername" name="username"
                                        placeholder="Enter an username" required autocomplete="off" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="modalUserEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="modalUserEmail" name="email"
                                        placeholder="Enter an email" required autocomplete="off" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="modalUserPhone" class="form-label">Phone</label>
                                    <div class="input-group">
                                        <select class="form-select" id="modalUserPhonePrefix" name="phone_prefix"
                                            required>
                                            <option value="+93">AF (Afghanistan) +93</option>
                                            <option value="+355">AL (Albania) +355</option>
                                            <option value="+213">DZ (Algeria) +213</option>
                                            <option value="+376">AD (Andorra) +376</option>
                                            <option value="+244">AO (Angola) +244</option>
                                            <option value="+1-268">AG (Antigua and Barbuda) +1-268</option>
                                            <option value="+54">AR (Argentina) +54</option>
                                            <option value="+374">AM (Armenia) +374</option>
                                            <option value="+61">AU (Australia) +61</option>
                                            <option value="+43">AT (Austria) +43</option>
                                            <option value="+994">AZ (Azerbaijan) +994</option>
                                            <option value="+1-242">BS (Bahamas) +1-242</option>
                                            <option value="+973">BH (Bahrain) +973</option>
                                            <option value="+880">BD (Bangladesh) +880</option>
                                            <option value="+1-246">BB (Barbados) +1-246</option>
                                            <option value="+375">BY (Belarus) +375</option>
                                            <option value="+32">BE (Belgium) +32</option>
                                            <option value="+501">BZ (Belize) +501</option>
                                            <option value="+229">BJ (Benin) +229</option>
                                            <option value="+975">BT (Bhutan) +975</option>
                                            <option value="+591">BO (Bolivia) +591</option>
                                            <option value="+387">BA (Bosnia and Herzegovina) +387</option>
                                            <option value="+267">BW (Botswana) +267</option>
                                            <option value="+55">BR (Brazil) +55</option>
                                            <option value="+673">BN (Brunei) +673</option>
                                            <option value="+359">BG (Bulgaria) +359</option>
                                            <option value="+226">BF (Burkina Faso) +226</option>
                                            <option value="+257">BI (Burundi) +257</option>
                                            <option value="+855">KH (Cambodia) +855</option>
                                            <option value="+237">CM (Cameroon) +237</option>
                                            <option value="+1">CA (Canada) +1</option>
                                            <option value="+238">CV (Cape Verde) +238</option>
                                            <option value="+236">CF (Central African Republic) +236</option>
                                            <option value="+235">TD (Chad) +235</option>
                                            <option value="+56">CL (Chile) +56</option>
                                            <option value="+86">CN (China) +86</option>
                                            <option value="+57" selected>CO (Colombia) +57</option>
                                            <option value="+269">KM (Comoros) +269</option>
                                            <option value="+243">CD (Congo - Kinshasa) +243</option>
                                            <option value="+242">CG (Congo - Brazzaville) +242</option>
                                            <option value="+506">CR (Costa Rica) +506</option>
                                            <option value="+385">HR (Croatia) +385</option>
                                            <option value="+53">CU (Cuba) +53</option>
                                            <option value="+357">CY (Cyprus) +357</option>
                                            <option value="+420">CZ (Czech Republic) +420</option>
                                            <option value="+45">DK (Denmark) +45</option>
                                            <option value="+253">DJ (Djibouti) +253</option>
                                            <option value="+1-767">DM (Dominica) +1-767</option>
                                            <option value="+1-809">DO (Dominican Republic) +1-809</option>
                                            <option value="+593">EC (Ecuador) +593</option>
                                            <option value="+20">EG (Egypt) +20</option>
                                            <option value="+503">SV (El Salvador) +503</option>
                                            <option value="+240">GQ (Equatorial Guinea) +240</option>
                                            <option value="+291">ER (Eritrea) +291</option>
                                            <option value="+372">EE (Estonia) +372</option>
                                            <option value="+268">SZ (Eswatini) +268</option>
                                            <option value="+251">ET (Ethiopia) +251</option>
                                            <option value="+679">FJ (Fiji) +679</option>
                                            <option value="+358">FI (Finland) +358</option>
                                            <option value="+33">FR (France) +33</option>
                                            <option value="+241">GA (Gabon) +241</option>
                                            <option value="+220">GM (Gambia) +220</option>
                                            <option value="+995">GE (Georgia) +995</option>
                                            <option value="+49">DE (Germany) +49</option>
                                            <option value="+233">GH (Ghana) +233</option>
                                            <option value="+30">GR (Greece) +30</option>
                                            <option value="+1-473">GD (Grenada) +1-473</option>
                                            <option value="+502">GT (Guatemala) +502</option>
                                            <option value="+224">GN (Guinea) +224</option>
                                            <option value="+245">GW (Guinea-Bissau) +245</option>
                                            <option value="+592">GY (Guyana) +592</option>
                                            <option value="+509">HT (Haiti) +509</option>
                                            <option value="+504">HN (Honduras) +504</option>
                                            <option value="+36">HU (Hungary) +36</option>
                                            <option value="+354">IS (Iceland) +354</option>
                                            <option value="+91">IN (India) +91</option>
                                            <option value="+62">ID (Indonesia) +62</option>
                                            <option value="+98">IR (Iran) +98</option>
                                            <option value="+964">IQ (Iraq) +964</option>
                                            <option value="+353">IE (Ireland) +353</option>
                                            <option value="+972">IL (Israel) +972</option>
                                            <option value="+39">IT (Italy) +39</option>
                                            <option value="+1-876">JM (Jamaica) +1-876</option>
                                            <option value="+81">JP (Japan) +81</option>
                                            <option value="+962">JO (Jordan) +962</option>
                                            <option value="+7">KZ (Kazakhstan) +7</option>
                                            <option value="+254">KE (Kenya) +254</option>
                                            <option value="+686">KI (Kiribati) +686</option>
                                            <option value="+383">XK (Kosovo) +383</option>
                                            <option value="+965">KW (Kuwait) +965</option>
                                            <option value="+996">KG (Kyrgyzstan) +996</option>
                                            <option value="+856">LA (Laos) +856</option>
                                            <option value="+371">LV (Latvia) +371</option>
                                            <option value="+961">LB (Lebanon) +961</option>
                                            <option value="+266">LS (Lesotho) +266</option>
                                            <option value="+231">LR (Liberia) +231</option>
                                            <option value="+218">LY (Libya) +218</option>
                                            <option value="+423">LI (Liechtenstein) +423</option>
                                            <option value="+370">LT (Lithuania) +370</option>
                                            <option value="+352">LU (Luxembourg) +352</option>
                                            <option value="+261">MG (Madagascar) +261</option>
                                            <option value="+265">MW (Malawi) +265</option>
                                            <option value="+60">MY (Malaysia) +60</option>
                                            <option value="+960">MV (Maldives) +960</option>
                                            <option value="+223">ML (Mali) +223</option>
                                            <option value="+356">MT (Malta) +356</option>
                                            <option value="+692">MH (Marshall Islands) +692</option>
                                            <option value="+222">MR (Mauritania) +222</option>
                                            <option value="+230">MU (Mauritius) +230</option>
                                            <option value="+52">MX (Mexico) +52</option>
                                            <option value="+691">FM (Micronesia) +691</option>
                                            <option value="+373">MD (Moldova) +373</option>
                                            <option value="+377">MC (Monaco) +377</option>
                                            <option value="+976">MN (Mongolia) +976</option>
                                            <option value="+382">ME (Montenegro) +382</option>
                                            <option value="+212">MA (Morocco) +212</option>
                                            <option value="+258">MZ (Mozambique) +258</option>
                                            <option value="+95">MM (Myanmar) +95</option>
                                            <option value="+264">NA (Namibia) +264</option>
                                            <option value="+674">NR (Nauru) +674</option>
                                            <option value="+977">NP (Nepal) +977</option>
                                            <option value="+31">NL (Netherlands) +31</option>
                                            <option value="+64">NZ (New Zealand) +64</option>
                                            <option value="+505">NI (Nicaragua) +505</option>
                                            <option value="+227">NE (Niger) +227</option>
                                            <option value="+234">NG (Nigeria) +234</option>
                                            <option value="+850">KP (North Korea) +850</option>
                                            <option value="+389">MK (North Macedonia) +389</option>
                                            <option value="+47">NO (Norway) +47</option>
                                            <option value="+968">OM (Oman) +968</option>
                                            <option value="+92">PK (Pakistan) +92</option>
                                            <option value="+680">PW (Palau) +680</option>
                                            <option value="+970">PS (Palestine) +970</option>
                                            <option value="+507">PA (Panama) +507</option>
                                            <option value="+675">PG (Papua New Guinea) +675</option>
                                            <option value="+595">PY (Paraguay) +595</option>
                                            <option value="+51">PE (Peru) +51</option>
                                            <option value="+63">PH (Philippines) +63</option>
                                            <option value="+48">PL (Poland) +48</option>
                                            <option value="+351">PT (Portugal) +351</option>
                                            <option value="+974">QA (Qatar) +974</option>
                                            <option value="+40">RO (Romania) +40</option>
                                            <option value="+7">RU (Russia) +7</option>
                                            <option value="+250">RW (Rwanda) +250</option>
                                            <option value="+1-869">KN (Saint Kitts and Nevis) +1-869</option>
                                            <option value="+1-758">LC (Saint Lucia) +1-758</option>
                                            <option value="+1-784">VC (Saint Vincent and the Grenadines) +1-784</option>
                                            <option value="+685">WS (Samoa) +685</option>
                                            <option value="+378">SM (San Marino) +378</option>
                                            <option value="+239">ST (Sao Tome and Principe) +239</option>
                                            <option value="+966">SA (Saudi Arabia) +966</option>
                                            <option value="+221">SN (Senegal) +221</option>
                                            <option value="+381">RS (Serbia) +381</option>
                                            <option value="+248">SC (Seychelles) +248</option>
                                            <option value="+232">SL (Sierra Leone) +232</option>
                                            <option value="+65">SG (Singapore) +65</option>
                                            <option value="+421">SK (Slovakia) +421</option>
                                            <option value="+386">SI (Slovenia) +386</option>
                                            <option value="+677">SB (Solomon Islands) +677</option>
                                            <option value="+252">SO (Somalia) +252</option>
                                            <option value="+27">ZA (South Africa) +27</option>
                                            <option value="+82">KR (South Korea) +82</option>
                                            <option value="+34">ES (Spain) +34</option>
                                            <option value="+94">LK (Sri Lanka) +94</option>
                                            <option value="+249">SD (Sudan) +249</option>
                                            <option value="+597">SR (Suriname) +597</option>
                                            <option value="+46">SE (Sweden) +46</option>
                                            <option value="+41">CH (Switzerland) +41</option>
                                            <option value="+963">SY (Syria) +963</option>
                                            <option value="+886">TW (Taiwan) +886</option>
                                            <option value="+992">TJ (Tajikistan) +992</option>
                                            <option value="+255">TZ (Tanzania) +255</option>
                                            <option value="+66">TH (Thailand) +66</option>
                                            <option value="+228">TG (Togo) +228</option>
                                            <option value="+676">TO (Tonga) +676</option>
                                            <option value="+1-868">TT (Trinidad and Tobago) +1-868</option>
                                            <option value="+216">TN (Tunisia) +216</option>
                                            <option value="+90">TR (Turkey) +90</option>
                                            <option value="+993">TM (Turkmenistan) +993</option>
                                            <option value="+688">TV (Tuvalu) +688</option>
                                            <option value="+256">UG (Uganda) +256</option>
                                            <option value="+380">UA (Ukraine) +380</option>
                                            <option value="+971">AE (United Arab Emirates) +971</option>
                                            <option value="+44">GB (United Kingdom) +44</option>
                                            <option value="+1">US (United States) +1</option>
                                            <option value="+598">UY (Uruguay) +598</option>
                                            <option value="+998">UZ (Uzbekistan) +998</option>
                                            <option value="+678">VU (Vanuatu) +678</option>
                                            <option value="+58">VE (Venezuela) +58</option>
                                            <option value="+84">VN (Vietnam) +84</option>
                                            <option value="+967">YE (Yemen) +967</option>
                                            <option value="+260">ZM (Zambia) +260</option>
                                            <option value="+263">ZW (Zimbabwe) +263</option>
                                        </select>
                                        <input type="text" class="form-control" id="modalUserPhone" name="phone"
                                            placeholder="Enter phone number" required autocomplete="off"
                                            pattern="\d{7,10}" title="El número debe contener entre 7 y 10 dígitos" />
                                    </div>
                                </div>
                            </div>

                            <!-- Fila 3: 3 columnas -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="modalUserBirthdate" class="form-label">Birthdate</label>
                                    <input type="date" class="form-control" id="modalUserBirthdate" name="birthdate"
                                        required autocomplete="off" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="modalUserCurrentPassword" class="form-label">Contraseña actual</label>
                                    <input type="password" class="form-control" id="modalUserCurrentPassword" name="current_password"
                                        placeholder="Introduce tu contraseña actual" required autocomplete="off" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="modalUserPassword" class="form-label">Nueva contraseña</label>
                                    <input type="text" class="form-control" id="modalUserPassword" name="password"
                                        placeholder="Introduce la nueva contraseña" required autocomplete="off" />
                                </div>                              
                            </div>

                            <!-- Fila 4: 2 columnas -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserCountry" class="form-label">País</label>
                                    <select class="form-select" id="modalUserCountry" name="country" default="Seleccione un país" required>
                                        <option value="">Seleccione un país</option>
                                        <option value="Colombia">Colombia</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3" id="ciudadColombiaDiv" style="display:none;">
                                    <label for="modalUserCity" class="form-label">Ciudad</label>
                                    <select class="form-select" id="modalUserCity" name="city" default="Seleccione una ciudad">
                                        <option value="">Seleccione una ciudad</option>
                                        <option value="Arauca">Arauca</option>
                                        <option value="Armenia">Armenia</option>
                                        <option value="Barranquilla">Barranquilla</option>
                                        <option value="Bogotá">Bogotá</option>
                                        <option value="Bucaramanga">Bucaramanga</option>
                                        <option value="Cali">Cali</option>
                                        <option value="Cartagena">Cartagena</option>
                                        <option value="Cúcuta">Cúcuta</option>
                                        <option value="Florencia">Florencia</option>
                                        <option value="Ibagué">Ibagué</option>
                                        <option value="Leticia">Leticia</option>
                                        <option value="Manizales">Manizales</option>
                                        <option value="Medellín">Medellín</option>
                                        <option value="Mitú">Mitú</option>
                                        <option value="Mocoa">Mocoa</option>
                                        <option value="Montería">Montería</option>
                                        <option value="Neiva">Neiva</option>
                                        <option value="Pasto">Pasto</option>
                                        <option value="Pereira">Pereira</option>
                                        <option value="Popayán">Popayán</option>
                                        <option value="Puerto Carreño">Puerto Carreño</option>
                                        <option value="Quibdó">Quibdó</option>
                                        <option value="Riohacha">Riohacha</option>
                                        <option value="San Andrés">San Andrés</option>
                                        <option value="San José del Guaviare">San José del Guaviare</option>
                                        <option value="Santa Marta">Santa Marta</option>
                                        <option value="Sincelejo">Sincelejo</option>
                                        <option value="Tunja">Tunja</option>
                                        <option value="Valledupar">Valledupar</option>
                                        <option value="Villavicencio">Villavicencio</option>
                                        <option value="Yopal">Yopal</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3" id="ciudadOtroDiv" style="display:none;">
                                    <label for="modalUserCityOtro" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" id="modalUserCityOtro" name="city_otro" placeholder="Ingrese su ciudad" />
                                </div>           
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="btnUpdateUser">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Card del usuario -->
                <div class="card text-center shadow-lg"
                    style="background-color: var(--soft-green) !important; color: var(--dark-green) !important;">
                    <div class="card-header bg-white text-dark"
                        style="background-color: var(--soft-green) !important; color: var(--dark-green) !important;">
                        <h5>User Management Panel</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Imagen de perfil -->
                            <?php if (!empty($profile_picture)): ?>
                            <img id="userProfilePictureCard" src="/trsi/uploads/<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile"
                                class="img-thumbnail mb-2" style="height: 200px; width: 200px;">
                            <?php endif; ?>

                            <!-- Información del usuario -->
                            <div class="col-md-8 text-start">
                                <input type="hidden" id="UserUser_id" value="<?php echo htmlspecialchars($user_id); ?>">
                                <p><strong>ID:</strong> <span id="userIdCard"><?php echo htmlspecialchars($user_id); ?></span></p>
                                <p><strong>Status:</strong> <span id="userStatusCard"><?php echo htmlspecialchars($status_id); ?></span></p>
                                <p><strong>Role:</strong> <span id="userRoleCard"><?php echo htmlspecialchars($role_id); ?></span></p>
                                <p><strong>First name:</strong> <span id="userFirstNameCard"><?php echo htmlspecialchars($first_name); ?></span></p>
                                <p><strong>Last name:</strong> <span id="userLastNameCard"><?php echo htmlspecialchars($last_name); ?></span></p>
                                <p><strong>Username:</strong> <span id="userUsernameCard"><?php echo htmlspecialchars($username); ?></span></p>
                                <p><strong>Email:</strong> <span id="userEmailCard"><?php echo htmlspecialchars($email); ?></span></p>
                                <p><strong>Phone:</strong> <span id="userPhoneCard"><?php echo htmlspecialchars($phone); ?></span></p>
                                <p><strong>Location:</strong> <span id="userCityCard"><?php echo htmlspecialchars($city); ?></span>, <span id="userCountryCard"><?php echo htmlspecialchars($country); ?></span></p>
                                <p><strong>Birthdate:</strong> <span id="userBirthdateCard"><?php echo htmlspecialchars($birthdate); ?></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light"
                        style="background-color: var(--soft-green) !important; color: var(--dark-green) !important;">
                        <button type="button" class="btn btn-primary me-2" id="btnDetailUser">Edit profile</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- jQuery -->
    <script src="/trsi/frontend/dist/jquery/js/jquery.min.js"></script>
    <!-- Bootstrap JS With Popper-->
    <script src="/trsi/frontend/dist/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- Chart JS -->
    <script src="/trsi/frontend/dist/chart/js/chart.umd.min.js"></script>
    <!-- FontAwesome JS -->
    <script src="/trsi/frontend/dist/fontawesome/js/all.min.js"></script>
    <!-- user-panel JS -->
    <script src="/trsi/frontend/js/user-panel.js"></script>
</body>

</html>