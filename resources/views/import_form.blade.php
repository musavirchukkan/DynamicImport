<!DOCTYPE html>
<html>

<head>
    <title>File Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div>
            <h2>Dynamic Import</h2>
        </div>
        <div>
            <form class="row g-3" id="import-form" save-action="{{ route('import.process') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="formFile" class="form-label">import file</label>
                    <input class="form-control" type="file" id="file" name="file">
                </div>
                <select class="form-select" aria-label="Default select example" name="module">
                    <option value='' selected>select menu</option>
                    <option value="student">Students</option>
                    <option value="staff">Staffs</option>
                    <option value="parent">Parents</option>
                </select>
                <div class="col-auto">
                    <button type="submit" id="mapping-button" class="btn btn-primary mb-3">upload</button>
                </div>
            </form>
        </div>
        <div>
            <h3>mapping</h3>
            <form class="row g-3" id="maping-form" save-action="{{ route('import.store') }}"
                enctype="multipart/form-data">
                @csrf
            <div class="col-auto">
                <button type="submit" id="import-button" class="btn btn-primary mb-3">Import</button>
            </div>
            <table class="table">
                <thead>
                    <tr>

                        <th scope="col">Excel</th>
                        <th scope="col">Workpex</th>
                        <th scope="col">sample</th>
                    </tr>
                </thead>
                <tbody id="mappingData">

                </tbody>
            </table>
            <input type="text" name='headingRow' id='headingRow' hidden>
            <input type="text" name='filePath' id='filePath' hidden >
            </form>

        </div>

        <div id="result-container">
            <ul id="result-list"></ul>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            $('#import-form').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);


                $.ajax({
                    type: 'POST',
                    url: $('#import-form').attr('save-action'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#headingRow').val(response.data.headingRow);
                        $('#filePath').val(response.data.filePath);
                        var tableRows = '';
                        for (var i = 0; i < response.data.headingRow.length; i++) {
                            var optionHtml = '';
                            optionHtml += `<option value='' selected>Select</option>`;
                            for (var key in response.data.columnNames) {
                                if (response.data.columnNames.hasOwnProperty(key)) {
                                    optionHtml += `<option value="${key}">${response.data.columnNames[key]}</option>`;
                                }
                            }

                            tableRows += `<tr>
                                <td>` + response.data.headingRow[i] + `</td>
                                <td>
                                    <select class="form-select" aria-label="Default select example" name="column`+i+`">
                                        ` + optionHtml + `
                                    </select>
                                </td>
                                <td>` + response.data.firstRow[i] + `</td>
                            </tr>`;
                        }
                        $('#mappingData').html(tableRows);
                    },
                    error: function(xhr, status, error) {
                        $('#result-list').append('<li>Error: ' + error + '</li>');
                    },
                });
            });

            $('#maping-form').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);


                $.ajax({
                    type: 'POST',
                    url: $('#maping-form').attr('save-action'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                    },

                });

            });
        });
    </script>
</body>

</html>
