<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @toastifyCss
</head>

<body>

    {{-- add new product modal start --}}
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" id="add_product_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4 bg-light">
                        <div class="row">
                            <div class="col-lg">
                                <label for="product_name">Nombre del producto</label>
                                <input type="text" name="product_name" class="form-control" placeholder="Nombre del producto" required>
                            </div>
                            <div class="col-lg">
                                <label for="price">Precio</label>
                                <input type="text" name="price" class="form-control" placeholder="Precio" pattern="^\d+(\.\d{1,2})?$" title="Por favor ingresa numeros válidos" required>
                            </div>
                        </div>
                        <div class="my-2">
                            <label for="description">Descripcion</label>
                            <input type="description" name="description" class="form-control" placeholder="Ingresa una descripcion breve del producto" required>
                        </div>
                        <div class="my-2">
                            <label for="phone">Selecciona una categoria</label>
                            <select name="id_catalog" class="form-control" required>

                            </select>
                        </div>
                        <div class="my-2">
                            <label for="product_image">Imagen del Producto</label>
                            <input type="file" name="product_image" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" id="add_product_btn" class="btn btn-primary">Agregar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- add new product modal end --}}

    {{-- edit product modal start --}}
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" id="edit_product_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" id="product_id">
                    <input type="hidden" name="product_image" id="product_image">
                    <div class="modal-body p-4 bg-light">
                        <div class="row">
                            <div class="col-lg">
                                <label for="product_name">Nombre del producto</label>
                                <input type="text" id="product_name" name="product_name" class="form-control" placeholder="Nombre del producto" required>
                            </div>
                            <div class="col-lg">
                                <label for="price">Precio</label>
                                <input type="text" id="price" name="price" class="form-control" placeholder="Precio" pattern="^\d+(\.\d{1,2})?$" title="Por favor ingresa numeros válidos" required>
                            </div>
                        </div>
                        <div class="my-2">
                            <label for="description">Descripcion</label>
                            <input type="description" id="description" name="description" class="form-control" placeholder="Ingresa una descripcion breve del producto" required>
                        </div>
                        <div class="my-2">
                            <label for="phone">Selecciona una categoria</label>
                            <select name="id_catalog" id="id_catalog" class="form-control" required>

                            </select>
                        </div>
                        <div class="my-2">
                            <label for="product_image">Imagen del Producto</label>
                            <input type="file" name="product_image" class="form-control">
                        </div>
                        <div class="mt-2" id="image">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" id="edit_product_btn" class="btn btn-primary">Actualizar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- edit product modal end --}}

    <body class="bg-light">
        <div class="container">
            <div class="row my-5">
                <div class="col-lg-12">
                    <div class="card shadow">
                        <div class="card-header bg-danger d-flex justify-content-between align-items-center">
                            <h3 class="text-light">Mantenimiento de Productos</h3>
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addProductModal"><i
                                    class="bi-plus-circle me-2"></i>Añadir Nuevo Producto</button>
                        </div>
                        <div class="card-body" id="show_all_products">
                            <h1 class="text-center text-secondary my-5">Cargando...</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script
            src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
            crossorigin="anonymous">
        </script>
        <script>
            $(document).ready(function() {
                fetchAllProducts();
                fetchCategories();
            });

            // Fetch categories function
            function fetchCategories() {
                $.ajax({
                    url: "{{ route('fetchCatalog') }}",
                    method: "get",
                    success: function(response) {
                        let options = response.map(function(category) {
                            return `<option value="${category.id}">${category.catalog_name}</option>`;
                        }).join('');
                        $('select[name="id_catalog"]').html('<option value="">Selecciona una categoría</option>' + options);
                    }
                });
            }

            // Fetch all products function
            function fetchAllProducts() {
                $.ajax({
                    url: "{{ route('fetchAll') }}",
                    method: "get",
                    success: function(response) {
                        $("#show_all_products").html(response);
                    }
                });
            }

            // Add product function
            $('#add_product_form').submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                // Show a loading button message
                $("#add_product_btn").text("Agregando...");
                $.ajax({
                    url: "{{ route('store') }}",
                    method: "post",
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status == 200) {
                            Toastify({
                                text: "Producto agregado correctamente",
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                style: {
                                    backgroundColor: "green",
                                }
                            }).showToast();
                            fetchAllProducts();
                        }
                        $("#add_product_btn").text("Agregar Producto");
                        $('#add_product_form')[0].reset();
                        $('#addProductModal').modal('hide');
                    }
                });
            });

            // Get the product data to edit
            $(document).on('click', '.editIcon', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
                $.ajax({
                    url: "{{ route('edit') }}",
                    method: 'get',
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $("#product_id").val(response.id);
                        $('#product_name').val(response.product_name);
                        $('#price').val(response.price);
                        $('#description').val(response.description);
                        $('#id_catalog').val(response.id_catalog);
                        $("#product_image").val(response.product_image);
                        // Image preview
                        $("#image").html(`<img src="storage/images/${response.product_image}" width="100" class="img-fluid img-thumbnail">`);
                    }
                });
            });

            // Update product
            $('#edit_product_form').submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                // Show a loading button message
                $("#edit_product_btn").text("Actualizando...");
                $.ajax({
                    url: "{{ route('update') }}",
                    method: "post",
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status == 200) {
                            Toastify({
                                text: "Producto actualizado correctamente",
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                style: {
                                    backgroundColor: "green",
                                }
                            }).showToast();
                            fetchAllProducts();
                        }
                        $("#edit_product_btn").text("Actualizar Producto");
                        $('#edit_product_form')[0].reset();
                        $('#editProductModal').modal('hide');
                    }
                });
            });

            //Delete product
            $(document).on('click', '.deleteIcon', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
                let csrf = '{{ csrf_token() }}';

                $.ajax({
                    url: "{{ route('delete') }}",
                    method: 'delete',
                    data: {
                        id: id,
                        _token: csrf
                    },
                    success: function(response) {
                        Toastify({
                            text: "Producto eliminado correctamente",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            style: {
                                backgroundColor: "green",
                            }
                        }).showToast();
                        fetchAllProducts();
                    }
                });
            });
        </script>
        @toastifyJs
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    </body>
</html>