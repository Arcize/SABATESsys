<div class="view-box">
    <div class="formContainer">
        <h3 class="h3">INGRESE LOS DATOS DE LA FALLA</h2>
            <form action="" method="POST">
                <div class="userDetails">
                    <div class="inputGroup">
                        <label for="idPC">ID del Equipo:</label>
                        <input class="input" id="idPC" required type="text" name="idPC">
                    </div>
                    <div class="inputGroup">
                        <label for="idUser">ID del Usuario:</label>
                        <input class="input" id="idUser" required type="text" name="idUser">
                    </div>
                    <div class="inputGroup textArea">
                        <label for="content"></label>
                        <textarea class="textarea" id="content" required name="content"></textarea>
                    </div>
                    <div class="btnArea">
                        <a href="index.php?view=employeeTable">
                            <button class="button" type="button">Volver</button>
                        </a>
                        <a href="index.php?view=employee&action=employee_create">
                            <button class="button" type="submit">Guardar</button>
                        </a>
                    </div>
                </div>
    </div>